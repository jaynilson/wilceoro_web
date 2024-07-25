<?php

namespace App\Http\Controllers;

use App\Helpers\SICAP;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Fleet;
use App\Models\Tool;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{
    public function employeeFleetInsert(Request $request)
    {
       if(
        !empty($request->place) &&
        !empty($request->id_employee) &&
        !empty($request->id_fleet)
        ){
            $checkin= new CheckIn();
            $checkin->type="tool";
            $checkin->place=$request->place;
            if(!empty($request->lat) && !empty($request->lng) ){
                $checkin->lat=$request->lat;
                $checkin->lng=$request->lng;
            }
            $checkin->odometer_reading=$request->odometer_reading;
            $checkin->id_employee=$request->id_employee;
            $checkin->id_fleet=$request->id_fleet;
            $checkin->id_check_out=$request->id_check_out;
            $checkin->status="in_process";
            if(!empty($request->manual_date)){
                $checkin->created_at = $request->manual_date;
                $checkin->is_manual = true;
            }
            $checkin->save();
            $checkout = CheckOut::where("id",$request->id_check_out)->first();     
            $checkout->status="finalized";
            $checkout->save();
            $fleet = Fleet::where("id",$request->id_fleet)->first();  
            if($request->place!="" && $request->place!=null){
                $fleet->yard_location=$request->place;
                $fleet->save();
            }
            $fleet->status="true";
            $fleet->current_yard_location=$request->place;
            $fleet->last_odometer=$request->odometer_reading;
            $fleet->save();
            
            $usersNotify=User::whereIn("id_rol",[3,1])->get();
            $userTmp=User::where('id',$request->id_employee)->first();
            $userTmp->yard_location=$request->place;
            $userTmp->save();

            $title_n = 'New check-in for vehicle';
            $msg_n = 'Employee '.$userTmp->name.' has check-in for vehicle with n '.$fleet->n;
            $path_n = 'checkin_fleet';
            $cod_sender = $request->id_employee;
            
            foreach ($usersNotify as $key => $user) {
                if(!$user->notify_checkout) continue;
                //start notification
                $cod_receiver = $user->id;
                $type_sender = 4;
                $type_receiver = $user->id_rol;
                $type_notification = 'message'; //redirect,message,modal_redirect,none
                $use_lang_title = 'false';
                $use_lang_msg = 'false';
                $paramsTitleNotifi = [];
                $paramsMsgNotifi = [];
                $sendMail = true;
                $icon = 'wilcoerp-logo.png'; //name-image.jpg or html code
                $type_icon = 'image-public'; //html-class, image-public
                SICAP::sendNotification($title_n, $msg_n, $path_n, $cod_sender, $cod_receiver, $type_sender, $type_receiver, $type_notification, $use_lang_title, $use_lang_msg, $paramsTitleNotifi, $paramsMsgNotifi, $sendMail, $icon, $type_icon, $user->email);
                //end notification
            }

            ActivityLog::create([
                'type' => 6,
                'title' => $title_n,
                'desc' => $msg_n,
                'href' => "/fleet_detail/".$checkin->id_fleet,
                'id_reference' => $checkin->id,
            ]);

            $employee= User::where("id",$request->id_employee)->first();
            return response()->json(["checkin"=> $checkin,"fleet"=>$fleet,"employee"=> $employee]);
       }else{
        return response()->json(["error"=>"fields are missing"],400);
       }
    }

    public function employeeToolInsert(Request $request)
    {
       if(
        !empty($request->id_check_out) &&
        !empty($request->id_employee) &&
        !empty($request->id_tool)
        ){

            $checkin= new CheckIn();
            $checkin->type="tool";
            $checkin->id_employee=$request->id_employee;
            $checkin->id_tool=$request->id_tool;
            $checkin->id_check_out=$request->id_check_out;
            $checkin->status="in_process";
            $checkin->save();
            $checkout = CheckOut::where("id",$request->id_check_out)->first();     
            $checkout->status="finalized";
            $checkout->save();
            $tool= Tool::where("id",$request->id_tool)->first();     
            $tool->stock = intval($tool->stock) +  intval($checkout->quantity);
            $tool->save();
            $usersNotify=User::whereIn("id_rol",[2,1])->get();
            $userTmp=User::where('id',$request->id_employee)->first();

            $title_n = 'New check-in for tool';
            $msg_n = 'Employee '.$userTmp->name.' has check-in for tool with n '.$tool->n;
            $path_n = 'checkin_tool';
            $cod_sender = $request->id_employee;
            
            foreach ($usersNotify as $key => $user) {
                if(!$user->notify_checkout) continue;
                //start notification
                $cod_receiver = $user->id;
                $type_sender = 4;
                $type_receiver = $user->id_rol;
                $type_notification = 'message'; //redirect,message,modal_redirect,none
                $use_lang_title = 'false';
                $use_lang_msg = 'false';
                $paramsTitleNotifi = [];
                $paramsMsgNotifi = [];
                $sendMail = true;
                $icon = 'wilcoerp-logo.png'; //name-image.jpg or html code
                $type_icon = 'image-public'; //html-class, image-public
                SICAP::sendNotification($title_n, $msg_n, $path_n, $cod_sender, $cod_receiver, $type_sender, $type_receiver, $type_notification, $use_lang_title, $use_lang_msg, $paramsTitleNotifi, $paramsMsgNotifi, $sendMail, $icon, $type_icon, $user->email);
                //end notification
            }
            $employee= User::where("id",$request->id_employee)->first();

            ActivityLog::create([
                'type' => 6,
                'title' => $title_n,
                'desc' => $msg_n,
                'href' => "#",
                'id_reference' => $checkin->id,
            ]);

            return response()->json(["checkin"=> $checkin,"tool"=>$tool,"employee"=> $employee]);
       }else{
        return response()->json(["error"=>"fields are missing"],400);
       }
    }
}