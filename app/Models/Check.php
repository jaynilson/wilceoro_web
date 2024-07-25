<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Check extends Model
{
    protected $table='check';
    protected $guarded= [];

    public function getDataTable(Request $request)
    {
        $type=$request->type;
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'type',
            3 => 'critical',
            4 => 'created_at',
        );
        $totalData = Check::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];
        if (empty($request->input('search.value'))) {
            if ($limit == -1) {
                $elements = Check::get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $elements = Check::get(['*'])
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
                $elements =  Check::get(['*'])
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })
                    ->filter(function ($element) use ($search, $columns, $request) {
                        return $this->filterSearch($element, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $elements =  Check::get(['*'])
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
            $totalFiltered = Check::get(['*'])
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
