<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
//use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Reminder extends Model
{
    protected $table='reminder';
    protected $guarded= [];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_service', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(ReminderNotifyUser::class, 'id', 'id_reminder');
    }

    public function getDataTable(Request $request)
    {
        $fleet_id = $request->fleet_id;
        $columns = array(
            0 => 'id',
            1 => 'task',
            2 => 'time_interval',
            3 => 'meter_interval',
            4 => 'created_at',
        );
        $totalData = $fleet_id?Reminder::where('id_fleet', $fleet_id)->count():Reminder::select()->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];

        $query = Reminder::get(['*'])
            ->map(function ($element) {
                return $this->mapDataTable($element);
            });
        if($fleet_id) $query = $query->where('id_fleet', $fleet_id);
        if(!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query = $query
                ->filter(function ($element) use ($search, $columns, $request) {
                    return $this->filterSearch($element, $search, $columns, $request);
                });
        }
        $totalFiltered = $query->count();
        $query = $query->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir);
        if ($limit != -1) {
            $query = $query
                ->skip($start)
                ->take($limit);
        }
        $elements = $query->values()->all();
        for($i=0;$i<count($elements);$i++){
            //$elements[$i]->users_count = count($elements[$i]->users);
            $elements[$i]->users = ReminderNotifyUser::where('id_reminder', $elements[$i]->id)->get();
            for($j=0;$j<count($elements[$i]->users);$j++){
                if($elements[$i]->users[$j]->id_role){
                    $role = Rol::find($elements[$i]->users[$j]->id_role);
                    $elements[$i]->users[$j]->name = 'All '.str_replace("_"," ",$role->name);
                    $elements[$i]->users[$j]->email = 'All '.str_replace("_"," ",$role->name);
                }else{
                    $user = User::find($elements[$i]->users[$j]->id_user);
                    $elements[$i]->users[$j]->name = $user->name." ".$user->last_name;
                    $elements[$i]->users[$j]->email = $user->email;
                }
            }
        }
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $elements
        ];
        return $result;
    }

    function filterSearch($element, $search, $columns, $request)
    {
        $item = false;
        foreach ($columns as $colum)
            if (stristr($element[$colum], $search))
                $item = $element;
        return $item;
    }

    function mapDataTable($element)
    {
        $element['actions']=json_decode($element);
        return $element;
    }
}
