<?php
namespace App\Http\Controllers;
use App\Helpers\SICAP;
use App\Models\ActivityLog;
use App\Models\Reminder;
use App\Models\ReminderNotifyUser;
use App\Models\ReminderIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
class ReminderController extends Controller
{
    public function dataTable(Request $request)
    {
        $service = new Reminder();
        $results = $service->getDataTable($request);
        return response()->json($results);
    }

    public function create(Request $request)
    {
        $id_fleet = $request->route('id_fleet');
        $reminder = new Reminder;
        if($id_fleet)$reminder->id_fleet = $id_fleet;
        $reminder->type = 'service';
        //$reminder->time_interval = 2;
        return view('reminder_create', compact('id_fleet', 'reminder'));
    }

    public function edit(Request $request)
    {
        $id = $request->route('id');
        $id_fleet = $request->route('id_fleet');
        $reminder = Reminder::find($id);
        $users = $reminder->users();
        return view('reminder_create', compact('id_fleet', 'reminder', 'users'));
    }

    public function save(Request $request)
    {
        $id = $request->id;
        $type_sql = $id>0?1:0;
        $reminder = $id?Reminder::find($id):new Reminder;
        $reminder->id_fleet = $request->id_fleet;
        $reminder->type = $request->type;
        $reminder->id_service = null;
        $reminder->id_interface = null;
        if($reminder->type=='service'){
            $reminder->id_service = $request->id_service;
        }else if($reminder->type=='renewal'){
            $reminder->id_interface = $request->id_interface;
        }
        $reminder->task = $request->task;
        $reminder->description = $request->description;
        $reminder->common_interval = $request->common_interval;
        $reminder->time_interval_unit = $request->time_interval_unit;
        $reminder->status = $request->status;
        if(!$id){
            $reminder->created_at = now();
        }
        $reminder->updated_at = now();
        $reminder->save();

        if($id){
            $olds = ReminderNotifyUser::where('id_reminder', $id)->get();
            foreach($olds as $old_watcher){
                $exist = false;
                foreach($request->id_watchers as $watcher){
                    if($old_watcher->id_role==-$watcher||$old_watcher->id_user==$watcher){
                        $exist = true;
                        break;
                    }
                }
                if(!$exist)$old_watcher->delete();
            }
        }
        foreach($request->id_watchers as $watcher){
            $olds = ReminderNotifyUser::where('id_reminder', $reminder->id);
            if($watcher>0){
                if(!$olds->where('id_user', $watcher)->count()){
                    $old = new ReminderNotifyUser;
                    $old->id_reminder = $reminder->id;
                    $old->id_user = $watcher;
                    $old->save();
                }
            }else{
                if(!$olds->where('id_role', -$watcher)->count()){
                    $old = new ReminderNotifyUser;
                    $old->id_reminder = $reminder->id;
                    $old->id_role = -$watcher;
                    $old->save();
                }
            }
        }

        $href = "/fleet_detail/".$reminder->id_fleet;
        ActivityLog::create([
            'type' => 10,
            'title' => $type_sql==0?'New reminder has created':'Reminder has updated',
            'desc' => $reminder->task,
            'href' => $href,
            'id_reference' => $reminder->id,
            'type_sql' => $type_sql
        ]);

        if($request->prev_id_fleet)
            return redirect('fleet_detail/'.$request->prev_id_fleet)->with('success', 'Reminder saved successfully.');
        else
            return redirect('fleet_detail/'.$request->prev_id_fleet)->with('success', 'Reminder saved successfully.');
    }

    public function delete($id)
    {
        ReminderNotifyUser::where('id_reminder', $id)->delete();
        $reminder = Reminder::find($id);
        $href = "/fleet_detail/".$reminder->id_fleet;
        ActivityLog::create([
            'type' => 10,
            'title' => 'Reminder has deleted',
            'desc' => $reminder->task,
            'href' => $href,
            'id_reference' => $reminder->id,
            'type_sql' => 2
        ]);
        $reminder->delete();
        return response()->json(["delete"=> $id]);
    }
}
