@extends("$theme/layout")
@section('title') WilcoERP - Request Categories @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />
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
                Request Categories
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    @if(SICAPHelper::checkPermission(10, 2)||SICAPHelper::checkPermission(10, 3))
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i> Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__section kt-nav__section--first">
                                    <span class="kt-nav__section-text">Choose an option</span>
                                </li>
                                @if(SICAPHelper::checkPermission(10, 2))
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_element">
                                        <span class="kt-nav__link-text">Add new</span>
                                    </a>
                                </li>
                                @endif
                                @if(SICAPHelper::checkPermission(10, 3))
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
                    @if(SICAPHelper::checkPermission(10, 2))
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
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>
@if(SICAPHelper::checkPermission(10, 2))
<!--start: Modal add element -->
<div class="modal fade" id="modal_add_element" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('request_category_insert')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title" class="form-control-label">Type <font style="color:red;">*</font></label>
                                <select name="type" class="form-control " id="type" required>
                                    <option class="text-capitalize" value="" {{old('type')?'':'selected'}}  disabled>* Select type</option>
                                    <option class="text-capitalize" value="maintenance" {{old('type')? ("maintenance"==old('type'))? 'selected':'' :''}}>Maintenance</option>
                                    <option class="text-capitalize" value="repair" {{old('type')? ("repair"==old('type'))? 'selected':'' :''}}>Repair</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title" class="form-control-label">Title <font style="color:red;">*</font></label>
                                <input type="text" name="title" class="form-control" id="title" value="{{old('title')}}" />
                            </div>
                            <br>
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
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal add element -->
@endif
@if(SICAPHelper::checkPermission(10, 1))
<!--start: Modal edit element -->
<div class="modal fade" id="modal_edit_element" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('request_category_update')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title" class="form-control-label">Type <font style="color:red;">*</font></label>
                                <select name="type" class="form-control " id="type_edit" required>
                                    <option class="text-capitalize" value="" {{old('type')?'':'selected'}}  disabled>* Select type</option>
                                    <option class="text-capitalize" value="maintenance" {{old('type')? ("maintenance"==old('type'))? 'selected':'' :''}}>Maintenance</option>
                                    <option class="text-capitalize" value="repair" {{old('type')? ("repair"==old('type'))? 'selected':'' :''}}>Repair</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title" class="form-control-label">Title <font style="color:red;">*</font></label>
                                <input type="text" name="title" class="form-control" id="title_edit" value="{{old('title')}}" />
                            </div>
                            <br>
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
                    <input type="hidden" name="id" id="id-edit">
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
@if(SICAPHelper::checkPermission(10, 3))
<!--start: Modal Delete Employees -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('request_category_delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <div id="container-ids-delete"></div>
                <input type="hidden" name="type" value="trailers">
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
            <form action="{{route('request_category_delete')}}"  method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <input type="hidden" name="id[]" value="" id="id_delete">
                <input type="hidden" name="type" value="trailers">
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
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
@endsection
@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-request-categories.js" type="text/javascript"></script>
@endsection