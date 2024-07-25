<?php

namespace App\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
class ReportProblem extends Model
{
    protected $table='report_problem';
    protected $guarded= ['id'];


    public function getDataTable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'type',
            2 => 'fleet_name',
            3 => 'category_name',
            4 => 'place',
            5 => 'description',
            6 => 'status',
            7 => 'created_at'
        );
        $query = ReportProblem::leftJoin('fleet', 'fleet.id', '=', 'report_problem.id_fleet')
                        ->leftJoin('request_category', 'request_category.id', '=', 'report_problem.id_request_category')
                        ->get([
                            'report_problem.id',
                            'report_problem.type',
                            DB::raw('CONCAT("N°: ",fleet.n," ",fleet.model) as fleet_name'),
                            DB::raw('request_category.title as category_name'),
                            'report_problem.place',
                            'report_problem.description',
                            'report_problem.status',
                            'report_problem.created_at',
                            'report_problem.id_employee'
                        ]);
        if($request->has('uid')) $query = $query->where('id_employee', $request->uid);
        $totalData = $query->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query = $query->filter(function ($element) use ($search, $columns, $request) {
                return $this->filterSearch($element, $search, $columns, $request);
            });
            $totalFiltered = $query->count();
        }
        $query = $query->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir);
        if ($limit != -1) {
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
