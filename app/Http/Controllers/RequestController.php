<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Fleet;
use App\Models\ActivityLog;
use App\Models\Request as ModelsRequest;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function employeeRequestFleetInsert(Request $request)
    {
       if(
        !empty($request->id_employee) &&
        !empty($request->id_fleet) &&
        !empty($request->date_needed)
        ){
            $requestM= new ModelsRequest();
            $requestM->type="fleet";
            $requestM->id_employee=$request->id_employee;
            $requestM->id_fleet=$request->id_fleet;
            $requestM->date_needed= $request->date_needed;
            $requestM->cant = $request->cant;
            $requestM->status="in_process";
            $requestM->save();
            $requestM= ModelsRequest::where("id",$requestM->id)->first();     
            $employee= User::where("id",$request->id_employee)->first();
            $fleet= Fleet::where("id",$request->id_fleet)->first();
            ActivityLog::create([
                'type' => 20,
                'title' => 'New vehicle Request',
                'desc' => 'Employee '.$employee->name.' has requested a vehicle n '.$fleet->n,
                'href' => '#',
                'id_reference' => $requestM->id,
                'type_sql' => 0
            ]);
            return response()->json(["request"=> $requestM,"employee"=> $employee]);
       }else{
        return response()->json(["error"=>"fields are missing"],400);
       }
    }

    public function employeeRequestToolInsert(Request $request)
    {
        if(
            !empty($request->id_employee) &&
            !empty($request->id_tool) &&
            !empty($request->date_needed)
            ){
                $requestM= new ModelsRequest();
                $requestM->type="tool";
                $requestM->id_employee=$request->id_employee;
                $requestM->id_tool=$request->id_tool;
                $requestM->date_needed= $request->date_needed;
                $requestM->cant = $request->cant;
                $requestM->status="in_process";
                $requestM->save();
                $requestM= ModelsRequest::where("id",$requestM->id)->first();     
                $employee= User::where("id",$request->id_employee)->first();
                $tool= Fleet::where("id",$request->id_tool)->first();
                ActivityLog::create([
                    'type' => 20,
                    'title' => 'New Inventory Request',
                    'desc' => 'Employee '.$employee->name.' has requested an inventory '.$tool->title,
                    'href' => '#',
                    'id_reference' => $requestM->id,
                    'type_sql' => 0
                ]);
                return response()->json(["request"=> $requestM,"employee"=> $employee]);
        }else{
            return response()->json(["error"=>"fields are missing"],400);
        }
    }
}
