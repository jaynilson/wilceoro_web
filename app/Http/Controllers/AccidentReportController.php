<?php

namespace App\Http\Controllers;

use App\Helpers\SICAP;
use App\Http\Requests\ValidationDeleteUser;
use App\Http\Requests\ValidationUser;
use App\Models\AccessToken;
use App\Models\Answer;
use App\Models\Report;
use App\Models\User;
use App\Models\Rol;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use PDF;

class AccidentReportController extends Controller
{
    public function accidentReportInsert(Request $request)
    {
        if(
            !empty($request->type) &&
            !empty($request->id_employee)
            ){
                $report = new Report();
                $report->type=$request->type;
                $report->id_employee=$request->id_employee;
                if($request->has('id_fleet')) $report->id_fleet = $request->id_fleet;
                $report->save();
                $report = Report::where("id",$report->id)->first();
                $usersNotify=User::whereIn("id_rol",[1,3])->get();
                $userTmp=User::where('id',$report->id_employee)->first();

                $title_n = 'New accident report';
                $msg_n = 'Employee '.$userTmp->name.' has sent a new accident report';
                $path_n = 'report_problem';
                $cod_sender = $report->id_employee;

                foreach ($usersNotify as $key => $user){
                    if(!$user->notify_accident) continue;
                    //start notification
                    $cod_receiver = $user->id;
                    $type_sender = 4;
                    $type_receiver = $user->id_rol;
                    $type_notification = 'none'; //redirect,message,modal_redirect,none
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
                    'type' => 11,
                    'title' => $title_n,
                    'desc' => $msg_n,
                    'href' => '#',
                    'id_reference' => $report->id,
                ]);

                return response()->json(["report"=> $report]);
           }else{
            return response()->json(["error"=>"fields are missing"],400);
        }            
    }

    public function accidentReportAnswerInsert(Request $request)
    {
        if(
            !empty($request->content) && 
            !empty($request->id_report)
            ){
                $answer= new Answer();
                $answer->type=$request->type;
                $answer->question_text=$request->question_text;
                $answer->position=$request->position;
                $answer->content=$request->content;
                $answer->id_report=$request->id_report;
                $answer->save();
                $answer= Answer::where("id",$answer->id)->first();

                ActivityLog::create([
                    'type' => 18,
                    'title' => 'New accident answer',
                    'desc' => 'A new answer has reported',
                    'href' => '#',
                    'id_reference' => $answer->id,
                ]);

                return response()->json(["answer"=> $answer]);

           }else{
            return response()->json(["error"=>"fields are missing"], 400);
           }
    }

    public function accidentReportLinked(Request $request)
    {
        if(
            !empty($request->id_report) && 
            !empty($request->id_report_linked)
            ){
                $report = Report::where("id",$request->id_report)->first();
                $report2= Report::where("id",$request->id_report_linked)->first();
                $report->report_linked_id=$request->id_report_linked;
                $report2->report_linked_id=$request->id_report;
                $report->save();
                $report2->save();
                return response()->json(["report"=> $report]);
           }else{
            return response()->json(["error"=>"fields are missing"],400);
           }
    }

    public function dataTable(Request $request){
        return response()->json((new Report())->getDataTable($request));
    }
}
