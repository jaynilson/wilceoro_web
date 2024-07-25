@extends("$theme/layout")
@section('title') WilcoERP - Usuarios @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />
@endsection
<link href="/css/user.scss" rel="stylesheet" type="text/css" />
@section('styles_optional_vendors')
@endsection
@section('content_breadcrumbs') 
{!! SICAPHelper::getBreadCrumbs([
]) !!}
@endsection
@section('content_page')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-map"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Users
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i> Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" style="min-width: 185px;">
                            <ul class="kt-nav">
                                <li class="kt-nav__section kt-nav__section--first">
                                    <span class="kt-nav__section-text">Choose an option</span>
                                </li>
                                @if(SICAPHelper::checkPermission(1, 2))
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_employee">
                                        <i class="kt-nav__link-icon fa fa-user-plus"></i>
                                        <span class="kt-nav__link-text">Add new</span>
                                    </a>
                                </li>
                                @endif
                                <li class="kt-nav__item">
                                    <a href="/export-users-excel" class="kt-nav__link">
                                        <i class="kt-nav__link-icon fa fa-download"></i>
                                        <span class="kt-nav__link-text">Export to Excel</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_users_import">
                                        <i class="kt-nav__link-icon fa fa-upload"></i>
                                        <span class="kt-nav__link-text">Import from Excel</span>
                                    </a>
                                </li>
                                @if(SICAPHelper::checkPermission(1, 3))
                                <li class="kt-nav__item">
                                    <a href="#" onclick="deleteSelected()" id="btn-delete-employees" class="kt-nav__link">
                                        <i class="kt-nav__link-icon fa fa-user-minus"></i>
                                        <span class="kt-nav__link-text">Delete selected</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @if(SICAPHelper::checkPermission(1, 2))
                    &nbsp;
                    <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#modal_add_employee">
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
                        <th>Full name</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Pin</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>

