<?php

namespace App\Http\Controllers;

use App\Helpers\SICAP;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Damage;
use App\Models\Fleet;
use App\Models\Request as ModelsRequest;
use App\Models\Tool;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageController extends Controller
{
    public function damageInsert(Request $request)
    {
       if(
        !empty($request->id_employee) &&
        !empty($request->type) &&
        !empty($request->id_checkout) &&
        !empty($request->explanation)
        ){
            $damage= new Damage();
            if($request->type=="fleet"){
                $damage->id_fleet=$request->id_fleet;
            }else if($request->type=="tool"){
                $damage->id_tool=$request->id_tool;
            }
            $damage->type=$request->type;
            $damage->id_employee=$request->id_employee;
            $damage->id_checkout=$request->id_checkout;
            $damage->explanation= $request->explanation;
            $damage->status="in_process";
            $damage->save();

            $usersNotify=User::whereIn("id_rol",[2,1])->get();
            $userTmp=User::where('id',$request->id_employee)->first();
            $toolTmp=Tool::where('id',$request->id_tool)->first();

            $title_n = 'Damaged tool';
            $msg_n = 'Employee '.$userTmp->name.' has marked that the tool with n '.$toolTmp->n.' has been damaged ';
            $path_n = 'damaged_tool';
            $cod_sender = $request->id_employee;
            
            foreach ($usersNotify as $key => $user) {
                if(!$user->notify_damage) continue;             
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
                'type' => 12,
                'title' => $title_n,
                'desc' => $msg_n,
                'href' => "#",
                'id_reference' => $damage->id,
            ]);

            return response()->json(["damage"=> $damage]);
       }else{
        return response()->json(["error"=>"fields are missing"],400);
       }
        

    }

    
}
