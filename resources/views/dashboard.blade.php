@extends("$theme/layout")
@section('title') WilcoERP - dashboard @endsection
@section('styles_page_vendors')
<!-- <link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css"> -->
<!-- <link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" /> -->
@endsection
@section('styles_optional_vendors')
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/custom/jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet" type="text/css" />
<!-- <link href="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetimr-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="{{asset("assets")}}/css/session-calendar.css" rel="stylesheet" type="text/css" /> -->
@endsection
<link href="/css/dashboard.scss" rel="stylesheet" type="text/css"/>
@section('content_breadcrumbs') 
{!! SICAPHelper::getBreadCrumbs([
["route"=>"#","name"=>"Dashboard"],
["route"=>route('dashboard'),"name"=>"WilcoERP Dashboard"]
]) !!}
@endsection
@section('content_page')
@php 
$notifications=SICAPHelper::getNotificationsUser(auth()->user()->id,auth()->user()->id_rol); 
$notificationsNoRead=SICAPHelper::getNotificationsNoRead(auth()->user()->id,auth()->user()->id_rol); 
@endphp
<div class="dashboard-panel">
	<h1 class="mb-5">Welcome {{$user->name}}</h1>
	<div class="row">
		<div class="col-xs-12 col-lg-8">
			<!-- <div class="card chart-card">
				<div class="card-body">
					<div class="dropdown chart-button">
						<button class="btn text-muted p-0" type="button" id="overview_btn" data-toggle="dropdown">
							<i class="flaticon-more"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-end" aria-labelledby="overview_btn">
							<a class="dropdown-item" href="javascript:getLineChartData(0);">Operated Vehicles</a>
							<a class="dropdown-item" href="javascript:getLineChartData(1);">Services</a>
							<a class="dropdown-item" href="javascript:getLineChartData(2);">Inventories in use</a>
						</div>
					</div>
					<div id="chart"></div>
					<img class="chart-girl" src="{{asset("assets")}}/images/illustration-1.png"/>
				</div>
			</div> -->
			<div class="card p-2">
				<div class="card-body notification-card">
					<div class="d-flex justify-content-between pt-4 pb-4 mb-2">
						<h5 class="">Activity Timeline</h5>
					</div>
					<div>
						<ul class="timeline card-timeline mb-0" id="ul_activity_log"></ul>
					</div>
					<div class="w-100 text-center" id="more_activity_log" style="display:none;">
						<a href="#" onclick="showMoreActivityLogs()">
							<span class="color-primary font-weight-bold">Show More</span>
						</a>   
					</div>
				</div>
			</div>
			<div class="card overview-card p-3 mt-4">
				<div class="card-header">
					<div class="d-flex align-items-center justify-content-between">
						<h5 class="m-0 mr-2">Overview</h5>
						<div class="dropdown">
							<button class="btn text-muted p-0" type="button" id="overview_btn" data-toggle="dropdown">
								<i class="flaticon-more"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-end" aria-labelledby="overview_btn">
								<a class="dropdown-item" href="javascript:refreshOverview();">Refresh</a>
								<a class="dropdown-item" href="javascript:showOverview(0);">This month</a>
								<a class="dropdown-item" href="javascript:showOverview(1);">This Year</a>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3 col-6">
							<div class="d-flex align-items-center">
								<div class="avatar">
									<div class="avatar-initial bg-primary rounded shadow" onclick="location.href='fleet_manager_services';">
										<i class="flaticon-cogwheel text-white"></i>
									</div>
								</div>
								<div class="ml-3">
									<div class="mb-1">Services</div>
									<h5 class="mb-0" id="service_count">0</h5>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-6">
							<div class="d-flex align-items-center">
								<div class="avatar">
									<div class="avatar-initial bg-success rounded shadow" onclick="location.href='rental';">
										<i class="flaticon-tool text-white"></i>
									</div>
								</div>
								<div class="ml-3">
									<div class="mb-1">Rentals</div>
									<h5 class="mb-0" id="rental_count">0</h5>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-6">
							<div class="d-flex align-items-center">
							<div class="avatar">
								<div class="avatar-initial bg-warning rounded shadow">
									<i class="flaticon-bus-stop text-white"></i>
								</div>
							</div>
							<div class="ml-3">
								<div class="mb-1">Accidents</div>
								<h5 class="mb-0" id="accident_count">0</h5>
							</div>
							</div>
						</div>
						<div class="col-md-3 col-6">
							<div class="d-flex align-items-center">
								<div class="avatar">
									<div class="avatar-initial bg-info rounded shadow" style="background-color: rgba(22, 177, 255)!important;" onclick="location.href='users';">
										<i class="flaticon-users text-white"></i>
									</div>
								</div>
								<div class="ml-3">
									<div class="mb-1">Users</div>
									<h5 class="mb-0" id="user_count"></h5>
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
			<div class="row mt-5" style="margin-left: 0px;">
				<div class="col-6" id="chart_fleet_status" style="width:100%;height:100%;"></div>
				<div class="col-6" id="chart_fleet_department" style="width:100%;height:100%;"></div>
			</div>
			<div class="card vehicles-card p-3 mt-4">
				<div class="card-header">
					<div class="d-flex align-items-center justify-content-between">
						<h5 class="m-0 mr-2 vehicle-table-title">Available Vehicles</h5>
						<div class="dropdown">
							<button class="btn text-muted p-0" type="button" id="overview_btn" data-toggle="dropdown">
								<i class="flaticon-more"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-end" aria-labelledby="overview_btn">
								<a class="dropdown-item" href="javascript:showVehicles('true');">Available Vehicles</a>
								<a class="dropdown-item" href="javascript:showVehicles('in-service');">Vehicles In Service</a>
								<a class="dropdown-item" href="javascript:showVehicles('check-out');">In-Use Vehicles</a>
								<a class="dropdown-item" href="javascript:showVehicles('false');">Out of Service</a>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<table class="table-bordered table-hover table-data-custom" id="table_vehicles">
						<thead>
							<tr>
								<th>ID</th>
								<th>Type</th>
								<th>Model</th>
								<th>Licence plate</th>
								<th>Year</th>
								<th>Yard location</th>
								<th>Department</th>
								<th>Status</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			<div class="row mt-5" style="margin-left: 0px;">
				<div class="col-6" id="chart_tool_status" style="width:100%;height:100%;"></div>
				<div class="col-6" id="chart_tool_department" style="width:100%;height:100%;"></div>
			</div>	
			<div class="card vehicles-card p-3 mt-4">
				<div class="card-header">
					<div class="d-flex align-items-center justify-content-between">
						<h5 class="m-0 mr-2 inventory-table-title">Available Inventories</h5>
						<div class="dropdown">
							<button class="btn text-muted p-0" type="button" id="overview_btn" data-toggle="dropdown">
								<i class="flaticon-more"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-end" aria-labelledby="overview_btn">
								<a class="dropdown-item" href="javascript:showInventories(0);">Available Inventories</a>
								<a class="dropdown-item" href="javascript:showInventories(1);">Stack Of Out Inventories</a>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<table class="table-bordered table-hover table-data-custom" id="table_inventories">
						<thead>
							<tr>
								<th>ID</th>
								<th>Department</th>
								<th>Title/name</th>
								<th>Price</th>
								<th>Stock</th>
								<th>Type</th>
								<th>Required Return</th>
								<th>Status</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-4">
			<div class="card p-2">
				<div class="card-body notification-card">
					<div class="d-flex justify-content-between pt-4 pb-4">
						<h5 class="">New Notifications</h5>
						<i class="text-dark-50 color-primary">{{$notificationsNoRead}}</i>
					</div>
					<div>
						@for($i = 0; $i < count($notifications) && $i < 6; $i++)
						<div style="cursor: pointer;" onclick="readOpenNotification({{$notifications[$i]->id}},this)" class="row ">
							<div class="col-3">
								<img class="avatar-circle-card" src="{{asset("assets")}}/images/{{$notifications[$i]->icon}}" >
							</div>
							<div class="col-9">
								<div>
								<h6 class="m-0">{{$notifications[$i]->title}}</h6>
								<p class="text-muted m-0">{{$notifications[$i]->message}}</p>
								<hr>
								</div>
							</div>
						</div>
						@endfor
					</div>
					<div class="w-100 text-center">
						<a href="{{route('notification')}}">
							<span class="color-primary font-weight-bold">Show All</span>
						</a>   
					</div>
				</div>
			</div>
			<div class="card p-2 mt-4">
				<div class="card-body">
					<div class="d-flex justify-content-between pt-4 pb-4">
						<h5 class="">Work Orders</h5>
						<i class="text-dark-50 color-primary work-orders-count"></i>
					</div>
					<div class="work-orders-wrapper"></div>
					<div class="w-100 text-center" id="more_work_orders" style="display:none;">
						<a href="#" onclick="showMoreWorkOrders()">
							<span class="color-primary font-weight-bold">Show More</span>
						</a>   
					</div>
				</div>
			</div>
		</div>
	</div>
 </div>
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
@endsection
@section('js_page_vendors')
<script>
var routePublicImages=@json(asset("assets")."/images/");
</script>
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<!-- <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script> -->
<!-- <script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script> -->
<!-- <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script> -->
<!-- <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datetimr-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script> -->
<!-- <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script> -->
<!-- <script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-timepicker/init.js" type="text/javascript"></script> -->
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<!-- <script src="{{asset("assets/$theme")}}/vendors/custom/jquery-ui-1.12.1/jquery-ui.js" type="text/javascript"></script> -->
<!-- <script src="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.ui.position.js" type="text/javascript"></script> -->
<!-- <script src="{{asset("assets/$theme")}}/vendors/custom/jquery-context-menu/jquery.contextMenu.js" type="text/javascript"></script> -->
<script src="{{asset("assets")}}/js/gstatic.com_charts_loader.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/flot/flot.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/page-dashboard.js" type="text/javascript"></script>
@endsection

