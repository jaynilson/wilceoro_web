<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FleetCustomField extends Model
{
    protected $table='fleet_custom_field';
    protected $guarded= [];
    public function getDataTable(Request $request)
    {
        $type=$request->type;
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'type',
            3 => 'created_at',
        );
        $totalData = FleetCustomField::select()->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];

        $query = FleetCustomField::get(['*'])->map(function ($element) {
            return $this->mapDataTable($element);
        });
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
        foreach ($columns as $colum)
            if (stristr($element[$colum], $search))
                $item = $element;
        return $item;
    }
}
