@extends("$theme/layout")
@section('title') WilcoERP - Services @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css"/>
<style>
.no-padding {
  padding: 0!important;
  position: relative;
}
.no-padding .input-wrapper{
  position:absolute;
  left:0;
  top:0;
  width:100%;
  height:100%;
}
.no-padding input{
  width:100%;
  height:100%;
  border:none;
  color: #474747;
  background: white !important;
}
tr:hover .no-padding input{
  width:100%;
  height:100%;
  border:none;
  color: white;
  background: #161634 !important;
}
</style>
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
  <div class="col-6">
    <h1 class="color-black">Service Detail</h1>
  </div>
  <div class="col-6">
  </div>
</div>

<div class="row my-2">
  <div class="col-6 d-flex justify-content-start align-items-center">
    <button type="button" onclick="goBack()" class="btn btn-outline-light color-black">
      <i class="icon-2x color-primary flaticon2-back"></i> Back
    </button>
  </div>
  <div class="col-6 d-flex justify-content-end align-items-center">
  </div>
</div>

<div class="card p-5">
  <h3 class="color-black font-weight-normal">{{ $service->description }}</h3>
  <br>
  <table>
    <tr>
      <td>
        <span class="font-weight-bold color-black">Description</span>
        <p class="color-black">{{ $service->description }}</p>
      </td>
      <td>
        <span class="font-weight-bold color-black">Vehicle</span>
        <div class="row">
          <div class="col-1 d-flex align-items-center justify-content-center">
            <a href='/fleet_detail/{{$fleet->id}}'>
              <img src="/storage/images/fleet/{{$fleet->picture}}" width="30">
            </a>
          </div>
          <div class="col-11">
            <a href='/fleet_detail/{{$fleet->id}}'>
              <span class="font-weight-bold color-black">N°: {{ $fleet->n }}</span>
              <p class="color-black"> {{ $fleet->model }}</p>
            </a>
          </div>
        </div>
      </td>
      <td style="float: inline-start;">
        <span class="font-weight-bold color-black">Date Needed</span>
        <p class="color-black">{{ SICAPHelper::formatDate($service->needed_date) }}</p>
      </td>
    </tr>
    <tr>
      <td>
        <span class="font-weight-bold color-black">Service Type</span>
        <p class="color-black">
          {{ $service->type=='corrective'?'Corrective':($service->type=='preventive'?'Preventive':'') }}
          {{' / '}}
          {{ $service->working=='in-house'?'In-House':($service->working=='outsourced'?'Outsourced':'') }}
        </p>
      </td>
      <td>
        <span class="font-weight-bold color-black">Driver</span>
        <p class="color-black">{{$driver->name}} {{$driver->last_name}}</p>
      </td>
      <td style="float: inline-start;">
        <span class="font-weight-bold color-black">Date Completed</span>
        <p class="color-black">{{SICAPHelper::formatDate($service->completed_date)}} </p>
      </td>
    </tr>
    <tr>
      <td>
        <span class="font-weight-bold color-black">Date Requested</span>
        <p class="color-black">{{ SICAPHelper::formatDate($service->created_at) }}</p>
      </td>
      <td>
        <span class="font-weight-bold color-black">Odometer value</span>
        <p class="color-black">{{$odometer}}</p>
      </td>
      <td>
        <span class="font-weight-bold color-black">Total Hours</span>
        <p class="color-black">{{$hours}}</p>
      </td>
    </tr>
    <tr>
      <td>
        @if(SICAPHelper::checkPermission(14))
        <span class="font-weight-bold color-black">Cost</span>
        <p class="color-black">$ {{$cost}}</p>
        @endif
      </td>
      <td>
        <span class="font-weight-bold color-black">Status</span>
        <p class="color-black">{{$service->status}}</p>
      </td>
      <td>
        <span class="font-weight-bold color-black">Engine Hours</span>
        <p class="color-black">{{$service->engine_hours}}</p>
      </td>
    </tr>
  </table>
  <h3 class="color-black font-weight-normal">Records</h3>
  <br>
  <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          {{-- <i class="kt-font-brand flaticon-map"></i> --}}
        </span>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-wrapper">
          <div class="kt-portlet__head-actions">
            @if(SICAPHelper::checkPermission(12, 3))
            <div class="dropdown dropdown-inline">
              <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="la la-download"></i> Actions
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <ul class="kt-nav">
                  <li class="kt-nav__section kt-nav__section--first">
                      <span class="kt-nav__section-text">Choose an option</span>
                  </li>
                  @if(SICAPHelper::checkPermission(12, 3))
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
            @if(SICAPHelper::checkPermission(12, 2))
            &nbsp;
            <button  class="btn btn-brand btn-elevate btn-icon-sm" onclick="openModalAdd()">
                <i class="la la-plus"></i>
                Add record
            </button>
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <div class="container">
        <!--begin: Datatable -->
        <table class="table table-wilcoerp" id="kt_table_records">
          <thead>
            <tr>
              <th class="clean-icon-table">
                  <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                      <input type="checkbox" name="select_all" value="1" id="select-all">
                      <span></span>
                  </label>
              </th>
              <th>Category</th>
              <th>Date</th>
              <th>Mechanic</th>
              <th>Vehicle Part</th>
              <th>Hours spent</th>
              <th>Files</th>
              @if(SICAPHelper::checkPermission(14))
              <th>Cost</th>
              <th>Total Cost</th>
              @endif
              <th>Actions</th>
            </tr>
          </thead>
        </table>
        <!--end: Datatable -->
      </div>
    </div>
  </div>
