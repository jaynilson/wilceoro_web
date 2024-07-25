<?php
namespace App\Http\Controllers;
use App\Models\checkIn;
use App\Models\checkOut;
use App\Models\FailureReport;
use App\Models\Fleet;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class FailureReportController extends Controller
{
    public function employeeFailureReportFleetInsert(Request $request)
    {
       if(
        !empty($request->id_fleet) &&
        !empty($request->id_employee) &&
        !empty($request->type) &&
        !empty($request->description)
        ){

            $failureReport = new FailureReport();
            $failureReport->type=$request->type;
            $failureReport->id_fleet=$request->id_fleet;
            $failureReport->id_employee=$request->id_employee;
            $failureReport->description=$request->description;
            $failureReport->status="pending";
            $failureReport->pictures="pictures";
            $failureReport->save();

            $fleet_title = "";
            $fleet = Fleet::find($request->id_fleet);
            if($fleet){
                $fleet_title = $fleet->n;
            }
            $employee_name = "";
            $employee = User::find($request->id_employee);
            if($employee){
                $employee_name = $employee->name;
            }
            $title = 'Employee '.$employee_name.' has reported "Failure" for vehicle with n '.$fleet_title;

            ActivityLog::create([
                'type' => 12,
                'title' => 'New Failure Report!',
                'desc' => $title,
                'href' => "#",
                'id_reference' => $failureReport->id,
            ]);

            return response()->json(["failureReport"=> $failureReport]);
       }else{
            return response()->json(["error"=>"fields are missing"],400);
       }
    }
}