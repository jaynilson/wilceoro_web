<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Rental extends Model
{
    protected $table='rental';
    protected $guarded= [];

    public function getDataTable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'n',
            2 => 'title',
            3 => 'stock',
            4 => 'type',
            5 => 'status',
        );
        $query = Rental::select('*');
        $totalData = $query->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $query = Rental::query();
        $query = $query->get(['*'])->map(function ($element) {
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
        if ($limit > 0) {
            $query = $query
                ->skip($start)
                ->take($limit);
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
        $tool = Tool::find($element->id_tool);
        $element['tool'] = $tool;
        $element['tool_name'] = $tool?'N° '.$tool->n.' '.$tool->title:'';
        $element['required_return'] = $tool?$tool->required_return:false;
        $employee = User::find($element->id_employee);
        $element['employee'] = $employee;
        $element['employee_name'] = $employee?$employee->name.' '.$employee->last_name:'';
        $files=Asset::where('id_reference',$element->id)->where("type","rental_returned")->get();
        $element['files'] = $files;
        $element['actions'] = json_decode($element);
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