</div>

@if(SICAPHelper::checkPermission(12, 2))
<!--start: Modal add employee -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('service_update')}}" id="form-add"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Category <font style="color:red;">*</font></label>
                <select class="form-control" name="id_category" id="id-category" required>
                  <option value="">Select a category</option>
                  @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="hour_spend" class="form-control-label">Hours spent <font style="color:red;">*</font></label>
                <input type="text" name="hour_spend" class="form-control" id="hour_spend" value="{{old('hour_spend')}}" required/>
              </div>
              @if(SICAPHelper::checkPermission(14))
              <div class="form-group">
                <label for="cost" class="form-control-label">Cost <font style="color:red;">*</font></label>
                <input type="text" name="cost" class="form-control" id="cost" value="{{old('cost')}}" required/>
              </div>
              @endif
              <div class="form-group">
                <label>Mechanic <font style="color:red;">*</font></label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectEmployee('add')">Select mechanic</button>
                  </div>
                  <input class="form-control" id="text-mechanic" name="text-mechanic" readonly required/>
                </div>
                <input type="hidden" name="id_employee" id="id-mechanic"/>
              </div>
              <div class="form-group">
                <label>Vehicle Parts</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                  <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectTool('add')">Select part</button>
                  </div>
                  <input  class="form-control" id="text-tool" name="text-tool" readonly/>
                </div>
                <input type="hidden" name="id_tool" id="id-tool"/>
                <input type="hidden" id="price-tool" name="price-tool"/>
                <input type="hidden" id="quantity-tool" name="quantity-tool"/>
              </div>
              @if(SICAPHelper::checkPermission(14))
              <div class="form-group">
                <label for="cost" class="form-control-label">Total Cost</label>
                <input type="text" class="form-control" id="total-cost" readonly/>
              </div>
              @endif
              <div class="form-group">
                <label for="description" class="form-control-label">Note</label>
                <textarea name="description" class="form-control" id="description">{{old('description')}}</textarea>
              </div>
              <div class="mt-3">
                <label for="img-change"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                  <div class="btn btn-primary">Attach file</div>          
                </label>
                <input type='file' id="img-change" style="display:none" name="picture_upload"/>
                <br>
              </div>
              <div id="container-files-add">
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
<!--end: Modal add employee -->
@endif
@if(SICAPHelper::checkPermission(12, 1))
<!--start: Modal edit record -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">Edit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          </button>
      </div>
      <form action="/service_record_update" id="form-edit"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
        @csrf
        @method('post')
        
        <div class="modal-body" >
          <div class="row">
            <div class="col-12">
              <input type="hidden" value="" id="id-edit">
              <div class="form-group">
                <label>Category <font style="color:red;">*</font></label>
                <select class="form-control" name="id_category" id="id-category-edit" required>
                  <option value="">Select a category</option>
                  @foreach ($categories as $category)
                  <option value="{{$category->id}}">{{$category->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="hour_spend" class="form-control-label">Hours spent <font style="color:red;">*</font></label>
                <input type="text" name="hour_spend" class="form-control" id="hour_spend-edit" value="{{old('hour_spend')}}" required/>
              </div>
              @if(SICAPHelper::checkPermission(14))
              <div class="form-group">
                <label for="cost" class="form-control-label">Cost <font style="color:red;">*</font></label>
                <input type="text" name="cost" class="form-control" id="cost-edit" value="{{old('cost')}}" required/>
              </div>
              @endif
              <div class="form-group">
                <label>Mechanic <font style="color:red;">*</font></label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectEmployee('edit')">Select mechanic</button>
                  </div>
                  <input class="form-control" id="text-mechanic-edit" name="text-mechanic-edit" readonly required/>
                </div>
                <input type="hidden" name="id_employee" id="id-mechanic-edit"/>
              </div>
              <div class="form-group">
                <label>Vehicle Parts</label>
                <div class="input-group mb-3">
                  <!-- <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectTool('edit')">Select part</button>
                  </div> -->
                  <input class="form-control" id="text-tool-edit" name="text-tool" readonly/>
                </div>
                <input type="hidden" name="id_tool" id="id-tool-edit"/>
                <input type="hidden" id="price-tool-edit" name="price-tool-edit"/>
                <input type="hidden" id="quantity-tool-edit" name="quantity-tool-edit"/>
              </div>
              @if(SICAPHelper::checkPermission(14))
              <div class="form-group">
                <label for="cost" class="form-control-label">Total Cost</label>
                <input type="text" class="form-control" id="total-cost-edit" readonly/>
              </div>
              @endif
              <div class="form-group">
                  <label for="description" class="form-control-label">Note</label>
                  <textarea name="description" class="form-control" id="description-edit">{{old('description')}}</textarea>
              </div>
              <div class="mt-3">
                <label for="img-change-edit"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                  <div class="btn btn-primary">Attach file</div>          
                </label>
                <input type='file' id="img-change-edit" style="display:none" name="picture_upload"/>
                <br>
                {{-- <small>Clic sobre la imagen para cambiar</small> --}}
              </div>
              <div id="container-files-edit"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--end: Modal edit record -->
@endif
<!--start: Modal select employee -->
<div class="modal fade" id="modal_select_employee" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select driver</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="OpenPrevModel();"></button>
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

<!--start: Modal select tool -->
<div class="modal fade" id="modal_select_tool" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select vehicle part</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="OpenPrevModel();"></button>
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
                <th>Qunantity</th>
                <th>Select</th>
              </tr>
            </thead>
          </table>
          <!--end: Datatable -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="setToolForm()">Select</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal select tool -->
@if(SICAPHelper::checkPermission(12, 3))
<!--start: Modal Delete Employees -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('record_delete')}}" id="form_delete" method="POST" autocomplete="off">
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
<!--end: Modal Delete Employees -->

