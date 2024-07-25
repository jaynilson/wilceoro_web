<?php

namespace App\Http\Controllers;

use App\Models\CheckOut;
use App\Models\Check;
use App\Models\DotReport;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DotReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings');
    }

    public function insert(Request $request)
    {
      $dotReport =  new  DotReport();
      $dotReport->id_check= $request->id_check;
      $dotReport->status=$request->status;
      $dotReport->is_critical=$request->is_critical;
      $dotReport->type=$request->type;
      $dotReport->id_reference=$request->id_reference;
      $dotReport->save();

      $check=Check::where('id',$request->id_check)->first();

      ActivityLog::create([
        'type' => 13,
        'title' => 'New DOT report',
        'desc' => "id_check: ".$request->id_check.", status:  ".$request->status.", is_critical: ".$request->is_critical.", type: ".$request->type.", id_reference:".$request->id_reference,
        'href' => "#",
        'id_reference' => $dotReport->id,
      ]);

      return response()->json(["dotReport"=> $dotReport]);
    }

    public function dotReportCheckOutDataTable(Request $request){
      $data = new CheckOut();
      $data = $data->getDataTableDotReportCheckout($request);
      return response()->json($data);
    }
}
