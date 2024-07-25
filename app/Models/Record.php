<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
class Record extends Model
{
    protected $table='record';
    protected $guarded= ['id'];
    public function getDataTable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'mechanic_name',
            3 => 'description',
            4 => 'tool_name',
            5 => 'hour_spend',
            6 => 'files',
            7 => 'cost'
        );
        $query = Record::query();
        if($request->has('id')) $query = $query->where('id_service', $request->id);
        if($request->has('uid')) $query = $query->where('id_mechanic', $request->uid);
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
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query = $query->filter(function ($element) use ($search, $columns, $request) {
                return $this->filterSearch($element, $search, $columns, $request);
            });
            $totalFiltered = $query->count();
        }
        $query = $query->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir);
        if ($limit > -1) {
            $query = $query->skip($start)->take($limit);
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
        $service=Service::where('id',$element->id_service)->first();
        $mechanic=User::where('id',$element->id_mechanic)->first();
        $files=Asset::where('id_reference',$element->id)->where("type", "record")->get();
        $element['date']=Carbon::createFromFormat('Y-m-d H:i:s', $element->created_at)->format('m/d/Y');
        $element['service_desc'] = $service->description;
        $element['mechanic_name'] = $mechanic->name." ".$mechanic->last_name;
        $element['tool_id'] = "";
        $element['tool_name'] = "";
        $element['tool_price'] = "";
        $element['tool_quantity'] = "";
        $element['total_cost'] = $element['cost'];
        $record_tools=RecordTool::where('id_record', $element->id)->get();
        foreach($record_tools as $record_tool){
            $tool = Tool::find($record_tool->id_tool);
            if($tool){
                $element['tool_id'] .= ($element['tool_id']==''?'':',') . $tool->id;
                $element['tool_name'] .= ($element['tool_name']==''?'':', ') . "N° $tool->n $tool->title ($record_tool->amount)";
                $element['tool_price'] .= ($element['tool_price']==''?'':',') . $tool->price;
                $element['tool_quantity'] .= ($element['tool_quantity']==''?'':',') . $record_tool->amount;
                $element['total_cost'] += $record_tool->amount*$tool->price;
            }
        }
        $category=RecordCategory::find($element->id_category);
        $element['category_name'] = $category?$category->name:'';
        $element['files'] = $files;
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
