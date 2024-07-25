@extends("$theme/layout")
@section('title') WilcoERP - Vehicles @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css"/>
<style>
.iti--allow-dropdown{
    display: block !important;
}
</style>
@endsection
@section('styles_optional_vendors')
@endsection
@section('content_breadcrumbs') 
{!! SICAPHelper::getBreadCrumbs([
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
                {{$type_fleet=='trucks_cars'?"Vehicles":($type_fleet=='trailers'?"Trailers":"Equipments")}}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions d-flex">
                    <div class="d-flex align-items-center mr-4">
                        <label for="filter_location" class="form-control-label mb-0 mr-2">Yard</label>
                        <select name="filter_location" class="form-control" style="border-radius:0px;" id="filter_location">
                            <option class="text-capitalize" value="">ALL</option>
                            @foreach ($locations as $location)
                                <option class="text-capitalize" value="{{ $location }}">{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center mr-4">
                        <label for="filter_department" class="form-control-label mb-0 mr-2">Department</label>
                        <select name="filter_department" class="form-control" style="border-radius:0px;" id="filter_department">
                            <option class="text-capitalize" value="">ALL</option>
                            @foreach ($departments as $department)
                                <option class="text-capitalize" value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center mr-4">
                        <label for="filter_status" class="form-control-label mb-0 mr-2">Status</label>
                        <select name="filter_status" class="form-control" style="border-radius:0px;" id="filter_status">
                            <option class="text-capitalize" value="">ALL</option>
                            @foreach ($statuses as $status)
                                <option class="text-capitalize" value="{{ $status }}">{{ 
                                    $status=='true'? "Available":(
                                        $status=='in-service'? "In Service":(
                                            $status=='check-out'? "In-Use":"Out of Service"
                                        )
                                    )
                                }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(SICAPHelper::checkPermission(3, 2)||SICAPHelper::checkPermission(3, 3))
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i> Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__section kt-nav__section--first">
                                    <span class="kt-nav__section-text">Choose an option</span>
                                </li>
                                @if(SICAPHelper::checkPermission(3, 2))
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_element">
                                        <span class="kt-nav__link-text">Add new</span>
                                    </a>
                                </li>
                                @endif
                                @if(SICAPHelper::checkPermission(3, 3))
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
                    @if(SICAPHelper::checkPermission(3, 2))
                    &nbsp;
                    <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add_element">
                        <i class="la la-plus"></i>
                        Add
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="container">
            <!--begin: Datatable -->
            <table class="table-bordered table-hover table-data-custom" id="kt_table_1">
                <thead>
                    <tr>
                        <th class="clean-icon-table">
                            <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                                <input type="checkbox" name="select_all" value="1" id="select-all">
                                <span></span>
                            </label>
                        </th>
                        <th>ID</th>
                        <th>Model</th>
                        <th>Licence plate</th>
                        <th>Year</th>
                        <th>Yard location</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>

@if(SICAPHelper::checkPermission(3, 2))
<!--start: Modal add element -->
<div class="modal fade" id="modal_add_element" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('fleet_insert')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <label for="img-change"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                                <img id="img-change-profile" class="picture-upload" src="{{ asset("assets/images/upload_picture.png") }}" />                 
                            </label>
                            <input type='file' id="img-change" style="display:none" name="picture_upload" accept="image/*"/>
                            <br>
                            {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="n" class="form-control-label">Type</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="trucks_cars" {{$type_fleet=="trucks_cars"?"selected":""}}>Vehicles</option>
                                    <option value="trailers" {{$type_fleet=="trailers"?"selected":""}}>Trailers</option>
                                    <option value="equipment" {{$type_fleet=="equipment"?"selected":""}}>Equipment</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="n" class="form-control-label">ID <font style="color:red;">*</font></label>
                                <input type="text" name="n" class="form-control" id="n" value="{{old('n')}}" required/>
                            </div>
                            <div class="form-group">
                                    <label for="model" class="form-control-label">Model <font style="color:red;">*</font></label>
                                    <input type="text" name="model" class="form-control" id="model" value="{{old('model')}}"/>
                            </div>
                            <div class="form-group">
                                    <label for="licence_plate" class="form-control-label">Licence plate <font style="color:red;">*</font></label>
                                    <input type="text" name="licence_plate" class="form-control" id="licence_plate" value="{{old('licence_plate')}}"/>
                            </div>
                            <div class="form-group">
                                <label for="year" class="form-control-label">Year <font style="color:red;">*</font></label>
                                <input type="text" name="year" class="form-control" id="year" value="{{old('year')}}"/>
                            </div>
                            <div class="form-group">
                                <label for="yard_location" class="form-control-label">Yard location <font style="color:red;">*</font></label>
                                <select class="select2 form-control" name="yard_location" id="yard_location"></select>
                            </div>
                            <div class="form-group">
                                <label for="department" class="form-control-label">Department <font style="color:red;">*</font></label>
                                <select class="select2 form-control" name="department" id="department"></select>
                            </div>
                            <div class="form-group">
                                <label class="">Status</label>
                                <div class="kt-radio-inline d-flex justify-content-center p-2">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" id="status-edit-true" value="true" {{old('status')? (old('status')=='true')? 'checked' :'' : 'checked'   }}> Enabled
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" id="status-edit-false" value="false" {{old('status')? (old('status')=='false')? 'checked' :'' : ''   }}> Disabled
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal add element -->
@endif

@if(SICAPHelper::checkPermission(3, 1))
<!--start: Modal edit element -->
<div class="modal fade" id="modal_edit_element" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('fleet_update')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <label for="img-change-edit"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                                <img id="img-change-fleet-edit" class="picture-upload" src="{{ asset("assets/images/upload_picture.png") }}" />                 
                            </label>
                            <input type='file' id="img-change-edit" style="display:none" name="picture_upload" accept="image/*"/>
                            <br>
                            {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="n" class="form-control-label">Type</label>
                                <select class="form-control" name="type" id="type_edit">
                                    <option value="trucks_cars" {{$type_fleet=="trucks_cars"?"selected":""}}>Vehicles</option>
                                    <option value="trailers" {{$type_fleet=="trailers"?"selected":""}}>Trailers</option>
                                    <option value="equipment" {{$type_fleet=="equipment"?"selected":""}}>Equipment</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="n" class="form-control-label">ID <font style="color:red;">*</font></label>
                                <input type="text" name="n" class="form-control" id="n_edit" required/>
                            </div>
                            <div class="form-group">
                                    <label for="model" class="form-control-label">Model <font style="color:red;">*</font></label>
                                    <input type="text" name="model" class="form-control" id="model_edit"/>
                            </div>
                            <div class="form-group">
                                    <label for="licence_plate" class="form-control-label">Licence plate <font style="color:red;">*</font></label>
                                    <input type="text" name="licence_plate" class="form-control" id="licence_plate_edit"/>
                            </div>
                            <div class="form-group">
                                <label for="year" class="form-control-label">Year <font style="color:red;">*</font></label>
                                <input type="text" name="year" class="form-control" id="year_edit" value="{{old('year')}}"/>
                            </div>
                            <div class="form-group">
                                <label for="yard_location" class="form-control-label">Yard location <font style="color:red;">*</font></label>
                                <input type="hidden" name="yard_location" id="yard_location_edit_hidden"/>
                                <select class="select2 form-control" name="current_yard_location" id="yard_location_edit"></select>
                            </div>
                            <div class="form-group">
                                <label for="department" class="form-control-label">Department <font style="color:red;">*</font></label>
                                <select class="select2 form-control" name="department" id="department_edit"></select>
                            </div>
                            <input type="hidden" name="id" id="id-edit">
                            <div class="form-group">
                                <label class="">Status</label>
                                <div class="kt-radio-inline d-flex justify-content-center p-2">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" value="true" {{old('status')? (old('status')=='true')? 'checked' :'' : 'checked'   }}> Enabled
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" value="false" {{old('status')? (old('status')=='false')? 'checked' :'' : ''   }}> Disabled
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal edit element -->
@endif

@if(SICAPHelper::checkPermission(3, 3))
<!--start: Modal Delete Employees -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('fleet_delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <div id="container-ids-delete">

                </div>
                <input type="hidden" name="type" value="trucks_cars">
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
<!--end: Modal Delete Employees -->

<!--start: Modal Delete Rol -->
<div class="modal fade" id="modal_delete_element" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('fleet_delete')}}"  method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <input type="hidden" name="id[]" value="" id="id_delete">
                <input type="hidden" name="type" value="trucks_cars">
                <div class="modal-body">
                    <h1 class="text-uppercase text-center">  <i class="flaticon-danger text-danger display-3"></i> <br> really want delete?</h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal Delete Rol -->
@endif
<input type="hidden" id="type_fleet" value="{{ $type_fleet }}">
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
<!-- end:: Content -->
@endsection
@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/select2/dist/js/select2.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-fleet.js" type="text/javascript"></script>
@endsection