<?php

namespace App\Helpers;

use App\Models\Module;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\Rol;
use App\Models\Permission;

use App\Models\User;
use DateTime;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SICAP
{
    public static function getMenuEnable($route,$param=null)
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("la ruta");
        $out->writeln($route . '/'. $param);
        
       try {
        if($param!=null){
            if (request()->is($route . '/'. $param) ) {
                return true;
            } else {
                return false;
            }
        }else{
            if (request()->is($route) || request()->is($route . '/*') || request()->route()->getName()==$route ) {
                return true;
            } else {
                return false;
            }
        }

       } catch (\Throwable $th) {
       return false;
       }
    }

    public static function getBreadCrumbs($breadcrumbsList)
    {
        $html = "";
        $htmlInner = "";
        for ($i = 0; $i < count($breadcrumbsList); $i++) {
            if ($i == 0)
                $html .= " <h3 class='kt-subheader__title'>" . $breadcrumbsList[$i]['name'] . "</h3>";
            if ($i == 1)
                $html .= " <span class='kt-subheader__separator kt-hidden'></span>";
            if ($i >= 1)
                $htmlInner .= ' <span class="kt-subheader__breadcrumbs-separator"></span><a href="' . $breadcrumbsList[$i]['route'] . '" class="kt-subheader__breadcrumbs-link"> ' . $breadcrumbsList[$i]['name'] . ' </a>';
        }

        if ($htmlInner != "") {
            $html .= '<div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>';
            $html .= $htmlInner;
            $html .= "</div>";
        }
        return $html;
    }

    public static function getRolPermissionStatus(Request $request = null, $route = false, $moduleTitle = false)
    {
        $flagRoute = false;
        $flagTitle = false;
        $employeeModules = User::join('rol', 'user.id_rol', '=', 'rol.id')
            ->join('rol_module', 'rol.id', '=', 'rol_module.id_rol')
            ->join('module', 'module.id', '=', 'rol_module.id_module')
            ->where('user.id', auth()->user()->id)->orderBy('user.id')
            ->orderBy('user.id')
            ->get(['module.id', 'module.title', 'module.url']);

        $modules = Module::get();
        /*
        foreach($employeeModules as $employeeModule){
            echo $employeeModule->url." <br>";
        }
        */
        foreach ($employeeModules as $employeeModule) {
            $flagRoute = true;
            if ($route && $route != "") {
                if ($route == $employeeModule->url)
                    $flagRoute = true;
            } else {
                //if(Route::has($employeeModule->url)){
                if ($request->route()->getName() == $employeeModule->url)
                    $flagRoute = true;

                $flagExistInListModule = false;
                foreach ($modules as $module) {
                    if ($module->url == $request->route()->getName())
                        $flagExistInListModule = true;
                }
                if (!$flagExistInListModule && !$flagRoute) {
                    $flagRoute = true;
                }
                //}
            }
            if ($moduleTitle && $employeeModule->title == $moduleTitle) $flagTitle = true;
        }
        if (request()->is('restricted_permission') && !$route)
            return true;
        if ($flagRoute || $flagTitle)
            return true;
        return true;
    }

    public static function checkRol($rolName){
        $role = Rol::where('id', auth()->user()->id_rol )->get('name')->first();
        if($role->name==$rolName)return true;
        return false;
    }

    public static function checkPermission($id_page, $mode=0){
        $modes = ['read', 'write', 'create', 'delete'];
        $permissions = auth()->user()->permissions;
        foreach($permissions as $permission){
            if($permission['id']==$id_page){
                return $permission[$modes[$mode]]==1;
            }
        }
        return false;
    }

    public static function getFormatMoney($cant)
    {
        return number_format((float) $cant, 2, '.', ',') . ' €';
    }

    public static function getFormatMoneyForDomPDF($cant)
    {
        return number_format((float) $cant, 2, '.', ',') . ' &#8364';
    }

    public static function getFormatPercent($percent)
    {
        return number_format((float) $percent, 2, '.', ',') . ' %';
    }

    public static function getNowDateFormatType1($useUnderscore = false)
    {

        $dayName = '';
        switch (date('w')) {
            case '1':
                $dayName = 'Lunes';
                break;
            case '2':
                $dayName = 'Martes';
                break;
            case '3':
                $dayName = 'Miércoles';
                break;
            case '4':
                $dayName = 'Jueves';
                break;
            case '5':
                $dayName = 'Viernes';
                break;
            case '6':
                $dayName = 'Sábado';
                break;
            case '0':
                $dayName = 'Domingo';
                break;
        }

        $dayName = $dayName . ", " . date('d') . " de " . date('F') . ' del ' . date('Y');

        return $dayName;
    }

    public static function getNowDayName()
    {

        $dayName = '';
        switch (date('w')) {
            case '1':
                $dayName = 'Lunes';
                break;
            case '2':
                $dayName = 'Martes';
                break;
            case '3':
                $dayName = 'Miércoles';
                break;
            case '4':
                $dayName = 'Jueves';
                break;
            case '5':
                $dayName = 'Viernes';
                break;
            case '6':
                $dayName = 'Sábado';
                break;
            case '0':
                $dayName = 'Domingo';
                break;
        }

        return $dayName;
    }

    public static function getStatusBackup($statusCode)
    {
        $status = '';
        switch ($statusCode) {
            case '0':
                $status = 'Creando...';
                $status = "<span class='status-gray p-1'>$status</span>";
                break;
            case '1':
                $status = 'Procesando...';
                $status = "<span class='status-blue p-1'>$status</span>";
                break;
            case '2':
                $status = 'Excepción: copia local-si, dropbox-no';
                $status = "<span class='status-yellow p-1'>$status</span>";
                break;
            case '3':
                $status = 'Creada con éxito';
                $status = "<span class='status-green p-1'>$status</span>";
                break;
            case '4':
                $status = 'Error';
                $status = "<span class='status-red p-1'>$status</span>";
                break;
        }



        return $status;
    }

    public static function getNameRolById($id_rol)
    {
        $role = Rol::where('id', $id_rol)->get('name')->first();
        return $role->name;
    }

    public static function getNotificationsUser($id_user,$id_rol){
       return Notification::
        where('cod_receiver',$id_user)
        ->where('type_receiver',$id_rol)
        ->orderBy('id', 'desc')
        ->offset(0)
        ->limit(25)
       ->get(['*']);

    }

    public static function getNotificationsNoRead($id_employee,$id_rol){
        return Notification::
         where('cod_receiver',$id_employee)
         ->where('type_receiver',$id_rol)
         ->where('status','no_read')->count();
    }

    public static function getAdmins(){
        $rol = Rol::where('name','administrador' )->get(['id']);
        $rol = $rol[0]->id ?? null;
        $admins=User::where("id_rol","=", $rol)->get(["*"]);
        return $admins;
    }

    public static function sendNotification(
        $title_n = '',
        $msg_n = '',
        $path_n = '',
        $cod_sender = '',
        $cod_receiver = '',
        $type_sender = '',
        $type_receiver = '',
        $type_notification = '',
        $use_lang_title = 'false',
        $use_lang_msg = 'false',
        $paramsTitleNotifi = array(),
        $paramsMsgNotifi = array(),
        $sendMail = false,
        $icon = '', //name-image.jpg or html code
        $type_icon = '', //html-class, image-public
        $email = ''
    ){
        $dateCreate = date('Y-m-d H:i:s');
        //$urlApiNotifications = config('app.api_notifications_socket')."/api"."/".config('app.mode_notifications_socket');
        $notification=Notification::create([
            'title' => $title_n,
            'message' => $msg_n,
            'status' => 'no_read',
            'path' => $path_n,
            'params_title' => json_encode($paramsTitleNotifi, JSON_UNESCAPED_SLASHES),
            'params_message' => json_encode($paramsMsgNotifi, JSON_UNESCAPED_SLASHES),
            'cod_sender' => (empty($cod_sender))?null:$cod_sender,
            'cod_receiver' => (empty($cod_receiver))?null:$cod_receiver,
            'type_sender' => (empty($type_sender))?null:$type_sender,
            'type_receiver' => (empty($type_receiver))?null:$type_receiver,
            'type_notification' => $type_notification,
            'date' => $dateCreate,
            'icon' => $icon,
            'type_icon' => $type_icon
        ]);

        if($sendMail && $email!="" && $email!=null){
            $data = [
                'title' => $title_n,
                'body' =>  $msg_n/* 'Hello  '.'your access pin is <strong>'.$user->pin."</strong>"*/,
                'asunto' =>  $title_n
            ];
            try {
                Mail::to($email)->send(new \App\Mail\Simple($data));
                return response()->json([ 'send' => true ], 200);
            } catch (\Throwable $th) {
                return response()->json([ 'errors' =>  $th ], 400);
            }
        }
       /* if ($notification){
            $notification=$notification->fresh();
            $post = array(
                'title_n' => $title_n,
                'msg_n' => $msg_n,
                'status' => 'no_read',
                'path_n' => $path_n,
                'cod_sender' => $cod_sender,
                'cod_receiver' => $cod_receiver,
                'type_sender' => $type_sender,
                'type_receiver' => $type_receiver,
                'type_notification' => $type_notification,
                'sex_sender' => 'male',
                'date_n_formated' => $dateCreate,
                'cod_n' => $notification->id,
                'icon' => $icon,
                'type_icon' => $type_icon,
            );

            $post = json_encode($post);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_URL, $urlApiNotifications);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($post)
                )
            );
            $data = curl_exec($ch);
            curl_close($ch);
            return true;
        } else {
            return false;
        }*/
        return true;
    }

    public static function createActivityLog(
        $type = '',
        $title = '',
        $desc = '',
        $link = '',
        $id_reference = 0
    ){
        ActivityLog::create([
            'type' => $type,
            'title' => $title,
            'desc' => $desc,
            'href' => $link,
            'id_reference' => $id_reference,
        ]);
    }

    public static function formatDate($d)
    {
        if ($d == null || $d == "") {
            return null;
        }
        
        // Split the date and time parts
        $dateTimeParts = explode(' ', $d);
        $datePart = $dateTimeParts[0];
        
        // Split the date using hyphens
        $dateParts = explode('-', $datePart);
        
        // Rearrange the date parts and format them as "mm/dd/yyyy"
        $formattedDate = $dateParts[1] . '/' . $dateParts[2] . '/' . $dateParts[0];
        
        // Append the time part back if it exists
        if (count($dateTimeParts) > 1) {
            $formattedDate .= ' ' . $dateTimeParts[1];
        }
        
        // Return the formatted date
        return $formattedDate;
    }

    public static function decodeDate($d){
        if($d==null || $d=="") return null;
        $dateParts = explode('/', $d);
        if(count($dateParts)<3) return null;
        // Rearrange the date parts and format them as "yyyy-mm-dd"
        $formattedDate = $dateParts[2] . '-' . $dateParts[0] . '-' . $dateParts[1];
        // Return the formatted date
        return $formattedDate;
    }
}