<!--start: Modal add employee -->
<div class="modal fade" id="modal_add_employee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('user_insert')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <label for="img-change"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                                <img id="img-change-profile" class="picture-profile" src="{{ asset("assets/images/user_default.png") }}"  />                 
                            </label>
                            <input type='file' id="img-change" style="display:none" name="picture_upload" accept="image/*"/>
                            <br>
                            {{-- <small>Clic sobre la imagen para cambiar</small> --}}
                        </div>
                        <div class="col-6 form-group">
                            <label for="name" class="form-control-label">Name <font style="color:red;">*</font></label>
                            <input type="text" name="name" class="form-control" id="recipient-name" value="{{old('name')}}"  autocomplete="new-password" required/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="last-name" class="form-control-label">Last name</label>
                            <input type="text" name="last_name" class="form-control" id="last-name" value="{{old('last_name')}}" />
                        </div>
                        <div class="col-6 form-group">
                            <label for="department" class="form-control-label">Department <font style="color:red;">*</font></label>
                            <input type="text" name="department" class="form-control" id="department" value="{{old('department')}}" required/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="yard_location" class="form-control-label">Yard Location</label>
                            <input type="text" name="yard_location" class="form-control" id="yard_location" value="{{old('yard_location')}}">
                        </div>
                        <div class="col-6 form-group">
                            <label for="id_rol">Rol <font style="color:red;">*</font></label>
                            <select name="id_rol" class="form-control" id="id_rol" required>
                                <option value="" style="text-transform: none !important;color: #99a3ac;" {{old('id_rol')?'':'selected'}}  disabled>Select a role</option>
                                @foreach ($roles as $role)
                                <option value="{{$role->id}}" {{old('id_rol')? ($key==old('id_rol'))? 'selected':'' :''}}>{{$role->display_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 form-group">
                            <label for="recipient-tel" class="form-control-label">CDL Upload</label>
                            <input type="file" name="cdl" class="form-control" id="cdl"/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="email" class="form-control-label">Email <font style="color:red;">*</font></label>
                            <input type="email" name="email" class="form-control" id="email" value="{{old('email')}}"  autocomplete="new-password" required/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="recipient-tel" class="form-control-label">Phone</label>
                            <input type="tel" name="tel" class="form-control" id="recipient-tel" value="{{old('tel')}}" maxlength="10" placeholder="xxxxxxxxxx" autocomplete="new-password">
                        </div>
                        <div class="col-12" id="part-password">
                            <div class="col-6 form-group">
                                <label for="recipient-password" class="form-control-label">Password <font style="color:red;">*</font></label>
                                <input type="password" name="password" class="form-control" id="password" value="{{old('password')}}" autocomplete="new-password"/>
                            </div>
                            <div class="col-6 form-group">
                                <label for="recipient-re-password" class="form-control-label">Confirm password <font style="color:red;">*</font></label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" value="{{old('password_confirmation')}}" autocomplete="new-password"/>
                            </div>
                        </div>
                        <div id="part-pin" class="col-6 form-group">
                            <label for="pin" class="form-control-label">Pin <font style="color:red;">*</font></label>
                            <input type="text" name="pin" class="form-control" id="pin" value="{{old('pin')}}" autocomplete="new-password"/>
                        </div>
                        <div class="col-6 form-group">
                            <label class="">Status</label>
                            <div class="kt-radio-inline d-flex justify-content-center p-2">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" value="enable" {{old('status')? (old('status')=='enable')? 'checked' :'' : 'checked'   }}> Enabled
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" value="disable" {{old('status')? (old('status')=='disable')? 'checked' :'' : ''   }}> Disabled
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-form-submit">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal add employee -->

<!--start: Modal edit employee -->
<div class="modal fade" id="modal_edit_employee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('user_update')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('put')
                
                <input type="hidden" id="id-edit" name="id"/>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <label for="img-change-edit"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                                <img id="img-change-profile-edit" class="picture-profile" src="{{ asset("assets/images/user_default.png") }}"  />                 
                            </label>
                            <input type='file' id="img-change-edit" style="display:none" name="picture_upload" accept="image/*"/>
                            <br>
                        </div>
                        <div class="col-6 form-group">
                            <label for="name" class="form-control-label">Name <font style="color:red;">*</font></label>
                            <input type="text" name="name" class="form-control" id="name-edit" value="{{old('name')}}"  autocomplete="new-password" required/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="last-name" class="form-control-label">Last name <font style="color:red;">*</font></label>
                            <input type="text" name="last_name" class="form-control" id="last-name-edit" value="{{old('last_name')}}" />
                        </div>
                        <div class="col-6 form-group">
                            <label for="department" class="form-control-label">Department <font style="color:red;">*</font></label>
                            <input type="text" name="department" class="form-control" id="department-edit" value="{{old('department')}}" required/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="yard_location" class="form-control-label">Yard Location</label>
                            <input type="text" name="yard_location" class="form-control" id="yard_location-edit" value="{{old('yard_location')}}">
                        </div>
                        <div class="col-6 form-group">
                            <label for="id_rol">Rol <font style="color:red;">*</font></label>
                            <select name="id_rol" class="form-control" id="id_rol_edit" required>
                                <option value="" style="text-transform: none !important;color: #99a3ac;" disabled>Select a role</option>
                                @foreach ($roles as $role)
                                <option value="{{$role->id}}" >{{$role->display_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 form-group">
                            <label for="recipient-tel" class="form-control-label">CDL Upload</label>
                            <input type="file" name="cdl" class="form-control" id="cdl-edit"/>
                        </div>
                        <div class="col-6 form-group">
                            <label for="email" class="form-control-label">Email <font style="color:red;">*</font></label>
                            <input type="email" name="email" class="form-control" id="email-edit" value"{{old('email')}}"  autocomplete="new-password" />
                        </div>
                        <div class="col-6 form-group">
                            <label for="recipient-tel-edit" class="form-control-label">Phone</label>
                            <input type="tel" name="tel" class="form-control" id="recipient-tel-edit" maxlength="15" placeholder="xxxxxxxxxx" autocomplete="new-password">
                        </div>
                        <div class="col-12" id="part-password-edit">
                            <div class="col-6 form-group">
                                <label for="recipient-password" class="form-control-label">Password <font style="color:red;">*</font></label>
                                <input type="password" name="password" class="form-control" id="password-edit" value="{{old('password')}}"   autocomplete="new-password"/>
                            </div>
                            <div class="col-6 form-group">
                                <label for="recipient-re-password" class="form-control-label">Confirm password <font style="color:red;">*</font></label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation_edit" value="{{old('password_confirmation')}}" autocomplete="new-password"/>
                            </div>
                        </div>
                        <div id="part-pin-edit" class="col-6 form-group">
                            <label for="pin" class="form-control-label">Pin <font style="color:red;">*</font></label>
                            <input type="text" name="pin" class="form-control" id="pin-edit" value="{{old('pin')}}" autocomplete="new-password"/>
                        </div>
                        <div class="col-6 form-group">
                            <label class="">Status</label>
                            <div class="kt-radio-inline d-flex justify-content-center p-2">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" value="enable" {{old('status')? (old('status')=='enable')? 'checked' :'' : 'checked'   }}> Enabled
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" value="disable" {{old('status')? (old('status')=='disable')? 'checked' :'' : ''   }}> Disabled
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-form-submit">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal edit employee -->

<!--start: Modal info employee -->
<div class="modal fade" id="modal-info-cell" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-info-cell-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body text-dark white-space-pre-wrap" id="modal-info-cell-content">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--end: Modal info employee -->

<!--start: Modal Delete Employees -->
<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('user_delete')}}" id="form_delete" method="POST" autocomplete="off">
                @csrf
                @method('delete')
                <div id="container-ids-delete"></div>
                <div class="modal-body">
                    <h1 class="text-uppercase text-center">  <i class="flaticon-danger text-danger display-1"></i> <br> Realmente desea Delete los empleados seleccionados</h1>
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
            <form action="{{route('user_delete')}}"  method="POST" autocomplete="off">
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

<!--start: Modal Import Users -->
<div class="modal fade" id="modal_users_import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Users from Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/import-users-excel"  method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="modal-body">
                    <!-- <small>Note: please make sure that </small> -->
                    <label for="import_file_upload" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                        <div class="btn btn-primary">Select File</div>
                        <small class="mr-2 import-uploaded-filename"></small>         
                    </label>
                    <input type='file' id="import_file_upload" style="display:none" name="file" accept=".xls, .xlsx" required/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal Import Users-->
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}"/>
<!-- end:: Content -->
@endsection
@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.js" type="text/javascript"></script>
<script>
    var input = document.querySelector("#recipient-tel");
    window.intlTelInput(input, {
      // allowDropdown: false,
      autoHideDialCode: false,
      autoPlaceholder: "off",
      // dropdownContainer: document.body,
      // excludeCountries: ["us"],
      formatOnDisplay: true,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
      hiddenInput: "tel",
      initialCountry: "us",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      //onlyCountries: ['mx','us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      preferredCountries: [ 'us','mx'],
      separateDialCode: true,
      utilsScript:'{{asset("assets/$theme")}}/vendors/general/intlTelInput/utils.js',
    });
    // Numeric only control handler
    jQuery.fn.ForceNumericOnly = function(){
        return this.each(function(){
            $(this).keydown(function(e){
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
    $("#recipient-tel").ForceNumericOnly();
    $("#recipient-tel-edit").ForceNumericOnly();
</script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-users.js" type="text/javascript"></script>
@endsection