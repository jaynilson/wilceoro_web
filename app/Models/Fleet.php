<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Fleet extends Model
{
    protected $table='fleet';
    protected $guarded= [];
    public function getDataTable(Request $request)
    {
        $type=$request->type;
        $columns = array(
            0 => 'id',
            1 => 'n',
            2 => 'model',
            3 => 'licence_plate',
            4 => 'year',
            5 => 'yard_location',
            6 => 'department',
            7 => 'status',
        );
        $totalData = Fleet::count();
        if($type!=''){
            $totalData = Fleet::where('type', $type)->count();
        }
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];
        if($request->input('location')!=''){
            Fleet::whereNull('current_yard_location')->update(['current_yard_location' => DB::raw('yard_location')]);
        }
        $query = Fleet::get(['*'])
                ->sortBy('type', SORT_NATURAL | SORT_FLAG_CASE, false)
                ->map(function ($element) {
                    return $this->mapDataTable($element);
                });
        if($type!=''){
            $query = Fleet::get(['*'])
                ->where('type', $type)
                ->map(function ($element) {
                    return $this->mapDataTable($element);
                });
        }
        if($request->input('location')!=''){
            // $query = $query->where('yard_location', $request->input('location'));
            $location = $request->input('location');
            $query = $query->where('current_yard_location', $location);
            //$query = $query->whereRaw("(current_yard_location IS NOT NULL AND current_yard_location = '$location') OR (current_yard_location IS NULL AND yard_location = '$location')");
            //$query = $query->where(function ($query) {
                // $query->where(function ($query) use ($location) {
                //     $query->whereNotNull('current_yard_location')
                //         ->where('current_yard_location', $location);
                // })
                // ->orWhere(function ($query) use ($location) {
                //     $query->whereNull('current_yard_location')
                //         ->where('yard_location', $location);
                // });
            //});
        }
        if($request->input('department')!=''){
            $query = $query->where('department', $request->input('department'));
        }
        if($request->input('status')!=''){
            $query = $query->where('status', $request->input('status'));
        }
        if(!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query = $query
                ->filter(function ($element) use ($search, $columns, $request) {
                    return $this->filterSearch($element, $search, $columns, $request);
                });
        }
        $totalFiltered = $query->count();
        $totalData = $totalFiltered;
        $query = $query->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir);
        if ($limit != -1) {
            $query = $query
                ->skip($start)
                ->take($limit);
        }
        $elements = $query->values()->all();
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $elements
        ];
        return $result;
    }

    public function getDataTableAll(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'n',
            2 => 'model',
            3 => 'licence_plate',
            4 => 'year',
            5 => 'yard_location',
            6 => 'department',
            7 => 'status',

        );
        $totalData = Fleet::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];
        if (empty($request->input('search.value'))) {
            if ($limit == -1) {
                $elements = Fleet::get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $elements = Fleet::get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                ->skip($start)->take($limit)
                ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $elements =  Fleet::get(['*'])
                ->map(function ($element) {
                    return $this->mapDataTable($element);
                })
                ->filter(function ($element) use ($search, $columns, $request) {
                    return $this->filterSearch($element, $search, $columns, $request);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $elements =  Fleet::get(['*'])
                ->map(function ($element) {
                    return $this->mapDataTable($element);
                })
                ->filter(function ($element) use ($search, $columns, $request) {
                    return $this->filterSearch($element, $search, $columns, $request);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                ->skip($start)->take($limit)
                ->values()->all();
            }
            $totalFiltered = Fleet::get(['*'])
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

    public function getList(Request $request)
    {
        $type=$request->type;
        $columns = array(
            0 => 'id',
            1 => 'n',
            2 => 'model',
            3 => 'licence_plate',
            4 => 'year',
            5 => 'yard_location',
            6 => 'department',
            7 => 'status',
        );

        $limit = $request->limit;
        $skip = $request->skip;
        $dir =true;

        $elements = [];
        $location= $this->getLocation($request->location);
        if($request->location!=''){
            Fleet::whereNull('current_yard_location')->update(['current_yard_location' => DB::raw('yard_location')]);
        }
        if (empty($request->search)) {
            if( $location!="other"){
                $elements = Fleet::
                where('type', $type)
                ->where('status', "true")
                ->where('current_yard_location', strtolower($location) )
              //  ->orWhere('yard_location',  strtoupper($location))
                ->get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })
                    ->sortBy("id", SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($skip)->take($limit)
                    ->values()->all();
            }else{
                $elements = Fleet::get(['*'])
                ->where('type', $type)
                ->where('status', "true")
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })
                    ->sortBy("id", SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($skip)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->search;
            if( $location!="other"){
                $elements =  Fleet::
               where('type', $type)
                ->where('status', "true")
                ->where('yard_location', strtolower($location) )
               // ->orWhere('yard_location',  strtoupper($location))
                ->get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })
                    ->filter(function ($element) use ($search, $columns, $request) {
                        return $this->filterSearch($element, $search, $columns, $request);
                    })
                    ->sortBy("id", SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($skip)->take($limit)
                    ->values()->all();
            }else{
                $elements =  Fleet::get(['*'])
                ->where('type', $type)
                ->where('status', "true")
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })
                    ->filter(function ($element) use ($search, $columns, $request) {
                        return $this->filterSearch($element, $search, $columns, $request);
                    })
                    ->sortBy("id", SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($skip)->take($limit)
                    ->values()->all();
            }
        }

        $result = [
            'fleets' =>  $elements
        ];

        return $result;
    }

    function getLocation($location){
        $locations = $this->getLocations();
        $placeFound="other";
        foreach ($locations as $key => $value) {
            if(strtoupper($value) == strtoupper($location)){
                $placeFound=$value;
            }
        }
        return  $placeFound;
    }

    function mapDataTable($element)
    {
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

    function getDepartments(){
        $departmentsList = Fleet::distinct('department')->pluck('department');
        return $departmentsList;
    }

    function getStatuses(){
        $statusList = Fleet::distinct('status')->pluck('status');
        return $statusList;
    }

    function getLocations(){
        // $locations = Fleet::distinct()
        // ->select('yard_location')
        // ->union(Fleet::distinct()->select('current_yard_location'))
        // ->pluck('yard_location');
        $locations = Fleet::distinct('current_yard_location')->pluck('current_yard_location');
        return $locations;
    }
}
