<?php
namespace App\Http\Controllers;
use App\Models\CheckOut;
use App\Models\CheckIn;
use App\Models\Fleet;
use App\Models\Record;
use App\Models\Rental;
use App\Models\Report;
use App\Models\ReportProblem;
use App\Models\Rol;
use App\Models\Service;
use App\Models\User;
use App\Models\Tool;
use App\Models\FleetCustomField;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExports;
use App\Exports\UserExports;
use App\Exports\FleetExport;
use App\Exports\ServiceExport;
use App\Exports\ToolExport;
use App\Imports\UserImports;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        FleetCustomField::where('type', 'number')->update(['type'=>'integer']);//TODO remove this later absolutely
        $user = User::findOrFail(auth()->user()->id);
        // $tmp=Record::whereYear('created_at', (Carbon::now()->year)-1)
        // ->whereMonth('created_at', $i)
        // ->get();
        return view('dashboard', compact('user'));
    }

    public function refreshCsrf(Request $request)
    { 
        return csrf_token();
    }

    public function getOperatedVehiclesCount(Request $request)
    {
        $res[] = ['Month', 'This year'];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 0; $i < 12; $i++) {
            $startDate = Carbon::now()->startOfYear()->addMonths($i)->startOfMonth()->toDateString();
            $endDate = Carbon::now()->startOfYear()->addMonths($i)->endOfMonth()->toDateString();
            $val = CheckOut::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)
                ->distinct('id_fleet')->count('id_fleet');
            $res[] = [
                $months[$i],
                $val,
            ];
        }
        return response()->json(['data' => $res], 200);
    }

    public function getServicesCount(Request $request)
    {
        $res[] = ['Month', 'This year'];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 0; $i < 12; $i++) {
            $startDate = Carbon::now()->startOfYear()->addMonths($i)->startOfMonth()->toDateString();
            $endDate = Carbon::now()->startOfYear()->addMonths($i)->endOfMonth()->toDateString();
            $val = Service::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)
                ->distinct('id_fleet')->count('id_fleet');
            $res[] = [
                $months[$i],
                $val,
            ];
        }
        return response()->json(['data' => $res], 200);
    }

    public function getInventoriesInUseCount(Request $request)
    {
        $res[] = ['Month', 'This year'];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 0; $i < 12; $i++) {
            $startDate = Carbon::now()->startOfYear()->addMonths($i)->startOfMonth()->toDateString();
            $endDate = Carbon::now()->startOfYear()->addMonths($i)->endOfMonth()->toDateString();
            $val = Record::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)
                ->distinct('id_tool')->count('id_tool');
            $val += Rental::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)
                ->distinct('id_tool')->count('id_tool');
            $val += CheckOut::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)
                ->distinct('id_tool')->count('id_tool');
            $val += CheckIn::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)
                ->distinct('id_tool')->count('id_tool');
            $res[] = [
                $months[$i],
                $val,
            ];
        }
        return response()->json(['data' => $res], 200);
    }

    public function getOverviewData(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $services = Service::where('created_at','>=',$from)->where('created_at','<=',$to)->count();
        $rentals = Rental::where('created_at','>=',$from)->where('created_at','<=',$to)->count();
        $accidents = ReportProblem::where('created_at','>=',$from)->where('created_at','<=',$to)->count();
        $users = User::count();
        return response()->json(compact(
            'services', 'rentals', 'accidents', 'users',
        ), 200);
    }

    public function getActivityLogsData(Request $request)
    {
        $data = new ActivityLog();
        $data = $data->getDataTable($request);
        return response()->json($data);
    }

    public function exportReportsToExcel(Request $request){
        $fileName = 'reports_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new ReportExports, $fileName);
    }

    public function exportUsersToExcel(Request $request){
        $fileName = 'users_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new UserExports, $fileName);
    }

    public function exportFleetsToExcel(Request $request){
        $fileName = 'fleets_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new FleetExport, $fileName);
    }

    public function exportServicesToExcel(Request $request){
        $fileName = 'services_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new ServiceExport, $fileName);
    }

    public function exportToolsToExcel(Request $request){
        $fileName = 'tools_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new ToolExport, $fileName);
    }

    public function importUsersFromExcel(Request $request){
        $file = $request->file('file');
        $import = new UserImports();
        Excel::import($import, $file);
        return redirect()->back()->with('success', 'Users imported successfully');
    }
}
