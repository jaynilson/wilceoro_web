<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
class Service extends Model
{
    protected $table='service';
    protected $guarded= ['id'];
    protected $fillable = [
        'description', 'type', 'id_fleet', 'id_employee',
        'next_service_date', 'needed_date', 'completed_date',
        'next_service_miles',
        'engine_hours', 'working', 'notes',
        'cost', 'miles',
        'status',
        'created_at', 'updated_at'
    ];

    public function getDataTable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'created_at',
            2 => 'description',
            3 => 'type',
            4 => 'vehicle_name',
            5 => 'driver_name',
            6 => 'cost'
        );

        $totalData = Service::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];

        $query = Service::get(['*'])
            ->map(function ($element) {
                return $this->mapDataTable($element);
        });
        if($request->has('status')&&$request->input('status')!="__ALL__"){
            $status = urldecode($request->input('status'));
            $query = $query->where('status', $status);
        }
        if($request->has('status_type')){
            $status = urldecode($request->input('status_type'));
            if($status=='working')
                $query = $query->where('status', '<>', 'Completed');
        }
        if (!empty($request->input('search.value'))){
            $search = $request->input('search.value');
            $query = $query->filter(function ($element) use ($search, $columns, $request) {
                return $this->filterSearch($element, $search, $columns, $request);
            });
        }
        $totalFiltered = $query->count();
        $query = $query->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir);
        if ($limit > 0) $query = $query->skip($start)->take($limit);
        $elements = $query->values()->all();

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $elements
        ];
        return $result;
    }

    function mapDataTable($element)
    {
        $fleet=Fleet::where('id',$element->id_fleet)->first();
        $driver=User::where('id',$element->id_employee)->first();
        $element['full_fleet'] = $fleet;
        if($fleet){
            $element['vehicle_name']= $fleet->model;
        }else{
            $element['vehicle_name']="";
        }

        $element['driver_name'] = "";
        $element['driver_avatar']= null;
        if($driver){
            $element['driver_name']= $driver->name." ".$driver->last_name;
            $element['driver_avatar']= $driver->picture;
        }

        $cost=0;
        $hours=0;
        $records=Record::where("id_service",$element->id)->get();
        foreach ($records as $key => $value) {
            $cost = $cost + $value->cost;
            $hours = $hours + $value->hour_spend;
        }
        $element["cost"] = $cost;
        $element["hours"] = $hours;
        $element["records"] = $records;
        $element['actions']=json_decode($element);



        return $element;
    }

    public function getDataTableMaintenance(Request $request)
    {

        $id=$request->id;
        $columns = array(
            0 => 'date',
            1 => 'miles',
            2 => 'type',
            3 => 'next_service_date',
            4 => 'next_service_miles',
            5 => 'notes'

        );
        $totalData = Service::where('id_fleet',$id)->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $elements = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $elements = Service::
                where('id_fleet',$id)->
                get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTableMaintenance($element);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $elements = Service::
                where('id_fleet',$id)->
                get(['*'])
        
                ->map(function ($element) {
                        return $this->mapDataTableMaintenance($element);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $elements =  Service::
                where('id_fleet',$id)->
                get(['*'])
              
                ->map(function ($element) {
                        return $this->mapDataTableMaintenance($element);
                    })
                    ->filter(function ($element) use ($search, $columns, $request) {
                        return $this->filterSearch($element, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $elements =  Service::
                where('id_fleet',$id)->
                get(['*'])
               
                ->map(function ($element) {
                        return $this->mapDataTableMaintenance($element);
                    })
                    ->filter(function ($element) use ($search, $columns, $request) {
                        return $this->filterSearch($element, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = Service::
            where('id_fleet',$id)->
            get(['*'])
               
                ->filter(function ($element) use ($search, $columns, $request) {
                    return $this->filterSearch($element, $search, $columns, $request);
                })
                ->count();
        }

        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $elements
        ];

        return $result;
    }
    function mapDataTableMaintenance($element)
    {
        $element['date']=Carbon::createFromFormat('Y-m-d H:i:s', $element->created_at)->format('m/d/Y h:i:s');
        $element['actions']=json_decode($element);
        return $element;
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

    function getStatuses(){
        $statusList = Service::distinct('status')->pluck('status');
        return $statusList;
    }
}
