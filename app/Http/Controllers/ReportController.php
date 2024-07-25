<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Fleet;
use App\Models\Record;
use App\Models\Service;
use App\Models\Tool;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reports');
    }

    public function getPieChartData(Request $request){
        $from = $request->input('from');
        $to = $request->input('to');
        $fleet = new Fleet();
        $statuses = $fleet->getStatuses();
        foreach($statuses as $status){
            $fleet_status_data[$status] = Fleet::where('status', $status)->where('created_at', '<=', $to)->count();
            $fleet_status_cost[$status] = Fleet::where('status', $status)->where('created_at', '<=', $to)->sum('price');
        }
        $departments = $fleet->getDepartments();
        foreach($departments as $department){
            $fleet_department_data[$department] = Fleet::where('department', $department)->where('created_at', '<=', $to)->count();
            $fleet_department_cost[$department] = Fleet::where('department', $department)->where('created_at', '<=', $to)->sum('price');
        }
        $fleet_type_data['Vehicles'] = Fleet::where('type', 'trucks_cars')->where('created_at', '<=', $to)->count();
        $fleet_type_cost['Vehicles'] = Fleet::where('type', 'trucks_cars')->where('created_at', '<=', $to)->sum('price');
        $fleet_type_data['Equipments'] = Fleet::where('type', 'equipment')->where('created_at', '<=', $to)->count();
        $fleet_type_cost['Equipments'] = Fleet::where('type', 'equipment')->where('created_at', '<=', $to)->sum('price');
        $fleet_type_data['Trailers'] = Fleet::where('type', 'trailers')->where('created_at', '<=', $to)->count();
        $fleet_type_cost['Trailers'] = Fleet::where('type', 'trailers')->where('created_at', '<=', $to)->sum('price');
        //
        $service = new Service();
        $statuses = $service->getStatuses();
        foreach($statuses as $status){
            $service_data[$status] = Service::where('status', $status)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->count();
            $service_cost[$status] = 0;
            foreach(Service::where('status', $status)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get() as $service){
                foreach(Record::where('id_service', $service->id)->get() as $record){
                    $service_cost[$status] += $record->cost;
                    $tool = Tool::find($record->id_tool);
                    if($tool){
                        $service_cost[$status] += $tool->price;
                    }
                }
            }
        }
        //
        $tool_status_data['Available'] = Tool::where('status', 'true')->where('created_at', '<=', $to)->count();
        $tool_status_data['Disabled'] = Tool::where('status', 'false')->where('created_at', '<=', $to)->count();
        $tool_status_data['Stack Of Out'] = 0;
        foreach(Tool::where('status', 'true')->where('created_at', '<=', $to)->get() as $tool){
            $tool_status_data['Stack Of Out'] += $tool->availableStock<=0?1:0;
        }
        $tool_status_data['Available'] -= $tool_status_data['Stack Of Out'];
        $tool_department_data['Fleet'] = Tool::where('department', 'fleet')->where('created_at', '<=', $to)->count();
        $tool_department_data['Office'] = Tool::where('department', 'office')->where('created_at', '<=', $to)->count();
        $tool_department_data['Shop'] = Tool::where('department', 'shop')->where('created_at', '<=', $to)->count();
        $tool_department_data['General'] = Tool::where('department', 'general')->where('created_at', '<=', $to)->count();

        return response()->json(compact(
            'fleet_status_data', 'fleet_department_data', 'fleet_type_data',
            'fleet_status_cost', 'fleet_department_cost', 'fleet_type_cost',
            'service_data', 'service_cost',
            'tool_status_data', 'tool_department_data'
        ), 200);
    }
}
