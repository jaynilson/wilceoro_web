<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\FleetManagerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\DotReportController;
use App\Http\Controllers\RequestCategoryController;
use App\Http\Controllers\InterfacesController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\AccidentReportController;
use App\Http\Controllers\ReportProblemController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('login', 'Security\LoginController@index')->name('login');
Route::post('login', 'Security\LoginController@login')->name('login_in');
Route::get('logout', 'Security\LoginController@logout')->name('logout');

Route::get('register', [UserController::class, 'register'])->name('register');
//Auth::routes();
//password reset routes
Route::post('password/email', 'Auth\UserForgotPasswordController@sendResetLinkEmail')->name('employee.password.email');
Route::get('password/reset', 'Auth\UserForgotPasswordController@showLinkRequestForm')->name('employee.password.request');
Route::post('password/reset', 'Auth\UserResetPasswordController@reset')->name('employee.password.update');
Route::get('password/reset/{token}', 'Auth\UserResetPasswordController@showResetForm')->name('employee.password.reset');

Route::get('restricted_permission', 'RestrictedPermission@index');
  
//notifications
Route::get('notification', [NotificationController::class, 'indexNotification'])->name('notification');
Route::post('notification/change_status_notification', [NotificationController::class, 'changeStatusNotification'])->name('notification_change_status_notification');
Route::post('notification/last', [NotificationController::class, 'getLastNotifications'])->name('notification_last');
Route::post('notification/dataTable', [NotificationController::class, 'dataTable'])->name('notification_dataTable');
Route::delete('notification/delete_notification', [NotificationController::class, 'destroy'])->name('notification_delete_notification');

