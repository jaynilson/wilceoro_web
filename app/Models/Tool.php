<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class Tool extends Model
{
    protected $table='tool';
    protected $guarded= [];

    public function getDataTable(Request $request, $department="", $type="")
    {
        $columns = array(
            0 => 'id',
            1 => 'n',
            2 => 'title',
            3 => 'stock',
            4 => 'type',
            5 => 'status',
        );
        $query = Tool::query();
        if ($department != '') {
            if ($department == 'fleet_assign') {
                $query = $query->where(function ($query) {
                    $query->where('department', 'fleet')
                        ->orWhere('department', 'general');
                });
            } else {
                $query = $query->where('department', $department);
            }
        }
        
        if($type!=''){
            $query = $query->where('type', $type);
        }

        if($request->has('out_stock_type')){
            $rows = $query->where('status', 'true')->get();
            $ids = [];
            foreach($rows as $row){
                if(
                    $request->input('out_stock_type')=='true'&&$row->available_stock<=0 ||
                    $request->input('out_stock_type')=='false'&&$row->available_stock>0
                )
                    $ids[] = $row->id;
            }
            $query = $query->whereIn('id', $ids);
        }
        $totalData = $query->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        
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

    public function getList(Request $request)
    {
        $type=$request->type;
        $columns = array(
            0 => 'id',
            1 => 'n',
            2 => 'title',
            3 => 'stock',
            4 => 'status',
            5 => 'type'

        );
        $limit = $request->limit;
        $skip = $request->skip;
        $dir =true;
        $elements = [];
        if (empty($request->search)) {
                $elements = Tool::get(['*'])
                ->where('type', $type)
                ->where('status', "true")
                ->map(function ($element) {
                        return $this->mapDataTable($element);
                    })
                    ->sortBy("id", SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($skip)->take($limit)
                    ->values()->all();
            
        } else {
            $search = $request->search;
                $elements =  Tool::get(['*'])
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

        $result = [
            'tools' =>  $elements
        ];
        return $result;
    }

    function mapDataTable($element)
    {
        $id = $element['id'];
        $available_stock = $element['stock'];
        // $records = Record::where('id_tool', $id)->get();
        // foreach ($records as $record) {
        //     $service = Service::find($record->id_service);
        //     if ($service && $service->status === 'In progress'){
        //         $available_stock --;
        //     }
        // }

        $records = RecordTool::where('id_tool', $id)->sum('amount');
        $available_stock -= $records;

        //$fleet_tools_count = FleetTool::where('id_tool', $id)->count();        
        $fleet_tools_count = FleetTool::whereRaw("FIND_IN_SET('$id', id_tool)")->count();
        $available_stock = $available_stock - $fleet_tools_count;
        $element['available_stock'] = $available_stock;
        $element['tool_name']='N° '.$element['n'].' '.$element['title'];
        $element['actions']=json_decode($element);
        return $element;
    }

    public function getAvailableStockAttribute()
    {
        $id = $this->id;
        $availableStock = $this->stock;

        // $records = Record::where('id_tool', $id)->get();
        // foreach ($records as $record) {
        //     $service = Service::find($record->id_service);

        //     if ($service && $service->status === 'In progress') {
        //         $availableStock--;
        //     }
        // }

        $records = RecordTool::where('id_tool', $id)->sum('amount');
        $availableStock -= $records;

        // $fleet_tools_count = FleetTool::where('id_tool', $id)->count();
        $fleet_tools_count = FleetTool::whereRaw("FIND_IN_SET('$id', id_tool)")->count();
        $availableStock -= $fleet_tools_count;

        return $availableStock;
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
