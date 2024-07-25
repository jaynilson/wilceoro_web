@extends("$theme/layout")
@section('title') WilcoERP - User edit @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css"/>
@endsection
<link href="/css/user_edit.scss" rel="stylesheet" type="text/css"/>
@section('styles_optional_vendors')
@endsection

@section('content_breadcrumbs')  
{!! SICAPHelper::getBreadCrumbs([
["route"=>"#","name"=>"Panel de Control"],
["route"=>"#","name"=>"Auditorias (logs)"]
]) !!}
@endsection

@section('content_page')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">
        <div class="container">
            <div class="mb-2">
                <div class="preview-wrapper d-flex">
                    <div class="fleet-img">
                        <img id="img-change-profile" class="picture-profile" src="{{ (empty($user->picture))? asset("assets/images/user_default.png") : Storage::url("images/profiles/".$user->picture) }}"/>
                        <i class="flaticon-edit" id="edit_avatar_icon"></i>
                    </div>
                    <div class="ml-4 w-100">
                        <div class="d-flex justify-content-between">
                            <h1>{{$user->name}} {{$user->last_name}}</h1>
                            <div class="preview-tools">
                                <div class="dropdown dropdown-inline">
                                    <!-- <button type="button" class="btn btn-brand  btn-elevate btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="la la-plus"></i> Add
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="kt-nav">
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link" onclick="openCheckoutModal()">
                                                    <span class="kt-nav__link-text">Add Manual {{$id_check_out?"Check-In":"Check-Out"}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="-mt-2">
                            <p class="break-words max-w-full text-sm text-gray-400 text-left font-normal normal-case font-sans m-0">
                                {{$role_name}}
                                {{$user->email!=''?' · '.$user->email:''}}
                                {{$user->yard_location!=''?' · '.$user->yard_location:''}}
                                {{$user->fleet?' · N° '.$user->fleet->n:''}}
                            </p>
                        </div>
                        <div class="d-flex items-center first:ml-0">
                            @if($user->odomete>0)
                            <abbr class="break-words max-w-full text-sm text-gray-900 text-left font-sans mr-5" style="text-decoration: underline dotted;">
                                {{$user->odometer.' mi'}}
                            </abbr>
                            @endif
                            @if ($user->fleet)
                                <div class="status-green p-1">Working</div>
                            @elseif ($user->status == 'enable')
                                <div class="status-blue p-1">Enable</div>
                            @elseif ($fleet->status == 'disable')
                                <div class="status-gray p-1">Disable</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button onclick="setTab('nav-overview')" class="nav-link active" id="nav-overview-tab" data-toggle="tab" data-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview" aria-selected="true">PROFILE</button>
                        @if($user->id_rol==4)
                        <button onclick="setTab('nav-diver-history')" class="nav-link" id="nav-diver-history-tab" data-toggle="tab" data-target="#nav-diver-history" type="button" role="tab" aria-controls="nav-diver-history" aria-selected="true">DRIVER HISTORY</button>
                        @endif
                        @if($user->id_rol==5)
                        <button onclick="setTab('nav-service')" class="nav-link" id="nav-service-tab" data-toggle="tab" data-target="#nav-service" type="button" role="tab" aria-controls="nav-service" aria-selected="false">SERVICE HISTORY</button>
                        @endif
                        @if($user->id_rol==4||$user->id_rol==5)
                        <button onclick="setTab('nav-incident')" class="nav-link" id="nav-incident-tab" data-toggle="tab" data-target="#nav-incident" type="button" role="tab" aria-controls="nav-incident" aria-selected="false">INCIDENT</button>
                        <button onclick="setTab('nav-accident')" class="nav-link" id="nav-accident-tab" data-toggle="tab" data-target="#nav-accident" type="button" role="tab" aria-controls="nav-accident" aria-selected="false">ACCIDENT</button>
                        @endif
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                        <form id="form_user_update" action="{{route('user_update')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" value="{{$user->id}}" name="id"/>
                            <input type="hidden" value="/user/edit/{{$user->id}}" name="_page"/>
                            <input type="hidden" value="true" name="_notify_setting"/>
                            <input type="file" name="picture_upload" id="avatar_upload" style="display:none;"/>
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="name" class="form-control-label">Name <font style="color:red;">*</font></label>
                                    <input type="text" name="name" class="form-control" id="user_name" value="{{$user->name}}" required {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="last-name" class="form-control-label">Last name</label>
                                    <input type="text" name="last_name" class="form-control" id="user_last_name" value="{{$user->last_name}}" {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="department" class="form-control-label">Department <font style="color:red;">*</font></label>
                                    <input type="text" name="department" class="form-control" id="user_department" value="{{$user->department}}" required {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="yard_location" class="form-control-label">Yard Location</label>
                                    <input type="text" name="yard_location" class="form-control" id="user_yard_location" value="{{$user->yard_location}}" {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="id_rol">Rol <font style="color:red;">*</font></label>
                                    <select name="id_rol" class="form-control" id="user_id_rol" required {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}>
                                        <option value="" style="text-transform: none !important;color: #99a3ac;" disabled>Select a role</option>
                                        @foreach ($roles as $rol)
                                        <option value="{{$rol->id}}" {{$user->id_rol==$rol->id?'selected':''}}>{{$rol->display_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="cdl" class="form-control-label">CDL</label>
                                    <div>
                                        <span class="mt-2 cdl-file-name">{{$user->cdl_path}}</span>
                                        <button type="button" id="upload_cdl_btn" class="btn btn-secondary btn-pill ml-2" style="width:100px;" {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}>{{$user->cdl_path&&$user->cdl_path!=''?"Change file":"Upload file"}}</button>
                                        <input type="file" name="cdl_upload" class="form-control" id="user_cdl" style="display:none;" {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                    </div>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="email" class="form-control-label">Email <font style="color:red;">*</font></label>
                                    <input type="email" name="email" class="form-control" id="user_email" value="{{$user->email}}" required {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="tel" class="form-control-label">Phone</label>
                                    <input type="tel" name="tel" class="form-control" id="user_tel" value="{{$user->tel}}" maxlength="10" placeholder="xxxxxxxxxx" {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-12" id="part-password">
                                    <div class="col-6 form-group">
                                        <label for="password" class="form-control-label">Password <font style="color:red;">*</font></label>
                                        <input type="password" name="password" class="form-control" id="user_password" value="" {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="password_confirmation" class="form-control-label">Confirm password <font style="color:red;">*</font></label>
                                        <input type="password" name="password_confirmation" class="form-control" id="user_password_confirmation" {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                    </div>
                                </div>
                                <div id="part-pin" class="col-6 form-group">
                                    <label for="pin" class="form-control-label">Pin <font style="color:red;">*</font></label>
                                    <input type="text" name="pin" class="form-control" id="user_pin" value="{{$user->pin}}" {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-6 form-group">
                                    <label class="">Status</label>
                                    <div class="kt-radio-inline d-flex justify-content-center p-2">
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="status" value="enable" {{$user->status=='enable'? 'checked':''}} {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/> Enabled
                                            <span></span>
                                        </label>
                                        <label class="kt-radio kt-radio--solid">
                                            <input type="radio" name="status" value="disable" {{$user->status=='disable'? 'checked':''}} {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/> Disabled
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 form-group">
                                    <br>
                                    <div class="w-100">
                                        <span>Would you like to receive the following notifications?</span>
                                        <span><hr></span>
                                    </div>
                                </div>
                                <div class="col-12 form-group d-flex mt-4">
                                    <label style="width: 250px;" for="notify_accident" class="form-control-label">Accident Reports</label>
                                    <input type="checkbox" name="notify_accident" class="form-control" id="notify_accident" {{old('notify_accident',$user->notify_accident ??true)?'checked':''}} {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-12 form-group d-flex mt-4">
                                    <label style="width: 250px;" for="notify_checkout" class="form-control-label">Check-Out/In</label>
                                    <input type="checkbox" name="notify_checkout" class="form-control" id="notify_checkout" {{old('notify_checkout',$user->notify_checkout ??true)?'checked':''}} {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-12 form-group d-flex mt-4">
                                    <label style="width: 250px;" for="notify_service" class="form-control-label">Service Events</label>
                                    <input type="checkbox" name="notify_service" class="form-control" id="notify_service" {{old('notify_service',$user->notify_service ??true)?'checked':''}} {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-12 form-group d-flex mt-4">
                                    <label style="width: 250px;" for="notify_outofstock" class="form-control-label">Inventory Out-Of-Stock</label>
                                    <input type="checkbox" name="notify_outofstock" class="form-control" id="notify_outofstock" {{old('notify_outofstock',$user->notify_outofstock ??true)?'checked':''}} {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                <div class="col-12 form-group d-flex mt-4">
                                    <label style="width: 250px;" for="notify_damage" class="form-control-label">Damage Reports</label>
                                    <input type="checkbox" name="notify_damage" class="form-control" id="notify_damage" {{old('notify_damage',$user->notify_damage ??true)?'checked':''}} {{SICAPHelper::checkPermission(2, 1)?'':'disabled'}}/>
                                </div>
                                @if(SICAPHelper::checkPermission(2, 1))
                                <div class="col-6 form-group">
                                    <div class="kt-login__actions" style="width: 80px;">
                                        <br>
                                        <button type="submit" class="btn btn-brand btn-pill  w-100">Save</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                    @if($user->id_rol==4)
                    <div class="tab-pane fade" id="nav-diver-history" role="tabpanel" aria-labelledby="nav-diver-history-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_driver_history">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Yard - Out</th>
                                    <th>Check-Out At</th>
                                    <th>Check-Out Miles</th>
                                    <th>Yard - In</th>
                                    <th>Check-In At</th>
                                    <th>Check-In Miles</th>
                                    <th>Elapsed time</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    @endif
                    @if($user->id_rol==5)
                    <div class="tab-pane fade" id="nav-service" role="tabpanel" aria-labelledby="nav-service-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_maintenance">
                            <thead>
                                <tr>
                                    <th class="clean-icon-table"></th>
                                    <th>Service</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Vehicle Part</th>
                                    <th>Hours spent</th>
                                    <th>Files</th>
                                    <th>Cost</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    @endif
                    @if($user->id_rol==4 || $user->id_rol==5)
                    <div class="tab-pane fade" id="nav-incident" role="tabpanel" aria-labelledby="nav-incident-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_incidents">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Type</th>
                                    <th>Vehicle</th>
                                    <th>Category</th>
                                    <th>Place</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    <div class="tab-pane fade" id="nav-accident" role="tabpanel" aria-labelledby="nav-accident-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_accidents">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Type</th>
                                    <th>Answer</th>
                                    <th>Question</th>
                                    <th>Position</th>
                                    <th>Content</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    @endif
                </div>
            </div>
        </div>                
    </div>
</div>
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
<input type="hidden" id="id" value="{{$user->id}}">
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}"/>
@endsection
@section('js_page_vendors')
<script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-datepicker/init.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/select2/dist/js/select2.js" type="text/javascript"></script>
<script src="{{asset("assets/$theme")}}/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-user-edit.js" type="text/javascript"></script>
@endsection

