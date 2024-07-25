<?php

namespace App\Http\Controllers;
use App\Models\FleetCustomField;
use App\Models\Rol;
use App\Models\Page;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Rol::get();
        return view('settings', compact('roles'));
    }
    public function fleetCustomDataTable(Request $request)
    {
        $fleet = new FleetCustomField();
        $results = $fleet->getDataTable($request);
        return response()->json($results);
    }
    public function fleetCustomSave(Request $request){
        $id = $request->input('id');
        $row = ($id==-1)?new FleetCustomField : FleetCustomField::find($id);
        $row->title = $request->input('title');
        $name = strtolower(str_replace(" ","_",$row->title));
        $row->name = $name;
        $row->type = $request->input('type');
        $row->status = $request->input('status');
        $row->updated_at = now();
        if($id==-1) $row->created_at = now();
        $row->save();
        return redirect('settings')
            ->with('success', 'Fleet Custom property has saved successfully.')
            ->with('tab', 'fleet_custom');
    }
    public function fleetCustomDelete(Request $request){
        $errors = 0;
        $cantSuccsess = 0;
        $ids = $request['id'];
        foreach ($ids as $key => $id) {
            FleetCustomField::where('id', $id)->delete();
        }
        return redirect('settings')
            ->with('success', 'Fleet Custom property has successfully removed.')
            ->with('tab', 'fleet_custom');
    }

    public function permissionDataTable(Request $request)
    {
        $id_rol = $request->id_rol;
        $columns = array(
            0 => 'name',
            1 => 'module',
            2 => 'read',
            3 => 'write',
            4 => 'create',
            5 => 'delete',
        );
        $query = Page::leftJoin('permission', function($join) use ($id_rol) {
            $join->on('permission.id_page', '=', 'page.id')
                ->where('permission.id_rol', '=', $id_rol)
                ->orWhereNull('permission.id_rol');
            })
            ->get([
                'page.id',
                'page.name',
                'page.module',
                'permission.read',
                'permission.write',
                'permission.create',
                'permission.delete',
                'permission.id_rol'
            ]);
        $totalData = $query->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'id';
        $dir = 'asc';
        if($request->has('order.0.column')){
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
        }
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query = $query->filter(function ($element) use ($search, $columns, $request) {
                $item = false;
                //general
                foreach ($columns as $colum)
                    if (stristr($element[$colum], $search))
                        $item = $element;
                return $item;
            });
            $totalFiltered = $query->count();
        }
        $query = $query->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir);
        if ($limit != -1) {
            $query = $query->skip($start)->take($limit);
        }
        $elements = $query->values()->all();
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $elements
        ];
        return response()->json($result);
    }

    public function permissionSave(Request $request){
        $id_rol = $request->id_rol;
        $permissions = json_decode($request->permissions, true);
        $permission_rows = Permission::get();
        foreach($permission_rows as $row){
            $exist = false;
            foreach($permissions as $permission){
                if($row->id_page==$permission[0]){
                    $exist = true;
                    break;
                }
            }
            if(!$exist) $row->delete();
        }
        foreach($permissions as $permission){
            if(count($permission)<5) continue;
            $id_page = $permission[0];
            $page = Page::find($id_page);
            if(!$page) continue;
            $row = Permission::where('id_rol', $id_rol)->where('id_page', $id_page)->first();
            if(!$row){
                $row = new Permission();
                $row->id_rol = $id_rol;
                $row->id_page = $id_page;
                $row->created_by = Auth::user()->id;
            }
            $row->read = $permission[1];
            $row->write = $permission[2];
            $row->create = $permission[3];
            $row->delete = $permission[4];
            $row->updated_by = Auth::user()->id;
            $row->save();
        }
        return redirect('settings')
            ->with('success', 'Permission has saved successfully.')
            ->with('tab', 'permission');
    }
}
