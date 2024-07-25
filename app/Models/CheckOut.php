<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Http\Request;
class CheckOut extends Model
{
    protected $table='check_out';
    protected $guarded= ['id'];

    public function getDataTableDriverHistory(Request $request)
    {
        $columns = array(
            0 => 'driver_name',
            1 => 'yard_out',
            2 => 'yard_in',
            3 => 'miles',
            4 => 'date',
        );
        $query = CheckOut::query();
        if($request->has('id')) $query = $query->where('id_fleet', $request->id);
        if($request->has('uid')) $query = $query->where('id_employee', $request->uid);
        $totalData = $query->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
        $query = $query
            ->get(['*'])
            ->map(function ($element) {
                return $this->mapDataTable($element);
            });
        
        if (!empty($request->input('search.value'))){
            $search = $request->input('search.value');
            $query = $query
                ->filter(function ($element) use ($search, $columns, $request) {
                    return $this->filterSearch($element, $search, $columns, $request);
                });
        }
        $query = $query->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir);
        if ($limit > 0)
            $query = $query
                ->skip($start)
                ->take($limit);
        $elements = $query
            ->values()
            ->all();
        $totalFiltered = $query->count();
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' =>  $totalFiltered,
            'aaData'               =>  $elements
        ];
        return $result;
    }

    public function getDataTableDotReportCheckout(Request $request)
    {
        $columns = array(
            0 => 'date',
            1 => 'driver',
        );
        $id=$request->id;
        $totalData = CheckOut::where('id_fleet',$id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;

       
        $elements = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $elements = CheckOut::
                where('id_fleet',$id)->
                get(['*'])
          
                ->map(function ($element) {
                        return $this->mapDataTableDotReport($element);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();

            } else {
                $elements = CheckOut::
                where('id_fleet',$id)->
                get(['*'])
        
                ->map(function ($element) {
                        return $this->mapDataTableDotReport($element);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $elements =  CheckOut::
                where('id_fleet',$id)->
                get(['*'])
              
                ->map(function ($element) {
                        return $this->mapDataTableDotReport($element);
                    })
                    ->filter(function ($element) use ($search, $columns, $request) {
                        return $this->filterSearch($element, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $elements =  CheckOut::
                where('id_fleet',$id)->
                get(['*'])
               
                ->map(function ($element) {
                        return $this->mapDataTableDotReport($element);
                    })
                    ->filter(function ($element) use ($search, $columns, $request) {
                        return $this->filterSearch($element, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = CheckOut::
            where('id_fleet',$id)->
            get(['*'])
               
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


    function mapDataTableDotReport($element)
    {
        $driver=User::where('id',$element->id_employee)->first();
        $checksTmp=DotReport::where('type','check_out_fleet')->where("id_reference",$element->id)->get();
        $checks=[];
        for ($i=0; $i < count($checksTmp) ; $i++) { 
        $checkTmp=Check::where("id",$checksTmp[$i]->id_check)->first();
        $tmp=[];
        $tmp["dot_report"] = $checksTmp[$i];
        $tmp["check"] = $checkTmp;

        array_push($checks,$tmp);
        }

        $element["date"]=Carbon::createFromFormat('Y-m-d H:i:s', $element->created_at)->format('m/d/Y h:i:s');
        $element["driver"] = $driver->name." ".$driver->last_name;
        $element["checks"] = $checks;
        $element['actions']=json_decode($element);
        return $element;
    }

    function mapDataTable($element)
    {
        $fleet=Fleet::where('id',$element->id_fleet)->first();
        $driver=User::where('id',$element->id_employee)->first();
        $check_in=CheckIn::where('id_check_out',$element->id)->first();
        $is_checkin = $check_in!=null;
        if($check_in==null) $check_in=new CheckIn;
        $element["check_in"] = $check_in;
        $element["driver_name"] = $driver->name." ".$driver->last_name;
        $element["fleet_name"] = "N°: $fleet->n $fleet->model";
        $element["yard_in"] = $check_in->place;
        $element["in_date"] = $check_in->created_at;
        $element["in_miles"] = $check_in->odometer_reading;
        $element["yard_out"] = $element->place;
        $element["out_date"] = $element->created_at;
        $element["out_miles"] = $element->odometer_reading;
        $element["elapsed_time"] = $is_checkin?$this->calculateElapsedTime($element->created_at, $check_in->created_at):null;
        return $element;
    }

    function calculateElapsedTime($outDate, $inDate)
    {
        $outDate = Carbon::parse($outDate);
        $inDate = Carbon::parse($inDate);
        $elapsed = $inDate->diff($outDate);

        $years = $elapsed->y > 0 ? ($elapsed->y === 1 ? $elapsed->y . " year" : $elapsed->y . " years") : "";
        $months = $elapsed->m > 0 ? ($elapsed->m === 1 ? $elapsed->m . " month" : $elapsed->m . " months") : "";

        if ($years || $months) {
            $elapsedTimeArr = array_filter([$years, $months]);
        } else {
            $days = $elapsed->d > 0 ? ($elapsed->d === 1 ? $elapsed->d . " day" : $elapsed->d . " days") : "";
            $hours = $elapsed->h > 0 ? ($elapsed->h === 1 ? $elapsed->h . " hour" : $elapsed->h . " hours") : "";
            $minutes = $elapsed->i > 0 ? ($elapsed->i === 1 ? $elapsed->i . " min" : $elapsed->i . " mins") : "";

            $elapsedTimeArr = array_filter([$days, $hours, $minutes]);
        }

        $elapsedTime = implode(" ", $elapsedTimeArr);

        return $elapsedTime;
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