Route::group(['prefix' => '/', 'middleware' => ['auth', 'enable_user', 'admin.permission']], function () {
  Route::get('/', [DashboardController::class, 'index']);
  Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::post('refresh_csrf', [DashboardController::class, 'refreshCsrf']);
  Route::post('dashboard/getOperatedVehiclesCount', [DashboardController::class, 'getOperatedVehiclesCount']);
  Route::post('dashboard/getServicesCount', [DashboardController::class, 'getServicesCount']);
  Route::post('dashboard/getInventoriesInUseCount', [DashboardController::class, 'getInventoriesInUseCount']);
  Route::post('dashboard/getOverviewData', [DashboardController::class, 'getOverviewData']);
  Route::post('dashboard/getActivityLogsData', [DashboardController::class, 'getActivityLogsData']);
  Route::get('export-reports-excel', [DashboardController::class, 'exportReportsToExcel']);
  Route::get('export-users-excel', [DashboardController::class, 'exportUsersToExcel']);
  Route::get('export-fleet-excel', [DashboardController::class, 'exportFleetsToExcel']);
  Route::get('export-service-excel', [DashboardController::class, 'exportServicesToExcel']);
  Route::get('export-tool-excel', [DashboardController::class, 'exportToolsToExcel']);
  Route::post('import-users-excel', [DashboardController::class, 'importUsersFromExcel']);
  //user
  Route::get('users', [UserController::class, 'users'])->name('users');
  Route::post('user/dataTable', [UserController::class, 'dataTable'])->name('user_data_table');
  Route::post('user/dataTableEmployee', [UserController::class, 'dataTableEmployee'])->name('user_data_table_employee');
  Route::post('user/dataTableMechanic', [UserController::class, 'dataTableMechanic'])->name('user_data_table_mechanic');
  Route::post('user/insert', [UserController::class, 'insert'])->name('user_insert');
  Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user_edit');
  Route::put('user/update', [UserController::class, 'update'])->name('user_update');
  Route::delete('user/delete/', [UserController::class, 'delete'])->name('user_delete');
  Route::post('api/getRoleList', [UserController::class, 'getRoleList']);
  Route::post('api/getUserList', [UserController::class, 'getUserList']);
  //profile
  Route::get('user_profile', [UserProfileController::class, 'index'])->name('user_profile');
  Route::put('user_profile/update', [UserProfileController::class, 'update'])->name('user_profile_update');
  Route::put('user_profile/change_password', [UserProfileController::class, 'changePassword'])->name('user_profile_change_password');
  //fleet
  Route::get('trucks_cars', [FleetController::class, 'trucksCars'])->name('trucks_cars');
  Route::get('trailers', [FleetController::class, 'trailers'])->name('trailers');
  Route::get('equipment', [FleetController::class, 'equipment'])->name('equipment');
  Route::post('fleet_dataTable', [FleetController::class, 'dataTable'])->name('fleet_dataTable');
  Route::post('fleet_all_dataTable', [FleetController::class, 'dataTableAll'])->name('fleet_all_dataTable');
  Route::post('fleet/insert', [FleetController::class, 'insert'])->name('fleet_insert');
  Route::post('fleet/update', [FleetController::class, 'update'])->name('fleet_update');
  Route::delete('fleet/delete', [FleetController::class, 'delete'])->name('fleet_delete');
  Route::post('api/getFleetList', [FleetController::class, 'getFleetList']);
  Route::post('api/getLocationList', [FleetController::class, 'getLocationList']);
  Route::post('api/getDepartmentList', [FleetController::class, 'getDepartmentList']);
  //fleet manager
  Route::get('fleet_detail/{id}', [FleetManagerController::class, 'fleetDetail'])->name('fleet_detail');
  Route::post('fleet_detail_update', [FleetManagerController::class, 'fleetDetailUpdate'])->name('fleet_detail_update');
  Route::get('fleet_manager_services', [FleetManagerController::class, 'services'])->name('fleet_manager_services');
  Route::get('fleet_manager_service/{id}', [FleetManagerController::class, 'service'])->name('fleet_manager_service');
  Route::post('driver_history_dataTable', [FleetManagerController::class, 'driverHistoryDataTable'])->name('driver_history_dataTable');
  Route::post('maintenance_dataTable', [FleetManagerController::class, 'maintenanceDataTable'])->name('maintenance_dataTable');
  Route::post('fleet_document', [FleetManagerController::class, 'fleetDocument'])->name('fleet_document');
  Route::delete('file_fleet_delete', [FleetManagerController::class, 'fleetDocumentDelete'])->name('file_fleet_delete');
  //
  Route::post('fleet_record/save', [FleetManagerController::class, 'recordSave'])->name('fleet_record_save');
  Route::delete('fleet_record/delete/{id}', [FleetManagerController::class, 'recordDelete'])->name('fleet_record_delete');
  Route::post('fleet_record/dataTable', [FleetManagerController::class, 'recordDataTable'])->name('fleet_record_dataTable');
  Route::post('fleet_record/saveFile', [FleetManagerController::class, 'saveRecordFile'])->name('save_fleet_record_file');
  //
  Route::post('fleet_tool/save', [FleetManagerController::class, 'toolSave'])->name('fleet_tool_save');
  Route::delete('fleet_tool/delete/{id}', [FleetManagerController::class, 'toolDelete'])->name('fleet_tool_delete');
  Route::post('fleet_tool/dataTable', [FleetManagerController::class, 'toolDataTable'])->name('fleet_tool_dataTable');
  //
  Route::post('fleet_custom/save/{id}', [FleetManagerController::class, 'customSave'])->name('fleet_custom_save');
  Route::delete('fleet_custom/delete/{id}/{row_id}', [FleetManagerController::class, 'customDelete'])->name('fleet_custom_delete');
  Route::post('fleet_custom/dataTable/{id}', [FleetManagerController::class, 'customDataTable'])->name('fleet_custom_dataTable');
 
  //tool
  Route::get('tools', [ToolsController::class, 'index'])->name('tools');
  Route::get('tools/fleet', [ToolsController::class, 'index'])->name('tools_fleet');
  Route::get('tools/office', [ToolsController::class, 'index'])->name('tools_office');
  Route::get('tools/shop', [ToolsController::class, 'index'])->name('tools_shop');
  Route::get('tools/general', [ToolsController::class, 'index'])->name('tools_general');
  Route::post('tool/insert/{department}', [ToolsController::class, 'insert'])->name('tool_insert');
  Route::post('tool_dataTable', [ToolsController::class, 'dataTable'])->name('tool_dataTable_all');
  Route::post('tool_dataTable/{department}', [ToolsController::class, 'dataTable'])->name('tool_dataTable');
  Route::post('tool/update/{department}', [ToolsController::class, 'update'])->name('tool_update');
  Route::delete('tool/delete/{department}', [ToolsController::class, 'delete'])->name('tool_delete');
  //?
  Route::get('reports', [ReportController::class, 'index'])->name('reports');
  Route::post('reports/getPieChartData', [ReportController::class, 'getPieChartData']);
  //setting
  Route::get('settings', [SettingController::class, 'index'])->name('settings');
  Route::post('settings/fleet_custom/save', [SettingController::class, 'fleetCustomSave'])->name('fleet_custom_field_save');
  Route::delete('settings/fleet_custom/delete', [SettingController::class, 'fleetCustomDelete'])->name('fleet_custom_field_delete');
  Route::post('settings/fleet_custom/dataTable', [SettingController::class, 'fleetCustomDataTable'])->name('fleet_custom_field_dataTable');
  Route::post('settings/permission/dataTable', [SettingController::class, 'permissionDataTable'])->name('permission_dataTable');
  Route::post('settings/permission/save', [SettingController::class, 'permissionSave'])->name('permission_save');
  //interface    
  Route::get('interfaces', [InterfacesController::class, 'interfaces'])->name('interfaces');
  Route::post('interface/insert', [InterfacesController::class, 'insert'])->name('interface_insert');
  Route::post('interface/update', [InterfacesController::class, 'update'])->name('interface_update');
  Route::delete('interface/delete', [InterfacesController::class, 'delete'])->name('interface_delete');
  Route::post('interface_dataTable', [InterfacesController::class, 'dataTable'])->name('interface_dataTable');
  Route::post('api/getInterfaceList', [InterfacesController::class, 'getInterfaceList']);
  //request
  Route::get('request_categories', [RequestCategoryController::class, 'requestCategories'])->name('request_categories');
  Route::post('request_category/insert', [RequestCategoryController::class, 'insert'])->name('request_category_insert');
  Route::post('request_category/update', [RequestCategoryController::class, 'update'])->name('request_category_update');
  Route::delete('request_category/delete', [RequestCategoryController::class, 'delete'])->name('request_category_delete');
  Route::post('request_category_dataTable', [RequestCategoryController::class, 'dataTable'])->name('request_category_dataTable');
  //service
  Route::post('service_insert', [ServiceController::class, 'insert'])->name('service_insert');
  Route::post('services_dataTable', [ServiceController::class, 'dataTable'])->name('services_dataTable');
  Route::delete('service_delete', [ServiceController::class, 'delete'])->name('service_delete');
  Route::post('service_update', [ServiceController::class, 'update'])->name('service_update');
  Route::post('service_record', [ServiceController::class, 'insertRecord'])->name('service_record');
  Route::post('api/getServiceList', [ServiceController::class, 'getServiceList']);
  Route::post('services_recordsDataTable', [ServiceController::class, 'recordsDataTable'])->name('services_recordsDataTable');
  Route::delete('record_delete', [ServiceController::class, 'recordDelete'])->name('record_delete');
  Route::post('record_file', [ServiceController::class, 'recordFile'])->name('record_file');
  Route::post('delete_record_file', [ServiceController::class, 'deleteFile'])->name('delete_record_file');
  Route::post('service_record_update', [ServiceController::class, 'recordUpdate'])->name('service_record_update');
  //
  Route::post('dot_report_checkout_dataTable', [DotReportController::class, 'dotReportCheckOutDataTable'])->name('dot_report_checkout_dataTable');
  Route::post('service_request_dataTable', [ReportProblemController::class, 'serviceRequestTable'])->name('service_request_dataTable');
  //reminder
  Route::get('reminder/create', [ReminderController::class, 'create'])->name('reminder_create');
  Route::get('reminder/create/{id_fleet}', [ReminderController::class, 'create'])->name('reminder_create');
  Route::get('reminder/edit/{id}', [ReminderController::class, 'edit'])->name('reminder_edit');
  Route::get('reminder/edit/{id}/{id_fleet}', [ReminderController::class, 'edit'])->name('reminder_edit');
  Route::post('reminder/save', [ReminderController::class, 'save'])->name('reminder_save');
  Route::post('reminder/dataTable', [ReminderController::class, 'dataTable'])->name('reminder_dataTable');
  Route::delete('reminder/delete/{id}', [ReminderController::class, 'delete'])->name('reminder_delete');
  //
  Route::post('check_in_employee_fleet', [CheckInController::class,'employeeFleetInsert']);
  Route::post('check_out_employee_fleet', [CheckOutController::class,'employeeFleetInsert']);
  //
  //rental
  Route::get('rental', [RentalController::class,'index'])->name('rental');
  Route::post('rental/save', [RentalController::class,'save'])->name('rental_save');
  Route::post('rental/dataTable', [RentalController::class,'dataTable'])->name('rental_dataTable');
  Route::delete('rental/delete', [RentalController::class,'delete'])->name('rental_delete');

  //
  Route::post('accident/dataTable', [AccidentReportController::class,'dataTable'])->name('accident_dataTable');

  //asset files delete
  Route::delete('asset/delete/{id}', 'AssetController@delete')->name('delete_file');
});