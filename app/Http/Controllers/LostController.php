<?php

namespace App\Http\Controllers;
use App\Models\ActivityLog;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Fleet;
use App\Models\Lost;
use App\Models\Request as ModelsRequest;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LostController extends Controller
{
    public function lostInsert(Request $request)
    {
       if(
        !empty($request->details) &&
        !empty($request->last_seen) &&
        !empty($request->ampm) &&
        !empty($request->time) &&
        !empty($request->date_incident) &&
        !empty($request->id_employee) &&
        !empty($request->id_tool) &&
        !empty($request->id_checkout)
        ){
            $lost = new Lost();
            $lost->lost=$request->lost;
            $lost->stolen=$request->stolen;
            $lost->details=$request->details;
            $lost->last_seen=$request->last_seen;
            $lost->ampm=$request->ampm;
            $lost->time=$request->time;
            $lost->date_incident=$request->date_incident;
            $lost->id_employee=$request->id_employee;
            $lost->id_tool=$request->id_tool;
            $lost->id_checkout=$request->id_checkout;
            $lost->status="in_process";
            $lost->save();
           
            $lost = Lost::where("id",$lost->id)->first();   
            
            ActivityLog::create([
                'type' => 19,
                'title' => 'New Lost Report',
                'desc' => $lost->lost,
                'href' => "#",
                'id_reference' => $damage->id,
            ]);

            return response()->json(["lost"=> $lost]);
       }else{
        return response()->json(["error"=>"fields are missing"],400);
       }
    }
}
