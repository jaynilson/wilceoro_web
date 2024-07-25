<?php

namespace App\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table='report';
    protected $guarded= ['id'];

    public function getDataTable(Request $request)
    {
        $columns = array(
            0 => 'report.id',
            1 => 'report.type',
            2 => 'answer.type',
            3 => 'answer.question_text',
            4 => 'answer.position',
            5 => 'answer.content',
            6 => 'answer.created_at',
        );
        $query = Report::leftJoin('answer', 'report.id', '=', 'answer.id_report')
                        ->get(['report.id', 'report.type', 'report.id_employee', DB::raw('answer.type as answer_type'), 'answer.question_text', 'answer.position', 'answer.content', 'answer.created_at']);
        if($request->has('uid')) $query = $query->where('id_employee', $request->uid);

        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

        $elements = [];
        $query = $query->map(function ($element) {
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
        
        // $element['actions']=json_decode($element);
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
