<?php

use App\Http\Controllers\AccidentReportController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\DamageController;
use App\Http\Controllers\DotReportController;
use App\Http\Controllers\FailureReportController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\InterfacesController;
use App\Http\Controllers\LostController;
use App\Http\Controllers\ReportProblemController;
use App\Http\Controllers\RequestCategoryController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ToolsController;
use App\Models\ReportProblem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login_pin', [UserController::class,'loginPin']);
Route::post('resend_pin', [UserController::class,'resendPin']);

Route::group(['middleware' => ['auth.api']], function() {
    Route::get('fleet', [FleetController::class,'fleetList']);
    Route::get('interfaces', [InterfacesController::class,'getInterfaces']);
    Route::get('request_category', [RequestCategoryController::class,'requestCategoryList']);
    Route::get('tool', [ToolsController::class,'toolList']);

    Route::post('check_in_employee_fleet', [CheckInController::class,'employeeFleetInsert']);
    Route::post('check_out_employee_fleet', [CheckOutController::class,'employeeFleetInsert']);

    Route::get('check_out', [CheckOutController::class,'getCheckout']);
    Route::get('check_out_tool', [CheckOutController::class,'getCheckoutTool']);

    Route::post('asset', [AssetController::class,'insert']);
    Route::post('employee_failure_report_fleet', [FailureReportController::class,'employeeFailureReportFleetInsert']);
    Route::post('employee_report_problem_fleet', [ReportProblemController::class,'employeeFleetInsert']);

    Route::post('accident_report', [AccidentReportController::class,'accidentReportInsert']);
    Route::post('accident_report_answer', [AccidentReportController::class,'accidentReportAnswerInsert']);
    Route::post('accident_report_linked', [AccidentReportController::class,'accidentReportLinked']);

    Route::post('check_in_employee_tool', [CheckInController::class,'employeeToolInsert']);
    Route::post('check_out_employee_tool', [CheckOutController::class,'employeeToolInsert']);

    Route::post('request_employee_tool', [RequestController::class,'employeeRequestToolInsert']);
    Route::post('request_employee_fleet', [RequestController::class,'employeeRequestFleetInsert']);

    Route::post('damage', [DamageController::class,'damageInsert']);
    Route::post('lost', [LostController::class,'lostInsert']);
    Route::post('update_profile_employee', [UserProfileController::class,'updateProfileEmployee']);

    Route::post('dot_report', [DotReportController::class,'insert']);

    Route::post('getLocations', [FleetController::class, 'getLocationList']);
    Route::post('getFleets', [FleetController::class,'getFleetList']);
    Route::post('getDepartments', [FleetController::class,'getDepartmentList']);
});


