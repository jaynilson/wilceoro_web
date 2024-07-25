<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\UserResetPasswordNotification;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $guarded = ['id'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'remember_token'
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }


    public function roles()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id');
    }

    public function getFleetAttribute()
    {
        $id = $this->id;
        $last_checkout = CheckOut::where('id_employee', $id)->where('status','in_process')->first();
        return $last_checkout?Fleet::findOrFail($last_checkout->id_fleet):null;
    }

    public function getCheckedYardLocationAttribute()
    {
        $id = $this->id;
        $last_checkout = CheckOut::where('id_employee', $id)->where('status','in_process')->first();
        return $last_checkout?$last_checkout->place:"";
    }

    public function getOdometerAttribute()
    {
        $id = $this->id;
        $odometer = CheckOut::where('id_employee', $id)->sum('odometer_reading');
        $odometer += CheckIn::where('id_employee', $id)->sum('odometer_reading');
        return $odometer;
    }

    public function getPermissionsAttribute()
    {
        $id_rol = $this->id_rol;
        $pages = Page::get()->map(function ($element) use ($id_rol){
            $permission = Permission::where('id_rol', $id_rol)->where('id_page', $element['id'])->first();
            $element['read'] = $permission?$permission->read:0;
            $element['write'] = $permission?$permission->write:0;
            $element['create'] = $permission?$permission->create:0;
            $element['delete'] = $permission?$permission->delete:0;
            return $element;
        });
        return $pages;
    }

    public function getDataTable(Request $request)
    {
        $columns = array(
            0 => 'user.id',
            1 => 'full_name',
            2 => 'email',
            3 => 'tel',
            4 => 'status',
            5 => 'actions'
        );
        $totalData = User::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $users = [];
        $query = User::join('rol', 'user.id_rol', '=', 'rol.id');
        if(!empty($request->input('id_rol'))){
            $query = $query->where("user.id_rol", $request->input('id_rol'));
        }
        if(!empty($request->input('search.value'))){
            $search = $request->input('search.value');
            $query = $query->where('user.id', 'LIKE', "%{$search}%")
                            ->orWhere('user.name', 'LIKE', "%{$search}%")
                            ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                            ->orWhere('user.email', 'LIKE', "%{$search}%")
                            ->orWhere('user.tel', 'LIKE', "%{$search}%")
                            ->orWhere('user.status', 'LIKE', "%{$search}%")
                            ->orWhere('rol.name', 'LIKE', "%{$search}%");
        }
        if($limit > 0){
            $query = $query->offset($start)->limit($limit);
        }
        $totalFiltered = $query->count();
        $query = $query->orderBy('id_rol')->orderBy($order, $dir);
        $users = $query->get([
            'user.id',
            DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
            'user.department',
            'user.yard_location',
            'user.email',
            'user.tel',
            'user.status',
            'rol.name as rol_name',
            'user.pin',
            'user.last_name',
            'user.name',
            'user.id_rol',
            'picture'
        ])->map(function ($user) {
            $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
            $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
            $user['actions']=json_decode($user);
            return $user;
        });
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' =>  $totalFiltered,
            'aaData'               =>  $users
        ];
        return $result;
    }

    public function getDataTableEmployee(Request $request)
    {
        $columns = array(
            0 => 'user.id',
            1 => 'full_name',
            2 => 'email',
            3 => 'tel',
            4 => 'status',
            5 => 'actions'
        );
        $totalData = User::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $users = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $users = User::join('rol', 'user.id_rol', '=', 'rol.id')
                    ->where("user.id_rol",4)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
                        'user.email',
                       
                        'user.tel',
                        'user.status',
                        'rol.name as rol_name',
                        'user.pin',
                        'user.last_name',
                        'user.name',
                        'user.id_rol',
                        'picture'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        $user['actions']=json_decode($user);
                        return $user;
                    });
            } else {
                $users = User::join('rol', 'user.id_rol', '=', 'rol.id')
                ->where("user.id_rol",4)
                ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
                        'user.email',
                       
                        'user.tel',
                        'user.status',
                        'rol.name as rol_name',
                        'user.pin',
                        'user.last_name',
                        'user.name',
                        'user.id_rol',
                        'picture'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        $user['actions']=json_decode($user);
                        return $user;
                    });
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $users =  User::join('rol', 'user.id_rol', '=', 'rol.id')
                     ->where("user.id_rol",4)
                    ->orWhere('user.name', 'LIKE', "%{$search}%")
                    ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('user.email', 'LIKE', "%{$search}%")
                    ->orWhere('user.tel', 'LIKE', "%{$search}%")
                    ->orWhere('user.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
                        'user.email',
                       
                        'user.tel',
                       
                         'rol.name as rol_name',
                         'user.pin',
                        'user.status',
                        'user.last_name',
                        'user.name',
                        'user.id_rol',
                        'picture'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        $user['actions']=json_decode($user);
                        return $user;
                    });
            } else {

                $users =  User::join('rol', 'user.id_rol', '=', 'rol.id')
                   
                ->where("user.id_rol",4)
                    ->orWhere('user.name', 'LIKE', "%{$search}%")
                    ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('user.email', 'LIKE', "%{$search}%")
                    ->orWhere('user.tel', 'LIKE', "%{$search}%")
                    ->orWhere('user.status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
                        'user.email',
                        'rol.name as rol_name',
                        'user.tel',
                        'user.pin',
                        'user.status',
                        'user.last_name',
                        'user.name',
                        'user.id_rol',
                        'picture'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        $user['actions']=json_decode($user);
                        return $user;
                    });
            }
            $totalFiltered = User::join('rol', 'user.id_rol', '=', 'rol.id')
            ->where("user.id_rol",4)
                ->orWhere('user.name', 'LIKE', "%{$search}%")
                ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                ->orWhere('user.email', 'LIKE', "%{$search}%")
                ->orWhere('user.tel', 'LIKE', "%{$search}%")
                ->orWhere('user.status', 'LIKE', "%{$search}%")
                ->orWhere('rol.name', 'LIKE', "%{$search}%")
                ->count();
        }
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $users
        ];
        return $result;
    }

    public function getDataTableMechanic(Request $request)
    {
        $columns = array(
            0 => 'user.id',
            1 => 'full_name',
            2 => 'email',
            3 => 'tel',
            4 => 'status',
            5 => 'actions'
        );
        $totalData = User::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $users = [];
        if (empty($request->input('search.value'))) {
            if ($limit == -1) {
                $users = User::join('rol', 'user.id_rol', '=', 'rol.id')
                    ->where("user.id_rol",5)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
                        'user.email',
                        'user.tel',
                        'user.status',
                        'rol.name as rol_name',
                        'user.pin',
                        'user.last_name',
                        'user.name',
                        'user.id_rol',
                        'picture'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        $user['actions']=json_decode($user);
                        return $user;
                    });
            } else {
                $users = User::join('rol', 'user.id_rol', '=', 'rol.id')
                ->where("user.id_rol",5)
                ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
                        'user.email',
                        'user.tel',
                        'user.status',
                        'rol.name as rol_name',
                        'user.pin',
                        'user.last_name',
                        'user.name',
                        'user.id_rol',
                        'picture'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        $user['actions']=json_decode($user);
                        return $user;
                    });
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $users =  User::join('rol', 'user.id_rol', '=', 'rol.id')
                     ->where("user.id_rol",5)
                    ->orWhere('user.name', 'LIKE', "%{$search}%")
                    ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('user.email', 'LIKE', "%{$search}%")
                    ->orWhere('user.tel', 'LIKE', "%{$search}%")
                    ->orWhere('user.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
                        'user.email',
                        'user.tel',
                         'rol.name as rol_name',
                         'user.pin',
                        'user.status',
                        'user.last_name',
                        'user.name',
                        'user.id_rol',
                        'picture'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        $user['actions']=json_decode($user);
                        return $user;
                    });
            } else {
                $users =  User::join('rol', 'user.id_rol', '=', 'rol.id')
                ->where("user.id_rol",5)
                    ->orWhere('user.name', 'LIKE', "%{$search}%")
                    ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('user.email', 'LIKE', "%{$search}%")
                    ->orWhere('user.tel', 'LIKE', "%{$search}%")
                    ->orWhere('user.status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as full_name'),
                        'user.email',
                        'rol.name as rol_name',
                        'user.tel',
                        'user.pin',
                        'user.status',
                        'user.last_name',
                        'user.name',
                        'user.id_rol',
                        'picture'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        $user['actions']=json_decode($user);
                        return $user;
                    });
            }
            $totalFiltered = User::join('rol', 'user.id_rol', '=', 'rol.id')
            ->where("user.id_rol",5)
                ->orWhere('user.name', 'LIKE', "%{$search}%")
                ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                ->orWhere('user.email', 'LIKE', "%{$search}%")
                ->orWhere('user.tel', 'LIKE', "%{$search}%")
                ->orWhere('user.status', 'LIKE', "%{$search}%")
                ->orWhere('rol.name', 'LIKE', "%{$search}%")
                ->count();
        }
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $users
        ];
        return $result;
    }

    public function getAdministratorDataTable(Request $request)
    {
        $rol = Rol::where('name','administrador' )->get(['id']);
        $rol = $rol[0]->id ?? null;
        $columns = array(
            0 => 'user.id',
            1 => 'name',
            2 => 'email',
            3 => 'tel',
            4 => 'status',
            5 => 'actions'
        );
        $totalData = User::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $users = [];
        if (empty($request->input('search.value'))) {
            if ($limit == -1) {
                $users = User::join('rol', 'user.id_rol', '=', 'rol.id')
                    ->where('user.id_rol',  $rol)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as name'),
                        'user.email',
                        'user.tel',
                        'user.status',
                        'user.id as actions'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        return $user;
                    });
            } else {
                $users = User::join('rol', 'user.id_rol', '=', 'rol.id')
                ->where('user.id_rol',  $rol)
                ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as name'),
                        'user.email',
                        'user.tel',
                        'user.status',
                        'user.id as actions'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        return $user;
                    });
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $users =  User::join('rol', 'user.id_rol', '=', 'rol.id')
                    ->where('user.id_rol',  $rol)
                    ->where('user.id', 'LIKE', "%{$search}%")
                    ->orWhere('user.name', 'LIKE', "%{$search}%")
                    ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('user.email', 'LIKE', "%{$search}%")
                    ->orWhere('user.tel', 'LIKE', "%{$search}%")
                    ->orWhere('user.status', 'LIKE', "%{$search}%")
                    ->orWhere('rol.name', 'LIKE', "%{$search}%")
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as name'),
                        'user.email',
                        'user.tel',
                        'user.status',
                        'user.id as actions'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        return $user;
                    });
            } else {
                $users =  User::join('rol', 'user.id_rol', '=', 'rol.id')
                    ->where('user.id_rol',  $rol)
                    ->where('user.id', 'LIKE', "%{$search}%")
                    ->orWhere('user.name', 'LIKE', "%{$search}%")
                    ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                    ->orWhere('user.email', 'LIKE', "%{$search}%")
                    ->orWhere('user.tel', 'LIKE', "%{$search}%")
                    ->orWhere('user.status', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get([
                        'user.id',
                        DB::raw('CONCAT(user.name," ",user.last_name) as name'),
                        'user.email',
                        'user.tel',
                        'user.status',
                        'user.id as actions'
                    ])->map(function ($user) {
                        $user->status = $user->status == 'enable' ? 'Activo' : 'Inactivo';
                        $user->sex = $user->sex == 'male' ? 'Masculino' : 'Femenino';
                        return $user;
                    });
            }

            $totalFiltered = User::join('rol', 'user.id_rol', '=', 'rol.id')
                ->where('user.id', 'LIKE', "%{$search}%")
                ->where('user.id_rol',  $rol)
                ->orWhere('user.name', 'LIKE', "%{$search}%")
                ->orWhere('user.last_name', 'LIKE', "%{$search}%")
                ->orWhere('user.email', 'LIKE', "%{$search}%")
                ->orWhere('user.tel', 'LIKE', "%{$search}%")
                ->orWhere('user.status', 'LIKE', "%{$search}%")
                ->orWhere('rol.name', 'LIKE', "%{$search}%")
                ->count();
        }
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $users
        ];
        return $result;
    }

    function filterSearch($element, $search, $columns, $request)
    {
        $item = false;
            //general
            foreach ($columns as $colum)
                if (stristr($element[$colum], $search))
                    $item = $element;
            return $item;
    }
}
