<?php

namespace App\Http\Controllers;

use App\Models\CheckOut;
use App\Models\Fleet;
use App\Models\ReportProblem;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportProblemController extends Controller
{
    public function serviceRequestTable(Request $request){
        $data = new ReportProblem();
        $data = $data->getDataTable($request);
        return response()->json($data);
    }

    public function employeeFleetInsert(Request $request)
    {
       if(
        !empty($request->place) &&
        !empty($request->id_employee) &&
        !empty($request->id_fleet) &&
        !empty($request->id_request_category)
        ){
            $reportProblem= new ReportProblem();
            $reportProblem->type="fleet";
            $reportProblem->place=$request->place;
            if(!empty($request->lat) && !empty($request->lng) ){
                $reportProblem->lat=$request->lat;
                $reportProblem->lng=$request->lng;
            }
            $reportProblem->description=$request->description;
            $reportProblem->id_employee=$request->id_employee;
            $reportProblem->id_fleet=$request->id_fleet;
            $reportProblem->id_request_category=$request->id_request_category;
            $reportProblem->status="in_process";
            $audioName = null;
            if ($request->hasFile('audio')) {
                $ext = $request->file('audio')->extension();
                $audioObj =  $request->audio;
                $audioName = $reportProblem->id . time() . ".$ext";
                Storage::disk('public')->put("audios/$audioName",file_get_contents($audioObj));
            }
            $reportProblem->audio= $audioName;
            $reportProblem->save();
            $fleet = Fleet::where("id",$request->id_fleet)->first();     
            $employee= User::where("id",$request->id_employee)->first();

            ActivityLog::create([
                'type' => 8,
                'title' => 'New Problem Report',
                'desc' => 'Employee '.$employee->name.' has reported for vehicle n '.$fleet->n,
                'href' => "#",
                'id_reference' => $reportProblem->id,
            ]);

            return response()->json(["report_problem"=> $reportProblem,"id"=> $reportProblem->id,"fleet"=>$fleet,"employee"=> $employee]);
       }else{
            return response()->json(["error"=>"fields are missing"],400);
       }
    }
}
