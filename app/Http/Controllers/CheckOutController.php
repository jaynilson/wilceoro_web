<?php

namespace App\Http\Controllers;

use App\Helpers\SICAP;
use App\Models\CheckOut;
use App\Models\Fleet;
use App\Models\Tool;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckOutController extends Controller
{
    public function employeeFleetInsert(Request $request)
    {
       if(
        !empty($request->place) &&
        !empty($request->problem_found) && 
        !empty($request->id_employee) &&
        !empty($request->id_fleet)
        ){
            $checkout = new CheckOut();
            $checkout->type="fleet";
            $checkout->place=$request->place;
            if(!empty($request->lat) && !empty($request->lng) ){
                $checkout->lat=$request->lat;
                $checkout->lng=$request->lng;
            }
            $checkout->odometer_reading=$request->odometer_reading;
            $checkout->problem_found=$request->problem_found;
            $checkout->id_employee=$request->id_employee;
            $checkout->id_fleet=$request->id_fleet;
            $checkout->status="in_process";
            if(!empty($request->manual_date)){
                $checkout->created_at = $request->manual_date;
                $checkout->is_manual = true;
            }
            $checkout->save();
           
            $fleet = Fleet::where("id",$request->id_fleet)->first();  
            if($request->place!="" && $request->place!=null){
                $fleet->yard_location=$request->place;
                $fleet->save();
            }
 
            $fleet->current_yard_location=$request->place;
            $fleet->last_odometer=$request->odometer_reading;
            $fleet->status="check-out";
            $fleet->save();

            $employee = User::where("id",$request->id_employee)->first();
            $usersNotify=User::whereIn("id_rol",[3,1])->get();
            $userTmp=User::where('id',$request->id_employee)->first();
            $userTmp->yard_location=$request->place;
            $userTmp->save();

            $title_n = 'New checkout for vehicle';
            $msg_n = 'Employee '.$userTmp->name.' has checkout for vehicle with n '.$fleet->n;
            $path_n = 'checkout_fleet';
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
            }

            ActivityLog::create([
                'type' => 5,
                'title' => $title_n,
                'desc' => $msg_n,
                'href' => "/fleet_detail/".$checkout->id_fleet,
                'id_reference' => $checkout->id,
            ]);

            return response()->json(["checkout"=> $checkout,"fleet"=>$fleet,"employee"=> $employee]);
       }else{
        return response()->json(["error"=>"fields are missing"],400);
       }
    }

    public function getCheckout(Request $request)
    {
        try {
            $checkout = CheckOut::where("id_employee",$request->user_id)->where("status","in_process")->where("type","fleet")->first();    
            if( $checkout){
                $fleet = Fleet::where("id",$checkout->id_fleet)->first();     
                $employee= User::where("id",$checkout->id_employee)->first();
                return response()->json(["checkout"=> $checkout,"fleet"=>$fleet,"employee"=> $employee]);
            }else{
                return response()->json(["checkout"=> null,"fleet"=>null,"employee"=> null]);
            }
    
        } catch (\Throwable $th) {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();    
            $out->writeln($th);
            return response()->json(["checkout"=> null,"fleet"=>null,"employee"=> null]);
        }
    }

    public function employeeToolInsert(Request $request)
    {
       if(
        !empty($request->quantity) && 
        !empty($request->return_date) && 
        !empty($request->id_employee) &&
        !empty($request->id_tool)
        ){
            $tool= Tool::where("id",$request->id_tool)->first();
            if($tool->stock > $tool->quantity){
                $checkout = new CheckOut();
                $checkout->type="tool";
         
                $checkout->id_employee=$request->id_employee;
                $checkout->id_tool=$request->id_tool;
                $checkout->quantity=$request->quantity;
                $checkout->return_date=$request->return_date;
                $checkout->status="in_process";
                $checkout->save();
               
                $tool= Tool::where("id",$request->id_tool)->first();
                $tool->stock= (intval($tool->stock) -  intval($checkout->quantity));
                $tool->save();
                
                $employee= User::where("id",$request->id_employee)->first();
                $usersNotify=User::whereIn("id_rol",[2,1])->get();
                $userTmp=User::where('id',$request->id_employee)->first();

                $title_n = 'New checkout for tool';
                $msg_n = 'Employee '.$userTmp->name.' has checkout for tool with n '.$tool->n;
                $path_n = 'checkout_tool';
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
                    'type' => 5,
                    'title' => $title_n,
                    'desc' => $msg_n,
                    'href' => "#",//TODO
                    'id_reference' => $checkout->id,
                ]);

                return response()->json(["checkout"=> $checkout,"tool"=>$tool,"employee"=> $employee]);
            }else{
                return response()->json(["error"=>"There is not enough quantity"],400);
            }
        }else{
            return response()->json(["error"=>"fields are missing"],400);
        }
    }

    public function getCheckoutTool(Request $request)
    {
        try {
            $checkouts= CheckOut::where("id_employee",$request->user_id)->where("status","in_process")->where("type","tool")->get();    
            if($checkouts){
                $checkoutsTmp=[];
                foreach ($checkouts as $key => $value) {
                    $tool= Tool::where("id",$value->id_tool)->first();  
                    array_push($checkoutsTmp, [
                        "checkout"=> $value,
                        "tool"=> $tool
                    ]);
                }
                $employee= User::where("id",$request->user_id)->first();
                return response()->json(["checkouts"=> $checkoutsTmp,"employee"=> $employee]);
            }else{
                return response()->json(["checkouts"=> null,"employee"=> null]);
            }
    
        } catch (\Throwable $th) {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();    
            $out->writeln($th);
            return response()->json(["checkout"=> null,"fleet"=>null,"employee"=> null]);
        }
    }
}
