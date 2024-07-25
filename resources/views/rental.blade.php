@extends("$theme/layout")
@section('title') WilcoERP - Rentals @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css"/>
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
                Rental
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions d-flex">
                    <div class="d-flex align-items-center mr-4">
                    </div>
                    @if(SICAPHelper::checkPermission(15, 2)||SICAPHelper::checkPermission(15, 3))
                    &nbsp;
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i> Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__section kt-nav__section--first">
                                    <span class="kt-nav__section-text">Choose an option</span>
                                </li>
                                @if(SICAPHelper::checkPermission(15, 2))
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" onClick="showRentalModal();">
                                        <span class="kt-nav__link-text">Add new</span>
                                    </a>
                                </li>
                                @endif
                                @if(SICAPHelper::checkPermission(15, 3))
                                <li class="kt-nav__item">
                                    <a href="#" onClick="deleteSelected()" id="btn-delete-employees" class="kt-nav__link">
                                        <span class="kt-nav__link-text">Delete selected</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endif
                    @if(SICAPHelper::checkPermission(15, 2))
                    &nbsp;
                    <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" onClick="showRentalModal();">
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
                        <th>Inventory</th>
                        <th>Rental Date</th>
                        <th>Needed Date</th>
                        <th>Required Return</th>
                        <th>Vendor Name</th>
                        <th>Employee</th>
                        <th>Notify</th>
                        <th>Note</th>
                        <th>Returned Picture</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>

@if(SICAPHelper::checkPermission(15, 1)||SICAPHelper::checkPermission(15, 2))
<!--start: Modal add element -->
<div class="modal fade" id="modal_rental" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="record">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="rental_form" action="{{route('rental_save')}}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input type="hidden" name="id" id="rental_id"/>
                <div class="modal-body" class="d-flex justify-content-center">
                    <div class="row">
                        <div class="col-6 form-group inventory-select-group">
                            <label for="text-tool">Inventory <font style="color:red;">*</font></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectTool()">Select a inventory</button>
                                </div>
                                <input class="form-control" id="text_tool" name="text-tool" readonly required/>
                            </div>
                            <input type="hidden" name="id_tool" id="rental_id_tool"/>
                            <input type="hidden" name="return_tool" id="rental_return_tool"/>
                        </div>
                        <div class="col-6 form-group return-date-group">
                            <label for="return_date" class="form-control-label">Return Date <font style="color:red;">*</font></label>
                            <input type="text" name="return_date" class="form-control form-datepicker" id="rental_return_date" value="{{old('return_date')}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group employee-select-group">
                            <label for="text-employee">Employee <font style="color:red;">*</font></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectEmployee()">Select a employee</button>
                                </div>
                                <input class="form-control" id="text_employee" name="text-employee" readonly required/>
                            </div>
                            <input type="hidden" name="id_employee" id="rental_id_employee"/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="vendor_name" class="form-control-label">Vendor Name</label>
                            <input type="text" name="vendor_name" class="form-control" id="rental_vendor_name" value="{{old('vendor_name')}}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label for="rental_date" class="form-control-label">Rental Date <font style="color:red;">*</font></label>
                            <input type="text" name="rental_date" class="form-control form-datepicker" id="rental_rental_date" value="{{old('rental_date')}}" required/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="needed_date" class="form-control-label">Needed Date <font style="color:red;">*</font></label>
                            <input type="text" name="needed_date" class="form-control form-datepicker" id="rental_needed_date" value="{{old('needed_date')}}" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note" class="form-control-label">Note</label>
                        <textarea type="text" name="note" class="form-control" id="rental_note">{{old('note')}}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label for="notify" class="form-control-label">Notify Days</label>
                            <input type="number" name="notify" class="form-control" id="rental_notify" value="{{old('notify')}}"/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="status" class="form-control-label">Status</label>
                            <select name="status" class="form-control" id="rental_status" disabled>
                                <option value="check-out">Check-Out</option>
                                <option value="check-in">Check-In</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 returned-picture-group">
                        <label for="rental_file_upload" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                        <div class="btn btn-primary">Attach Returned Picture</div>          
                        </label>
                        <input type='file' id="rental_file_upload" style="display:none" name="picture_upload"/>
                        <br>
                    </div>
                    <div id="container-files-add" class="container-files-flex-view"></div>
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
<!--start: Modal select tool -->
<div class="modal fade" id="modal_select_tool" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="select-tool">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select a inventory</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modal_rental').modal('show');"></button>
      </div>
      <div class="modal-body" >
        <div class="container">
          <!--begin: Datatable -->
          <table class="table-bordered table-hover table-data-custom" id="kt_table_tool_selected">
            <thead>
              <tr>
                <th>ID</th>
                <th>Title/name</th>
                @if(SICAPHelper::checkPermission(14))
                <th>Price</th>
                @endif
                <th>Stock</th>
                <th>Type</th>
                <th>Required Return</th>
                <th>Status</th>
                <th>Select</th>
              </tr>
            </thead>
          </table>
          <!--end: Datatable -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#modal_rental').modal('show');">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal select tool -->

<!--start: Modal select tool -->
<div class="modal fade" id="modal_select_employee" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="select-employee">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select manager</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modal_rental').modal('show');"></button>
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
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#modal_rental').modal('show');">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal select tool -->

<!--start: Modal show files -->
<div class="modal fade" id="modal_show_files" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Files</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="container-modal-show-files" class="modal-body text-center container-files-flex-view"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal show files  -->

@if(SICAPHelper::checkPermission(15, 3))
<!--start: Modal Delete Employees -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('rental_delete')}}" id="form_delete" method="POST" autocomplete="off">
        @csrf
        @method('delete')
        <div id="container-ids-delete"></div>
        <div class="modal-body">
          <h1 class="text-uppercase text-center"><i class="flaticon-danger text-danger display-1"></i> <br> You really want to delete the selected rentals?</h1>
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
@endif

<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
<!-- end:: Content -->
@endsection
@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-datepicker/init.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-rental.js" type="text/javascript"></script>
@endsection

