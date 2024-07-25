@extends("$theme/layout")
@section('title') WilcoERP - Reports @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css"/>
@endsection
@section('styles_optional_vendors')
@endsection
@section('content_breadcrumbs') 
{!! SICAPHelper::getBreadCrumbs([
["route"=>"#","name"=>"Panel de Control"],
["route"=>"#","name"=>"Auditorias (logs)"]
]) !!}
@endsection
@section('content_page')
<!-- begin:: Content -->
<div class="row">
    <div class="col-12 mb-4 d-flex justify-content-between">
        <h1 class="color-black">Report</h1>
        <div style="max-height: 37px;">
            <a href="/export-reports-excel" class="btn btn-brand btn-elevate btn-icon-sm">
                <i class="la la-download"></i>
                Export
            </a>
        </div>
    </div>
    <div class="col-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button onclick="setTab('nav-fleetchart')" class="nav-link active" id="nav-fleetchart-tab" data-toggle="tab" data-target="#nav-fleetchart" type="button" role="tab" aria-controls="nav-fleetchart" aria-selected="true">Vehicle Charts</button>
                <button onclick="setTab('nav-costchart')" class="nav-link" id="nav-costchart-tab" data-toggle="tab" data-target="#nav-costchart" type="button" role="tab" aria-controls="nav-costchart" aria-selected="true">Cost Charts</button>
                <button onclick="setTab('nav-servicechart')" class="nav-link" id="nav-servicechart-tab" data-toggle="tab" data-target="#nav-servicechart" type="button" role="tab" aria-controls="nav-servicechart" aria-selected="true">Service Charts</button>
                <button onclick="setTab('nav-toolchart')" class="nav-link" id="nav-toolchart-tab" data-toggle="tab" data-target="#nav-toolchart" type="button" role="tab" aria-controls="nav-toolchart" aria-selected="true">Inventory Charts</button>
            </div>
            <div class="d-flex" style="position:absolute;right:15px;top:-23px;">
                
                <div class="form-group mr-2">
                    <label for="filter_from" class="form-control-label mb-0 mr-2">From</label>
                    <input type="text" class="form-control form-datepicker" name="filter_from" id="filter_from" value="{{ date('m/d/Y', strtotime('-1 week')) }}"/>
                </div>
                <div class="form-group">
                    <label for="filter_to" class="form-control-label mb-0 mr-2">To</label>
                    <input type="text" class="form-control form-datepicker" name="filter_to" id="filter_to" value="{{ date('m/d/Y') }}"/>
                </div>
            </div>
        </nav>
        
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-fleetchart" role="tabpanel" aria-labelledby="nav-fleetchart-tab">
                <div class="kt-portlet__body">
                    <div class="container">
                        <div class="row">
                            <div class="col-6" id="chart_fleet_status" style="width:600px;height:400px;"></div>
                            <div class="col-6" id="chart_fleet_type" style="width:600px;height:400px;"></div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-6" id="chart_fleet_department" style="width:600px;height:400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-costchart" role="tabpanel" aria-labelledby="nav-costchart-tab">
                <div class="kt-portlet__body">
                    <div class="container">
                        <div class="row">
                            <div class="col-6" id="chart_cost_status" style="width:600px;height:400px;"></div>
                            <div class="col-6" id="chart_cost_type" style="width:600px;height:400px;"></div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-6" id="chart_cost_department" style="width:600px;height:400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-servicechart" role="tabpanel" aria-labelledby="nav-servicechart-tab">
                <div class="kt-portlet__body">
                    <div class="container">
                        <div class="row">
                            <div class="col-6" id="chart_service_status" style="width:600px;height:400px;"></div>
                            <div class="col-6" id="chart_service_cost" style="width:600px;height:400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-toolchart" role="tabpanel" aria-labelledby="nav-toolchart-tab">
                <div class="kt-portlet__body">
                    <div class="container">
                        <div class="row">
                            <div class="col-6" id="chart_tool_status" style="width:600px;height:400px;"></div>
                            <div class="col-6" id="chart_tool_department" style="width:600px;height:400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
@endsection
@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets")}}/js/gstatic.com_charts_loader.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/flot/flot.bundle.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-reports.js" type="text/javascript"></script>
@endsection

