@extends("$theme/layout")
@section('title') WilcoERP - Services @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
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
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                {{-- <i class="kt-font-brand flaticon-map"></i> --}}
            </span>
            <h3 class="kt-portlet__head-title">
               Services
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions d-flex">
                    <div class="d-flex align-items-center mr-4">
                        <label for="stock" class="form-control-label mb-0 mr-2">Status</label>
                        <select name="filter_status" class="form-control" style="border-radius:0px;" id="filter_status">
                            <option class="text-capitalize" value="__ALL__">ALL</option>
                            @foreach ($status as $st)
                                <option class="text-capitalize" value="{{ $st==""?"Unassigned":$st }}">{{ $st==""?"Unassigned":$st }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(SICAPHelper::checkPermission(11, 2)||SICAPHelper::checkPermission(11, 3))
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i> Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__section kt-nav__section--first">
                                    <span class="kt-nav__section-text">Choose an option</span>
                                </li>
                                @if(SICAPHelper::checkPermission(11, 2))
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add">
                                        <span class="kt-nav__link-text">Add new</span>
                                    </a>
                                </li>
                                @endif
                                @if(SICAPHelper::checkPermission(11, 3))
                                <li class="kt-nav__item">
                                    <a href="#" onclick="deleteSelected()" id="btn-delete-employees" class="kt-nav__link">
                                        <span class="kt-nav__link-text">Delete selected</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endif
                    @if(SICAPHelper::checkPermission(11, 2))
                    &nbsp;
                    <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add">
                        <i class="la la-plus"></i>
                        New
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="container">
            <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom" id="kt_table_services">
                <thead>
                    <tr>
                        <th class="clean-icon-table">
                            <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                                <input type="checkbox" name="select_all" value="1" id="select-all">
                                <span></span>
                            </label>
                        </th>
                        <th>Date Requested</th>
                        <th>Date Needed</th>
                        <th>Date Completed</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>
@if(SICAPHelper::checkPermission(11, 2))
<!--start: Modal add service -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('service_insert')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description" class="form-control-label">Description <font style="color:red;">*</font></label>
                                <input type="text" name="description" class="form-control" id="description" value="{{old('description')}}" required/>
                            </div>
                            <div class="form-group">
                                <label for="id_rol">Type <font style="color:red;">*</font></label>
                                <select name="type" class="form-control text-capitalize" id="type" required>
                                    <option class="text-capitalize" value="" {{old('type')?'':'selected'}}  disabled>Select type</option>
                                    <option class="text-capitalize" value="preventive" {{old('type')? ('preventive'==old('type'))? 'selected':'' :''}}>Preventive</option>
                                    <option class="text-capitalize" value="corrective" {{old('type')? ('corrective'==old('type'))? 'selected':'' :''}}>Corrective</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Driver <font style="color:red;">*</font></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectEmployee('add')">Select driver</button>
                                    </div>
                                    <input  class="form-control" id="text-employee" value="" readonly required/>
                                </div>
                                <input type="hidden" name="id_employee" id="id-employee"/>
                            </div>
                            <div class="form-group">
                                <label>Vehicle <font style="color:red;">*</font></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectFleet('add')">Select vehicle</button>
                                    </div>
                                    <input  class="form-control" id="text-vehicle" name="text-vehicle" value="" readonly required/>
                                </div>
                                <input type="hidden" name="id_fleet" id="id-fleet"/>
                            </div>
                            <div class="form-group">
                                <label for="needed_date" class="form-control-label">Date Needed</label>
                                <input type="text" name="needed_date" class="form-control form-datepicker" id="needed-date"/>
                            </div>
                            <div class="form-group">
                                <label for="completed_date" class="form-control-label">Date Completed</label>
                                <input type="text" name="completed_date" class="form-control form-datepicker" id="completed-date"/>
                            </div>
                            <div class="form-group">
                                <label for="engine_hours" class="form-control-label">Engine Hours</label>
                                <input type="number" name="engine_hours" class="form-control" id="engine-hours"/>
                            </div>
                            <div class="form-group">
                                <label for="working" class="form-control-label">Work</label>
                                <select name="working" id="working" class="form-control">
                                    <option value="in-house" selected>In-House</option>
                                    <option value="outsourced">Outsourced</option>
                                </select>
                            </div>
                            <input type="hidden" name="status" id="status" value="Unassigned"/>
                            <div class="form-group">
                                <label for="notes" class="form-control-label">Notes</label>
                                <textarea name="notes" class="form-control" id="notes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal add service -->
@endif
@if(SICAPHelper::checkPermission(11, 1))
<!--start: Modal edit service -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('service_update')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description-edit" class="form-control-label">Description <font style="color:red;">*</font></label>
                                <input type="text" name="description" class="form-control" id="description-edit" required/>
                            </div>
                            <div class="form-group">
                                <label for="id_rol_edit">Type <font style="color:red;">*</font></label>
                                <select name="type" class="form-control text-capitalize" id="type-edit" required>
                                    <option class="text-capitalize" value=""  disabled>Select type</option>
                                    <option class="text-capitalize" value="preventive" >Preventive</option>
                                    <option class="text-capitalize" value="corrective" >Corrective</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Driver <font style="color:red;">*</font></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectEmployee('edit')">Select driver</button>
                                    </div>
                                    <input  class="form-control" id="text-employee-edit" value="" readonly required/>
                                </div>
                                <input type="hidden" name="id_employee" id="id-employee-edit">
                            </div>
                            <div class="form-group">
                                <label>Vehicle <font style="color:red;">*</font></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectFleet('edit')">Select vehicle</button>
                                    </div>
                                    <input  class="form-control" id="text-vehicle-edit" name="text-vehicle" value="" readonly required/>
                                </div>
                                <input type="hidden" name="id_fleet" id="id-fleet-edit">
                            </div>
                            <div class="form-group">
                                <label for="needed_date" class="form-control-label">Date Needed</label>
                                <input type="text" name="needed_date" class="form-control form-datepicker" id="needed-date-edit"/>
                            </div>
                            <div class="form-group">
                                <label for="completed_date" class="form-control-label">Date Completed</label>
                                <input type="text" name="completed_date" class="form-control form-datepicker" id="completed-date-edit"/>
                            </div>
                            <div class="form-group">
                                <label for="engine_hours" class="form-control-label">Engine Hours</label>
                                <input type="number" name="engine_hours" class="form-control" id="engine-hours-edit"/>
                            </div>
                            <div class="form-group">
                                <label for="working" class="form-control-label">Work</label>
                                <select name="working" id="working-edit" class="form-control">
                                    <option value="in-house" selected>In-House</option>
                                    <option value="outsourced">Outsourced</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="notes" class="form-control-label">Notes</label>
                                <textarea name="notes" class="form-control" id="notes-edit"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="status" class="form-control-label">Status</label>
                                <select name="status" id="status-edit" class="form-control">
                                    <option>Unassigned</option>
                                    <option>In progress</option>
                                    <option>Scheduled</option>
                                    <option>Completed</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id-edit">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal edit service  -->
@endif
<!--start: Modal select employee -->
<div class="modal fade" id="modal_select_employee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" >
                <div class="container">
                    <!--begin: Datatable -->
                    <table class="table-bordered table-hover table-data-custom" id="kt_table_employee_selected">
                        <thead>
                            <tr>
                                <th>Full name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                    </table>
                    <!--end: Datatable -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!--end: Modal select employee -->

<!--start: Modal select fleet -->
<div class="modal fade" id="modal_select_fleet" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select vehicle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" >
                <div class="container">
                    <!--begin: Datatable -->
                    <table class="table-bordered table-hover table-data-custom" id="kt_table_fleet_selected">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Model</th>
                                <th>Licence plate</th>
                                <th>Year</th>
                                <th>Yard location</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                    </table>
                    <!--end: Datatable -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!--end: Modal select fleet -->
@if(SICAPHelper::checkPermission(11, 3))
<!--start: Modal Delete Service -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('service_delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <div id="container-ids-delete"></div>
                <div class="modal-body">
                    <h1 class="text-uppercase text-center">  <i class="flaticon-danger text-danger display-1"></i> <br> You really want to delete the selected items?</h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal Delete Service -->

<!--start: Modal Delete Service -->
<div class="modal fade" id="modal_delete_element" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                    <form action="{{route('service_delete')}}"  method="POST" autocomplete="off">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="id[]" value="" id="id_delete">
                        <div class="modal-body">
                            <h1 class="text-uppercase text-center"><i class="flaticon-danger text-danger display-3"></i> <br> really want delete?</h1>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end: Modal Delete Service -->
@endif
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
@endsection

@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-datepicker/init.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-fleet-manager-services.js" type="text/javascript"></script>
@endsection

