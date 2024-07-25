<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
class ActivityLog extends Model
{
    protected $table='activity_log';
    protected $guarded= ['id'];
    public function getDataTable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'type',
            2 => 'title',
            3 => 'desc',
            4 => 'href',
            5 => 'created_at',
        );
        $totalData = ActivityLog::count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $elements = [];
        $query = ActivityLog::get(['*'])
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
        $element["_created_at"] = $element->created_at;
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

    function convertTimezone($timestamp)
    {
        $timezone = config('app.timezone'); // Get the timezone from your Laravel configuration
        
        $dateTime = new DateTime($timestamp, new DateTimeZone($timezone));
        $dateTime->setTimezone(new DateTimeZone(date_default_timezone_get())); // Convert to the server's timezone
        
        return $dateTime->format('Y-m-d H:i:s');
    }
}
