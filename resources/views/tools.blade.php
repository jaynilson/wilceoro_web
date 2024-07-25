@extends("$theme/layout")
@section('title') WilcoERP - Tools @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />
<style>
.iti--allow-dropdown{
    display: block !important;
}
.form-group {
    input[type="checkbox"].form-control{
        width: 24px;
        height: 24px;
    }
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
                Inventories
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions d-flex">
                    <div class="d-flex align-items-center mr-4">
                        <label for="stock" class="form-control-label mb-0 mr-2">Type</label>
                        <select name="filter_type" class="form-control" style="border-radius:0px;" id="filter_type">
                            <option class="text-capitalize" value="">ALL</option>
                            <option class="text-capitalize" value="ppe">PPE</option>
                            <option class="text-capitalize" value="consumables">CONSUMABLES</option>
                        </select>
                    </div>
                    @if(SICAPHelper::checkPermission(13, 2)||SICAPHelper::checkPermission(13, 3))
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
                                @if(SICAPHelper::checkPermission(13, 2))
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_element">
                                        <span class="kt-nav__link-text">Add new</span>
                                    </a>
                                </li>
                                @endif
                                @if(SICAPHelper::checkPermission(13, 3))
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
                    @if(SICAPHelper::checkPermission(13, 2))
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
                        <th>Title/name</th>
                        @if(SICAPHelper::checkPermission(14))
                        <th>Price</th>
                        @endif
                        <th>Available Stock</th>
                        <th>Stock in use</th>
                        <th>Type</th>
                        <th>Required Return</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>

@if(SICAPHelper::checkPermission(13, 2))
<!--start: Modal add element -->
<div class="modal fade" id="modal_add_element" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="/tool/insert/{{$department}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <label for="img-change"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                                <img id="img-change-profile" class="picture-upload" src="{{ asset("assets/images/upload_picture.png") }}"  />                 
                            </label>
                            <input type='file' id="img-change" style="display:none" name="picture_upload" accept="image/*"/>
                            <br>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                    <label for="title" class="form-control-label">Title/name <font style="color:red;">*</font></label>
                                    <input type="text" name="title" class="form-control" value="{{old('title')}}" id="title" required/>
                            </div>
                            @if(SICAPHelper::checkPermission(14))
                            <div class="form-group">
                                <label for="stock" class="form-control-label">Price <font style="color:red;">*</font></label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{old('price')}}" id="price" required/>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="stock" class="form-control-label">Stock <font style="color:red;">*</font></label>
                                <input type="number" name="stock" class="form-control" value="{{old('stock')}}" id="stock" required/>
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-control-label">Type <font style="color:red;">*</font></label>
                                <select name="type" class="form-control " id="type" required>
                                    <option class="text-capitalize" value="" {{old('type')?'':'selected'}}  disabled>* Select type</option>
                                    <option class="text-capitalize" value="ppe" {{old('type')? ("ppe"==old('type'))? 'selected':'' :''}}>PPE</option>
                                    <option class="text-capitalize" value="consumables" {{old('type')? ("consumables"==old('type'))? 'selected':'' :''}}>CONSUMABLES</option>
                                </select>
                            </div>
                            <div class="form-group d-flex mt-4">
                                <label for="required_return" class="form-control-label" style="width: 150px;">Reqired Return</label>
                                <input type="checkbox" name="required_return" class="form-control" {{old('required_return')?'checked':''}} id="required_return"/>
                            </div>
                            <div class="form-group d-flex mt-4">
                                <label for="status" class="form-control-label" style="width: 150px;">Status</label>
                                <div class="kt-radio-inline d-flex justify-content-center">
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
@if(SICAPHelper::checkPermission(13, 1))
<!--start: Modal edit element -->
<div class="modal fade" id="modal_edit_element" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="/tool/update/{{$department}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <label for="img-change-edit"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                                <img id="img-change-element-edit" class="picture-upload" src="{{ asset("assets/images/upload_picture.png") }}"  />                 
                            </label>
                            <input type='file' id="img-change-edit" style="display:none" name="picture_upload" accept="image/*"/>
                            <br>
                            {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                    <label for="title" class="form-control-label">Title/name <font style="color:red;">*</font></label>
                                    <input type="text" name="title" class="form-control" id="title_edit" value="{{old('title')}}" required/>
                            </div>
                            @if(SICAPHelper::checkPermission(14))
                            <div class="form-group">
                                <label for="stock" class="form-control-label">Price <font style="color:red;">*</font></label>
                                <input type="number" step="0.01" name="price" class="form-control" id="price_edit" value="{{old('price')}}" required/>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="stock" class="form-control-label">Stock <font style="color:red;">*</font></label>
                                <input type="number" name="stock" class="form-control" id="stock_edit" value="{{old('stock')}}" required/>
                            </div>
                            <div class="form-group">
                                <label for="type" class="form-control-label">Type <font style="color:red;">*</font></label>
                                <select name="type" class="form-control " id="type_edit" required>
                                    <option class="text-capitalize" value="" {{old('type')?'':'selected'}}  disabled>* Select type</option>
                                    <option class="text-capitalize" value="ppe" {{old('type')? ("ppe"==old('type'))? 'selected':'' :''}}>PPE</option>
                                    <option class="text-capitalize" value="consumables" {{old('type')? ("consumables"==old('type'))? 'selected':'' :''}}>CONSUMABLES</option>
                                </select>
                            </div>
                            <div class="form-group d-flex mt-4">
                                <label for="required_return" class="form-control-label" style="width: 150px;">Reqired Return</label>
                                <input type="checkbox" name="required_return" class="form-control" {{old('required_return')?'checked':''}} id="required_return-edit"/>
                            </div>
                            <input type="hidden" name="id" id="id-edit">
                            <div class="form-group d-flex mt-4">
                                <label for="status" class="form-control-label" style="width: 150px;">Status</label>
                                <div class="kt-radio-inline d-flex justify-content-center">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="status" value="true" {{old('status')? (old('status')=='true')? 'checked' :'' : 'checked'}}>Enabled
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
@if(SICAPHelper::checkPermission(13, 3))
<!--start: Modal Delete Employees -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="/tool/delete/{{$department}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <div id="container-ids-delete"></div>
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
            <form action="/tool/delete/{{$department}}"  method="POST" autocomplete="off">
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

<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
<input type="hidden" name="_department" id="_department" value="{{ $department }}">
<!-- end:: Content -->
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
<script src="{{asset("assets")}}/js/page-tools.js" type="text/javascript"></script>
<script>
    function onlyNumberKey(evt) {
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
</script>
@endsection

