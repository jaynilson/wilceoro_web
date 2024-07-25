<?php
namespace App\Http\Controllers;
use App\Helpers\SICAP;
use App\Models\User;
use App\Models\Asset;
use App\Models\Fleet;
use App\Models\Record;
use App\Models\RecordTool;
use App\Models\Service;
use App\Models\Tool;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
class ServiceController extends Controller
{
    public function insert(Request $request)
    {
        $fleet = Fleet::where("id",$request->id_fleet)->first();
        $service_params = array_merge(array_filter($request->except('id','needed_date', 'completed_date')), ['cost' => 0,'miles'=> $fleet->last_odometer]);
        $service = Service::create($service_params);

        $service->needed_date = (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $request->needed_date))?SICAP::decodeDate($request->needed_date):$request->needed_date;
        $service->completed_date = (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $request->completed_date))?SICAP::decodeDate($request->completed_date):$request->completed_date;
        $service->save();

        $fleet->status="in-service";
        $fleet->save();

        $employee = User::where("id",$request->id_employee)->first();
        $usersNotify=User::whereIn("id_rol",[3,1])->get();
        $userTmp=User::where('id',$request->id_employee)->first();
        
        //start notification
        $title_n = 'Maintenance for vehicle';
        $msg_n = "Employee {$userTmp->name} service for vehicle with n {$fleet->n}. Description: \"{$request->description}\"";
        $path_n = 'service_fleet';
        $cod_sender = $request->id_employee;
        $type_sender = 4;
        $type_notification = 'message'; //redirect,message,modal_redirect,none
        $use_lang_title = 'false';
        $use_lang_msg = 'false';
        $icon = 'wilcoerp-logo.png'; //name-image.jpg or html code
        $type_icon = 'image-public'; //html-class, image-public
        $paramsTitleNotifi = [];
        $paramsMsgNotifi = [];
        $sendMail = true;
        ActivityLog::create([
            'type' => 1,
            'title' => $title_n,
            'desc' => $msg_n,
            'href' => "/fleet_manager_services/".$service->id,
            'id_reference' => $service->id,
            'type_sql' => 0,
        ]);
        foreach ($usersNotify as $key => $user) {
            if(!$user->notify_service) continue;   
            $cod_receiver = $user->id;
            $type_receiver = $user->id_rol;
            SICAP::sendNotification($title_n, $msg_n, $path_n, $cod_sender, $cod_receiver, $type_sender, $type_receiver, $type_notification, $use_lang_title, $use_lang_msg, $paramsTitleNotifi, $paramsMsgNotifi, $sendMail, $icon, $type_icon, $user->email);
        }
        return redirect('fleet_manager_services')->with('success', 'Element created successfully.');
    }

    public function insertRecord(Request $request)
    {
      $record = new Record();
      $record->description=$request->description;
      $record->id_service=$request->id_service;
      $record->id_mechanic=$request->id_mechanic;
      //   $record->id_tool=$request->id_tool;
      $record->id_category=$request->id_category;
      $record->hour_spend=$request->hour_spend;
      $cost = 0;
      if($request->has('cost')&&$request->cost!=null) $cost = $request->cost;
      $record->cost=$cost;
      $record->save();

      $amounts = explode(',', $request->amounts);
      foreach(explode(',', $request->id_tool) as $index=>$id_tool){
        $record_tool = new RecordTool();
        $record_tool->id_record = $record->id;
        $record_tool->id_tool = $id_tool;
        $record_tool->amount = $amounts[$index];
        $record_tool->save();

        $tool = Tool::find($id_tool);
        if($tool && $tool->available_stock<=0){
            $title_n = 'Inventory Out of Stock';
            $msg_n = 'Inventory '.$tool->title.' is currently out of stock due to a new service request.';
            $path_n = 'insert_record';
            $usersNotify=User::whereIn("id_rol",[1,3])->get();
            foreach ($usersNotify as $key => $user) {
                if(!$user->notify_outofstock) continue; 
                $cod_sender = $request->id_mechanic;
                $cod_receiver = $user->id;
                $type_sender = 4;
                $type_receiver = $user->id_rol;
                $type_notification = 'message';
                $use_lang_title = 'false';
                $use_lang_msg = 'false';
                $paramsTitleNotifi = [];
                $paramsMsgNotifi = [];
                $sendMail = true;
                $icon = 'wilcoerp-logo.png';
                $type_icon = 'image-public';
                SICAP::sendNotification($title_n, $msg_n, $path_n, $cod_sender, $cod_receiver, $type_sender, $type_receiver, $type_notification, $use_lang_title, $use_lang_msg, $paramsTitleNotifi, $paramsMsgNotifi, $sendMail, $icon, $type_icon, $user->email);
            }
        }
      }
      return response()->json(["record"=>$record],200);
    }

    public function update(Request $request)
    {
        $service =  Service::findOrFail($request->id);
        $userTmp = User::where('id',$service->id_employee)->first();
        $fleet = Fleet::where("id",$service->id_fleet)->first();
        $title_n = 'Maintenance for vehicle has updated';
        $msg_n = "Employee {$userTmp->name} service for vehicle with n {$fleet->n}.";
        $service->update(array_filter($request->except('id','needed_date', 'completed_date')));
        $service->needed_date = (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $request->needed_date))?SICAP::decodeDate($request->needed_date):$request->needed_date;
        $service->completed_date = (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $request->completed_date))?SICAP::decodeDate($request->completed_date):$request->completed_date;
        $service->save();

        ActivityLog::create([
            'type' => 1,
            'title' => $title_n,
            'desc' => $msg_n,
            'href' => "/fleet_manager_services/".$service->id,
            'id_reference' => $service->id,
            'type_sql' => 1,
        ]);
        return redirect('fleet_manager_services')->with('success', 'Element saved successfully.');
    }

    public function recordUpdate(Request $request)
    {
        $record =  Record::findOrFail($request->id);
        $record->update(array_filter($request->except('id', 'id_tool', 'amounts')));
        
        // if($request->has('id_tool')&&$request->id_tool!=''){
        //     $id_tools = explode(',', $request->id_tool);
        //     RecordTool::whereIn('id_tool', $id_tools)->delete();
        //     $amounts = explode(',', $request->amounts);
        //     foreach($id_tools as $index=>$id_tool){
        //         $record_tool = new RecordTool();
        //         $record_tool->id_record = $record->id;
        //         $record_tool->id_tool = $id_tool;
        //         $record_tool->amount = $amounts[$index];
        //         $record_tool->save();

        //         $tool = Tool::find($id_tool);
        //         if($tool && $tool->available_stock<=0){
        //             $title_n = 'Inventory Out of Stock';
        //             $msg_n = 'Inventory '.$tool->title.' is currently out of stock due to a new service request.';
        //             $path_n = 'update_record';
        //             $usersNotify=User::whereIn("id_rol",[1,3])->get();
        //             foreach ($usersNotify as $key => $user) {
        //                 if(!$user->notify_outofstock) continue; 
        //                 $cod_sender = $request->id_mechanic;
        //                 $cod_receiver = $user->id;
        //                 $type_sender = 4;
        //                 $type_receiver = $user->id_rol;
        //                 $type_notification = 'message';
        //                 $use_lang_title = 'false';
        //                 $use_lang_msg = 'false';
        //                 $paramsTitleNotifi = [];
        //                 $paramsMsgNotifi = [];
        //                 $sendMail = true;
        //                 $icon = 'wilcoerp-logo.png';
        //                 $type_icon = 'image-public';
        //                 SICAP::sendNotification($title_n, $msg_n, $path_n, $cod_sender, $cod_receiver, $type_sender, $type_receiver, $type_notification, $use_lang_title, $use_lang_msg, $paramsTitleNotifi, $paramsMsgNotifi, $sendMail, $icon, $type_icon, $user->email);
        //             }
        //         }
        //     }
        // }
        return Redirect::back()->with('success', 'Element saved successfully.');
    }

    public function dataTable(Request $request)
    {
        $service = new Service();
        $results = $service->getDataTable($request);
        return response()->json($results);
    }

    public function recordsDataTable(Request $request)
    {
        $service = new Record();
        $results = $service->getDataTable($request);
        return response()->json($results);
    }

    public function delete(Request $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        $title="";
        foreach ($ids as $key => $id) {
            $service = Service::find($id);
            if ($service) {
                $userTmp = User::where('id',$service->id_employee)->first();
                $fleet = Fleet::where("id",$service->id_fleet)->first();
                $title .= ($title==''?'':', ')."Employee {$userTmp->name} service for vehicle with n {$fleet->n}.";
                Record::where('id_service', $service->id)->delete();
                $service->delete();
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }
        ActivityLog::create([
            'type' => 1,
            'title' => $cantSuccsess.' services has deleted',
            'desc' => $title,
            'href' => "#",
            'id_reference' => 0,
            'type_sql' => 2
        ]);
        return $cantSuccsess <= 1 ?
        redirect('fleet_manager_services')->with('success', $cantSuccsess . ' item successfully removed.')
        :
        redirect('fleet_manager_services')->with('success', $cantSuccsess . ' items successfully removed.');
    }

    public function deleteFile(Request $request)
    {
        Asset::where('id', $request->id_delete)->delete();
        return response()->json(["id_delete"=>$request->id_delete],200);
    }
    
    public function recordDelete(Request $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        foreach ($ids as $key => $id) {
            if (Record::where('id', $id)->delete()) {
                RecordTool::where('id_record', $id)->delete();
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }
        return $cantSuccsess <= 1 ?
        Redirect::back()->with('success', $cantSuccsess . ' item successfully removed.')
        :
        Redirect::back()->with('success', $cantSuccsess . ' items successfully removed.');
    }
    
    public function toolList(Request $request)
    {
        $tool = new Tool();
        $results = $tool->getList($request);
        return response()->json($results);
    }

    public function recordFile(Request $request)
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        try {
            $myFiles=$request['myFiles'];
            $saved=[];
            for ($i=0; $i < count($myFiles); $i++) { 
                $file_record =  $myFiles[$i];
                $filename = $file_record->getClientOriginalName();
                Storage::disk("public")->put("files_records/$filename",  file_get_contents($file_record));
                $file= new Asset();
                $file->type="record";
                $file->picture=$filename;
                $file->id_reference=$request->id_record;
                $file->save();
                array_push($saved, $file);
            }
            return response()->json(["files"=>$saved],200);
        } catch (\Exception $e) {
            $out->writeln("Error ");
            $out->writeln($e);
            return response()->json($e->getMessage(),400);
        }
    }

    public function getServiceList(Request $request){
        $id_fleet = $request->id_fleet;
        $query = Service::select();
        if($id_fleet) $query = $query->where('id_fleet', $id_fleet);
        return response()->json($query->get());
    }
}
