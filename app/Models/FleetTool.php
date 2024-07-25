<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
class FleetTool extends Model
{
    protected $table='fleet_tool';
    protected $guarded= ['id'];
    public function getDataTable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'id_tool',
            2 => 'assign_date',
            3 => 'return_date',
            4 => 'note',
        );
        $query = FleetTool::where("id_fleet", $request->id_fleet);
        $totalData = $query->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];
        $query = $query->get(['*'])
                        ->map(function ($element) {
                            return $this->mapDataTable($element);
                        });
        if(!empty($request->input('search.value'))){
            $search = $request->input('search.value');
            $query = $query->filter(function ($element) use ($search, $columns, $request) {
                return $this->filterSearch($element, $search, $columns, $request);
            });
            $totalFiltered = $query->count();
        }
        $query = $query->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir);
        if ($limit > 0){
            $query = $query->skip($start)->take($limit);
        }
        $elements = $query->values()->all();
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' =>  $totalFiltered,
            'aaData'               =>  $elements
        ];
        return $result;
    }

    function mapDataTable($element)
    {
        $ids = explode(',', $element->id_tool);
        $element['tool'] = Tool::whereIn('id', $ids)->get();
        $names = [];
        $required = 0;
        foreach($ids as $id){
            $tool=Tool::find($id);
            if($tool){
                array_push($names, str_replace(',', ' ', 'N° '.$tool->n.' '.$tool->title));
                if($tool->required_return) $required++;
            }else{
                $ids = array_filter($ids, function($value) use ($id){
                    return $value != $id;
                });
            }
        }
        $element['id_tool'] = implode(',', $ids);
        $element['tool_name'] = implode(', ', $names);
        $element['required_return'] = $required;
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
