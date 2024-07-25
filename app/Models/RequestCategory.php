<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class RequestCategory extends Model
{

    protected $table='request_category';
    protected $guarded= ['id'];

    public function getDataTable(Request $request)
    {




        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'type',
            3 => 'status'
        );

        $totalData = RequestCategory::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $elements = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $elements = RequestCategory::get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $elements = RequestCategory::get(['*'])
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
                $elements =  RequestCategory::get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })
                    ->filter(function ($element) use ($search, $columns, $request) {
                        return $this->filterSearch($element, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $elements =  RequestCategory::get(['*'])
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

            $totalFiltered = RequestCategory::get(['*'])
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
        if (empty($request->search)) {

                $elements = Fleet::get(['*'])
                ->where('type', $type)
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })
                    ->sortBy("id", SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($skip)->take($limit)
                    ->values()->all();
            
        } else {
            $search = $request->search;


                $elements =  Fleet::get(['*'])
                ->where('type', $type)
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

        $result = [
            'fleets' =>  $elements
        ];

        return $result;
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

}
