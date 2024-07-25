@extends("$theme/layout")
@section('title') WilcoERP - Settings @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
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
        <h1 class="color-black">Settings</h1>
        @if(SICAPHelper::checkPermission(8, 2))
        <div class="dropdown dropdown-inline">
            <button type="button" class="btn btn-brand  btn-elevate btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="la la-plus"></i> Add
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <ul class="kt-nav">
                    <!--<li class="kt-nav__section kt-nav__section--first">
                        <span class="kt-nav__section-text">Choose an option</span>
                    </li>-->
                    @if(SICAPHelper::checkPermission(8, 2))
                    <li class="kt-nav__item">
                        <a href="#" onClick="addFleetCustom()" class="kt-nav__link" data-toggle="modal" data-target="#modal_edit_fleetfield">
                            <span class="kt-nav__link-text">Add Fleet Custom</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        @endif
    </div>
    <div class="col-12">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                @if(SICAPHelper::checkRol('super_admin'))
                <button onclick="setTab('nav-permission')" class="nav-link active" id="nav-permission-tab" data-toggle="tab" data-target="#nav-permission" type="button" role="tab" aria-controls="nav-permission" aria-selected="true">Permission</button>
                @endif
                @if(SICAPHelper::checkPermission(8))
                <button onclick="setTab('nav-fleetcustom')" class="nav-link" id="nav-fleetcustom-tab" data-toggle="tab" data-target="#nav-fleetcustom" type="button" role="tab" aria-controls="nav-fleetcustom" aria-selected="true">Vehicle Customs</button>
                @endif
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            @if(SICAPHelper::checkRol('super_admin'))
            <div class="tab-pane fade show active" id="nav-permission" role="tabpanel" aria-labelledby="nav-permission-tab">
                <div class="kt-portlet__body">
                    <div class="container">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <label for="role" class="align-content-center mr-2" style="margin: 0;">Role</label>
                                <select name="role" id="filter_role" class="form-control">
                                @foreach($roles as $role)    
                                    <option value="{{$role->id}}">{{$role->display_name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="savePermission();">Save</button>
                        </div>
                        <div class="table-wrapper mt-4">
                            <!--begin: Datatable -->
                            <table class="table-bordered table-hover table-data-custom" id="kt_permission_table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Module</th>
                                        <th>Read Access</th>
                                        <th>Write Access</th>
                                        <th>Create Access</th>
                                        <th>Delete Access</th>
                                    </tr>
                                </thead>
                            </table>
                            <!--end: Datatable -->
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if(SICAPHelper::checkPermission(8))
            <div class="tab-pane fade" id="nav-fleetcustom" role="tabpanel" aria-labelledby="nav-fleetcustom-tab">
                <div class="kt-portlet__body">
                    <div class="container">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="kt_fleetcustom_table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Data Type</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if(SICAPHelper::checkPermission(8, 1)||SICAPHelper::checkPermission(8, 2))
<!--start: Modal add element -->
<div class="modal fade" id="modal_edit_fleetfield" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelFleetfield">Add New Fleet Custom</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="form_edit_fleetfield" action="{{route('fleet_custom_field_save')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" id="edit_fleetcustom_id" name="id" value="-1"/>
                            <div class="form-group">
                                <label for="title" class="form-control-label">Title <font style="color:red;">*</font></label>
                                <input type="text" name="title" class="form-control" id="edit_fleetcustom_title" required/>
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-control-label">Data type</label>
                                <select name="type" class="form-control" id="edit_fleetcustom_type">
                                    <option value="string">Text</option>
                                    <option value="integer">Number without Decimals</option>
                                    <option value="double">Number with Decimals</option>
                                    <option value="boolean">Yes/No</option>
                                    <option value="date">Date</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="">Status</label>
                                <div class="kt-radio-inline d-flex justify-content-center p-2">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" id="edit_fleetcustom_status_true" value="true" checked> Enabled
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" id="edit_fleetcustom_status_false" value="false"> Disabled
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal add element -->
@endif
@if(SICAPHelper::checkPermission(8, 3))
<!--start: Modal Delete Rol -->
<div class="modal fade" id="modal_delete_fleetfield" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('fleet_custom_field_delete')}}"  method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <input type="hidden" name="id[]" value="" id="id_delete">
                <div class="modal-body">
                    <h1 class="text-uppercase text-center">  <i class="flaticon-danger text-danger display-3"></i> <br> really want delete?</h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-setting.js" type="text/javascript"></script>
@endsection

