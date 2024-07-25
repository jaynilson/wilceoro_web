<?php

namespace App\Http\Middleware;

use App\Helpers\SICAP;
use App\Models\Rol;
use Closure;

class PermissionAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role = Rol::where('id', auth()->user()->id_rol )->get('name')->first();
        if($role->name=="super_admin")
        return $next($request);

        if($request->ajax()){
        header('Content-Type: application/json; charset=utf-8');
        header('HTTP/1.1 500 Usted no tiene este permiso, contacte con el administrador.');
        die(json_encode(array('message' => 'ERROR', 'code' => 403),JSON_UNESCAPED_UNICODE));
        }
       
        //return response()->json(['response' => 'Usted no tiene permiso para realizar esta acción']);

        return redirect('restricted_permission');
    }
}