<!--start: Modal Delete Rol -->
<div class="modal fade" id="modal_delete_element" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('record_delete')}}"  method="POST" autocomplete="off">
        @csrf
        @method('delete')
        <input type="hidden" name="id[]" value="" id="id_delete">
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
<!--start: Modal show files -->
<div class="modal fade" id="modal_show_files" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Files</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="container-modal-show-files" class="modal-body text-center"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal show files  -->

<input type="hidden" id="id_service" value="{{$service->id}}">
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
@endsection
@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/select2/dist/js/select2.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script>
jQuery.fn.ForceNumericOnly = function(){
  return this.each(function(){
    $(this).keydown(function(e)
    {
      var key = e.charCode || e.keyCode || 0;
      // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
      // home, end, period, and numpad decimal
      return (
          key == 8 || 
          key == 9 ||
          key == 13 ||
          key == 46 ||
          key == 110 ||
          key == 190 ||
          (key >= 35 && key <= 40) ||
          (key >= 48 && key <= 57) ||
          (key >= 96 && key <= 105));
    });
  });
};

$("#hour_spend").ForceNumericOnly();
$("#cost").ForceNumericOnly();
$("#hour_spend-edit").ForceNumericOnly();
$("#cost-edit").ForceNumericOnly();
</script>
<script src="{{asset("assets")}}/js/page-fleet-manager-service-detail.js" type="text/javascript"></script>
@endsection

