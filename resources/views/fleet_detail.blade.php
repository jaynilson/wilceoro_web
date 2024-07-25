@extends("$theme/layout")
@section('title') WilcoERP - Vehicle detail @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/$theme")}}/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css"/>
@endsection
<link href="/css/fleet_detail.scss" rel="stylesheet" type="text/css"/>
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
                        <img src="/storage/images/fleet/{{$fleet->picture}}"/>
                    </div>
                    <div class="ml-4 w-100">
                        <div class="d-flex justify-content-between">
                            <h1>{{$fleet->model}}</h1>
                            <div class="preview-tools">
                                @if(SICAPHelper::checkPermission(4,2)||
                                SICAPHelper::checkPermission(5,2)||
                                SICAPHelper::checkPermission(6,2)||
                                SICAPHelper::checkPermission(17,2)||
                                SICAPHelper::checkPermission(7,2))
                                <div class="dropdown dropdown-inline">
                                    <button type="button" class="btn btn-brand  btn-elevate btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="la la-plus"></i> Add
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="kt-nav">
                                            @if(SICAPHelper::checkPermission(4,2))
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link" onclick="openCheckoutModal()">
                                                    <span class="kt-nav__link-text">Add Manual {{$id_check_out?"Check-In":"Check-Out"}}</span>
                                                </a>
                                            </li>
                                            @endif
                                            @if(SICAPHelper::checkPermission(5,2))
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link" onclick="openRecordModal(0)">
                                                    <span class="kt-nav__link-text">Add New Record</span>
                                                </a>
                                            </li>
                                            @endif
                                            @if(SICAPHelper::checkPermission(6,2))
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link" onclick="openToolModal(0)">
                                                    <span class="kt-nav__link-text">New Assign Inventory</span>
                                                </a>
                                            </li>
                                            @endif
                                            @if(SICAPHelper::checkPermission(17,2))
                                            <li class="kt-nav__item">
                                                <a href="/reminder/create/{{$fleet->id}}" class="kt-nav__link">
                                                    <span class="kt-nav__link-text">Add New Reminder</span>
                                                </a>
                                            </li>
                                            @endif
                                            @if(SICAPHelper::checkPermission(7,2))
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link" data-toggle="modal" data-target="#modal_add_document" onclick="$('#nav-document-tab').trigger('click');">
                                                    <span class="kt-nav__link-text">Add Document</span>
                                                </a>
                                            </li>
                                            @endif
                                            @if(SICAPHelper::checkPermission(4,2))
                                                @if(count($custom_fields)>0)
                                                <li class="kt-nav__item">
                                                    <a href="#" class="kt-nav__link"  onclick="openNewFleetCustomModal();">
                                                        <span class="kt-nav__link-text">Add Vehicle Specs</span>
                                                    </a>
                                                </li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                @endif
                                <button class="btn btn-outline-secondary  btn-elevate btn-icon-sm"  data-toggle="modal" data-target="#modal_dot_report" >
                                    DOT REPORT
                                </button>
                            </div>
                        </div>
                        <div class="-mt-2">
                            <p class="break-words max-w-full text-sm text-gray-400 text-left font-normal normal-case font-sans m-0">
                                {{$fleet->type=='trucks_cars'?"Vehicle":($fleet->type=='trailers'?"Trailer":"Equipment")}}
                                {{$fleet->department!=''?' · '.$fleet->department:''}}
                                {{$fleet->current_yard_location!=''?' · '.$fleet->current_yard_location:(
                                    $fleet->yard_location!=''?' · '.$fleet->yard_location:''
                                )}}
                                {{$fleet->licence_plate!=''?' · '.$fleet->licence_plate:''}}
                                {{$fleet->vin!=''?' · '.$fleet->vin:''}}
                                 {{' · N° '.$fleet->n}}
                            </p>
                        </div>
                        <div class="d-flex items-center first:ml-0">
                            <abbr class="break-words max-w-full text-sm text-gray-900 text-left font-sans mr-5" style="text-decoration: underline dotted;">
                                {{$fleet->last_odometer?$fleet->last_odometer:'0'}} mi
                            </abbr>
                            @if ($fleet->status == 'true')
                                <div class="status-green p-1">Available</div>
                            @elseif ($fleet->status == 'in-service')
                                <div class="status-yellow p-1">In Service</div>
                            @elseif ($fleet->status == 'check-out')
                                <div class="status-gray p-1">In-Use</div>
                            @else
                                <div class="status-red p-1">Out of Service</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button onclick="setTab('nav-overview')" class="nav-link active" id="nav-overview-tab" data-toggle="tab" data-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview" aria-selected="true">OVERVIEW</button>
                        <button onclick="setTab('nav-diver-history')" class="nav-link" id="nav-diver-history-tab" data-toggle="tab" data-target="#nav-diver-history" type="button" role="tab" aria-controls="nav-diver-history" aria-selected="true">DRIVER HISTORY</button>
                        <button onclick="setTab('nav-service')" class="nav-link" id="nav-service-tab" data-toggle="tab" data-target="#nav-service" type="button" role="tab" aria-controls="nav-service" aria-selected="false">SERVICE HISTORY</button>
                        @if(SICAPHelper::checkPermission(5))
                        <button onclick="setTab('nav-record')" class="nav-link" id="nav-record-tab" data-toggle="tab" data-target="#nav-record" type="button" role="tab" aria-controls="nav-record" aria-selected="false">RECORDS</button>
                        @endif
                        @if(SICAPHelper::checkPermission(6))
                        <button onclick="setTab('nav-tool')" class="nav-link" id="nav-tool-tab" data-toggle="tab" data-target="#nav-tool" type="button" role="tab" aria-controls="nav-tool" aria-selected="false">TOOLS</button>
                        @endif
                        @if(SICAPHelper::checkPermission(17))
                        <button onclick="setTab('nav-remider-custom')" class="nav-link" id="nav-remider-custom-tab" data-toggle="tab" data-target="#nav-remider-custom" type="button" role="tab" aria-controls="nav-remider-custom" aria-selected="true">REMINDERS</button>
                        @endif
                        @if(SICAPHelper::checkPermission(7))
                        <button onclick="setTab('nav-document')" class="nav-link" id="nav-document-tab" data-toggle="tab" data-target="#nav-document" type="button" role="tab" aria-controls="nav-document" aria-selected="false">DOCUMENTS</button>
                        @endif
                        <button onclick="setTab('nav-specifications')" class="nav-link" id="nav-specifications-tab" data-toggle="tab" data-target="#nav-specifications" type="button" role="tab" aria-controls="nav-specifications" aria-selected="false">VEHICLE SPECS</button>
                        <button onclick="setTab('nav-incident')" class="nav-link" id="nav-incident-tab" data-toggle="tab" data-target="#nav-incident" type="button" role="tab" aria-controls="nav-incident" aria-selected="false">INCIDENT</button>
                        <button onclick="setTab('nav-accident')" class="nav-link" id="nav-accident-tab" data-toggle="tab" data-target="#nav-accident" type="button" role="tab" aria-controls="nav-accident" aria-selected="false">ACCIDENT</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                        <form  action="{{route('fleet_detail_update')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <input type="hidden" value="{{$fleet->id}}" name="id"/>
                            <input type="hidden" value="1" name="is_main_field"/>
                            <div class="row">
                                <div class="col-12 order-sm-2 col-lg-6 order-lg-1">
                                    <div class="form-group">
                                        <label for="department" class="form-control-label">Department <font style="color:red;">*</font></label>
                                        <input type="hidden" name="default_department" id="default_department" value="{{$fleet->department}}" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                        <select name="department" class="select2 form-control" id="department" required {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="model" class="form-control-label">Make</label>
                                        <input type="text" name="make" class="form-control" id="make" value="{{$fleet->make}}" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                    </div>
                                    <div class="form-group">
                                        <label for="model" class="form-control-label">Model <font style="color:red;">*</font></label>
                                        <input type="text" name="model" class="form-control" id="model" value="{{$fleet->model}}" required {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                    </div>
                                    <div class="form-group">
                                        <label for="licence_plate" class="form-control-label">License plate <font style="color:red;">*</font></label>
                                        <input type="text" name="licence_plate" class="form-control" id="licence_plate" value="{{$fleet->licence_plate}}" required {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                    </div>
                                    <div class="form-group">
                                        <label for="vin" class="form-control-label">Vin </label>
                                        <input type="text" name="vin" class="form-control" id="vin" value="{{$fleet->vin}}" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                    </div>
                                    <div class="form-group">
                                        <label for="price" class="form-control-label">Purchase price </label>
                                        <input type="text" name="price" class="form-control" id="price" value="{{$fleet->price}}" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="insurance_expiration_date" class="form-control-label">Insurance expiration date</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control form-datepicker" name="insurance_expiration_date" id="insurance_expiration_date" value="{{$fleet->insurance_expiration_date}}" readonly {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                            <input type="checkbox" class="form-control ml-4 mt-2" style="flex-shrink: 0;" name="insurance_expiration_reminder" id="insurance_expiration_reminder" {{$fleet->insurance_expiration_reminder?'checked':''}} {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_date" class="form-control-label">Registration date</label>
                                        <div class="d-flex">
                                            <input type="text" name="registration_date" class="form-control" id="registration_date" value="{{ $registration_date }}" readonly {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                            <input type="checkbox" class="form-control ml-4 mt-2" style="flex-shrink: 0;" name="registration_reminder" id="registration_reminder" {{$fleet->registration_reminder?'checked':''}} {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_odometer" class="form-control-label">Last odometer</label>
                                        <input type="text" name="last_odometer" class="form-control" id="last_odometer" value="{{$fleet->last_odometer}}" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select name="category" class="form-control text-capitalize" id="category" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}>
                                            <option class="text-capitalize" value="" {{$fleet->category?'':'selected'}}  disabled>Select category</option>
                                            <option class="text-capitalize" value="lease" {{$fleet->category? ('lease'==$fleet->category)? 'selected':'' :''}} >Lease</option>
                                            <option class="text-capitalize" value="asset"  {{$fleet->category? ('asset'==$fleet->category)? 'selected':'' :''}}>Asset</option>
                                            <option class="text-capitalize" value="sold" {{$fleet->category? ('sold'==$fleet->category)? 'selected':'' :''}} >Sold</option>
                                            <option class="text-capitalize" value="decommissioned" {{$fleet->category? ('decommissioned'==$fleet->category)? 'selected':'' :''}} >Decommissioned</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="current_yard_location">Current yard location</label>
                                        <input type="hidden" name="default_current_yard_location" id="default_current_yard_location" value="{{$fleet->current_yard_location==null||$fleet->current_yard_location==''?$fleet->yard_location:$fleet->current_yard_location}}" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                        <select name="current_yard_location" class="select2 form-control text-capitalize" id="current_yard_location" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="current_driver" class="form-control-label">Current driver</label>
                                        <input type="text" name="current_driver" class="form-control" id="current_driver" value="{{$current_driver}}" readonly {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                    </div>
                                    @if(Auth::user()->id_rol==1||Auth::user()->id_rol==2||Auth::user()->id_rol==3)
                                    <div class="form-group">
                                        <label for="text-namager">Foreman</label>
                                        <div class="input-group mb-3">
                                            @if(SICAPHelper::checkPermission(4, 1))
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" data-toggle="modal" data-target="#modal_select_manager" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}>Select manager</button>
                                                <button id="btn_delete_manager" class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white; background: gray; display:{{$fleet->id_manager?'block':'none'}};" onclick="deleteManager()" {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}>Delete manager</button>
                                            </div>
                                            @endif
                                            <input class="form-control" id="text_manager" name="text-namager" value="{{$fleet->manager_name}}" readonly/>
                                        </div>
                                        <input type="hidden" name="id_manager" id="id_manager" />
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="lease_rental_return_date" class="form-control-label">Lease/Rental Return Date (id lease/Rental)</label>
                                        <div class="d-flex">
                                            <input type="text" class="form-control form-datepicker" name="lease_rental_return_date" id="lease_rental_return_date" value="{{$fleet->lease_rental_return_date}}" readonly {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                            <input type="checkbox" class="form-control ml-4 mt-2" style="flex-shrink: 0;" name="lease_rental_return_reminder" id="lease_rental_return_reminder" {{$fleet->lease_rental_return_reminder?'checked':''}} {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                        </div>
                                    </div>
                                    <div class="form-group d-flex justify-content-between">
                                        <label for="required_cdl" class="form-control-label">CDL Required</label>
                                        <input type="checkbox" class="form-control ml-4 mt-2" id="_required_cdl" {{$fleet->required_cdl?'checked':''}} {{SICAPHelper::checkPermission(4, 1)?'':'disabled'}}/>
                                        <input type="hidden" name="required_cdl" class="form-control" id="required_cdl" value="{{$fleet->required_cdl?1:0}}"/>
                                    </div>
                                    @if(SICAPHelper::checkPermission(4, 1))
                                    <div class="kt-login__actions">
                                        <br>
                                        <button id="kt_login_signin_submit" type="submit" class="btn btn-brand btn-pill  w-100">Save</button>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-12 order-sm-1 col-lg-6 order-lg-2 mb-5 mt-4">
                                    <div>
                                        <img id="img_preview" src="/storage/images/fleet/{{$fleet->picture}}" width="100%"/>
                                    </div>
                                    @if(SICAPHelper::checkPermission(4, 1))
                                    <div class="mt-2 d-flex justify-content-between">
                                        <div style="position: relative; width: 120px;">
                                            <button class="btn btn-brand btn-pill w-100">Change</button>
                                            <input type="file" name="picture_upload" id="prev_picture" style="position: absolute;width: 100%; opacity: 0; height:100%; cursor: hand; left: 0; top: 0;"/>
                                        </div>
                                        <button id="picture_upload_submit" type="submit" class="btn btn-brand btn-pill" style="width: 120px; display: none;">Save</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(SICAPHelper::checkPermission(17))
                    <div class="tab-pane fade" id="nav-remider-custom" role="tabpanel" aria-labelledby="nav-remider-custom-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_reminder">
                            <thead>
                                <tr>
                                    <th>Reminder Type</th>
                                    <th>Task name</th>
                                    <th>Details</th>
                                    <th>Trigger</th>
                                    <th>Trigger Type</th>
                                    <th>Watchers</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    @endif
                    <div class="tab-pane fade" id="nav-diver-history" role="tabpanel" aria-labelledby="nav-diver-history-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_driver_history">
                            <thead>
                                <tr>
                                    <th>Driver name</th>
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
                    <div class="tab-pane fade" id="nav-service" role="tabpanel" aria-labelledby="nav-service-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_maintenance">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Miles</th>
                                    <th>Service type</th>
                                    <th>Next service date</th>
                                    <th>Next service miles</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    @if(SICAPHelper::checkPermission(5))
                    <div class="tab-pane fade" id="nav-record" role="tabpanel" aria-labelledby="nav-record-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_records">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th>Documents</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    @endif
                    @if(SICAPHelper::checkPermission(6))
                    <div class="tab-pane fade" id="nav-tool" role="tabpanel" aria-labelledby="nav-tool-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_tools">
                            <thead>
                                <tr>
                                    <th>Tool</th>
                                    <th>Assign Date</th>
                                    <th>Return Date</th>
                                    <th>Note</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    @endif
                    @if(SICAPHelper::checkPermission(7))
                    <div class="tab-pane fade" id="nav-document" role="tabpanel" aria-labelledby="nav-document-tab">
                        <div class="row">
                            @if (!count($files))
                            <div class="col-6 col-md-2 col-lg-2">
                                There are no documents
                            </div>
                            @else
                                @foreach ($files as $c => $value)
                                <div class="col-6 col-md-2 col-lg-2">
                                    <div class="container-doc">
                                        @if(SICAPHelper::checkPermission(7, 3))
                                        <div class="container-doc-delete">
                                            <form action="{{route('file_fleet_delete')}}"  method="POST" autocomplete="off">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" name="id" value="{{$value->id}}">
                                                <button name="action" value="blue">
                                                    <img src="{{asset("assets")}}/images/delete.webp" alt="">
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                        <a href="/storage/files_fleet/{{$value->picture}}" target="_blank">   
                                            @if ($value->ext == 'jpg' || $value->ext == 'jpeg' || $value->ext == 'png' || $value->ext == 'gif' || $value->ext == 'webp' || $value->ext == 'svg')
                                            <img src="{{asset("assets")}}/images/galeria.png" alt="">
                                            @elseif($value->ext == 'pdf')
                                            <img src="{{asset("assets")}}/images/pdf.png" alt="">
                                            @elseif($value->ext == 'xlsx' || $value->ext == 'xlsm' || $value->ext == 'xls' || $value->ext == 'xml')
                                            <img src="{{asset("assets")}}/images/xls.png" alt="">
                                            @elseif($value->ext == 'docx' || $value->ext == 'docm' || $value->ext == 'punto' )
                                            <img src="{{asset("assets")}}/images/docx.png" alt="">
                                            @else
                                            <img src="{{asset("assets")}}/images/unknown.png" alt="">
                                            @endif
                                        </a>
                                        <div class="containert-doc-title">{{$value->title}}</div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="tab-pane fade" id="nav-specifications" role="tabpanel" aria-labelledby="nav-specifications-tab">
                        <!--begin: Datatable -->
                        <input type="hidden" value="{{json_encode($custom_fields)}}" id="custom_field_names"/>
                        <table class="table-bordered table-hover table-data-custom" id="table_custom_rows">
                            <thead>
                                <tr>
                                    @foreach($custom_fields as $field)
                                    <th>{{$field->title}}</th>
                                    @endforeach
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable -->
                    </div>
                    <div class="tab-pane fade" id="nav-incident" role="tabpanel" aria-labelledby="nav-incident-tab">
                        <!--begin: Datatable -->
                        <table class="table-bordered table-hover table-data-custom" id="table_incidents">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Type</th>
                                    <th>Employee</th>
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
                                    <th>Employee</th>
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
                </div>
            </div>
        </div>                
    </div>
</div>

@if(SICAPHelper::checkPermission(4,1)||SICAPHelper::checkPermission(4,2))
<!--start: Modal add fleet custom -->
<div class="modal fade" id="modal_fleet_custom" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="fleet_custom">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                <div class="modal-body" class="d-flex justify-content-center">
                    @csrf
                    @method('post')
                    <input type="hidden" id="custom_row_id" name="custom_row_id"/>
                    @foreach($custom_fields as $field)
                    <div class="form-group {{$field->type=='boolean'?'d-flex justify-content-between':''}}">
                        <label for="{{$field->name}}" class="form-control-label">{{$field->title}}</label>
                        @if($field->type=='boolean')
                        <input
                            type="checkbox"
                            class="form-control"
                            data-id="edit_custom_{{$field->name}}"
                        />
                        <input
                            type="hidden"
                            class="form-control hidden-checkbox"
                            name="{{$field->name}}"
                            id="edit_custom_{{$field->name}}"
                            value="0"
                        />
                        @else
                        <input
                            type="{{
                                $field->type=='integer'||$field->type=='double'?'number':(
                                    $field->type=='boolean'?'checkbox':'text'
                                )}}"
                            class="form-control{{$field->type=='date'?' form-datepicker':''}}"
                            name="{{$field->name}}"
                            id="edit_custom_{{$field->name}}"
                        />
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end: Modal add fleet custom -->
@endif

@if(SICAPHelper::checkPermission(7, 2))
<!--start: Modal add element -->
<div class="modal fade" id="modal_add_document" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{route('fleet_document')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                
                <div class="modal-body" class="d-flex justify-content-center">
                    <div class="form-group">
                        <label for="title" class="form-control-label">Title <font style="color:red;">*</font></label>
                        <input type="text" name="title" class="form-control" id="title"/>
                    </div>
                    <br>
                    <input type='file' name="file" class=""/>
                    <input type="hidden" name="id_fleet" value="{{$fleet->id}}">
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

<!--start: Modal dot report  element -->
<div class="modal fade" id="modal_dot_report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DOT REPORT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" class="d-flex justify-content-center" >
                <!--begin: Datatable -->
                <table class="table-bordered table-hover table-data-custom" id="table_dot_report_checkout">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Driver</th>
                            <th>View report</th>
                        </tr>
                    </thead>
                </table>
                <!--end: Datatable -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!--end: Modal dot report element -->

<!--start: Modal show dot report  element -->
<div class="modal fade" id="modal_dot_report_detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DOT REPORT DETAIL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" class="d-flex justify-content-center" >
                {{-- asset("assets/images/user_default.png") : Storage::url("images/profiles/".$user->picture) --}}
                <table class="table-bordered table-hover table-data-custom w-100"  >
                    <thead>
                        <tr>
                            <th>Check</th>
                            <th>Image</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="table_body_dot_report_checkout"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!--end: Modal show dot report element -->

@if(SICAPHelper::checkPermission(5, 1)||SICAPHelper::checkPermission(5, 2))
<!--start: Modal add element -->
<div class="modal fade" id="modal_record" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="record">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input type="hidden" name="id" id="record_id"/>
                <div class="modal-body" class="d-flex justify-content-center" >
                    <div class="form-group">
                        <label for="type" class="form-control-label">Type <font style="color:red;">*</font></label>
                        <select name="type" class="form-control text-capitalize" id="record_type" required>
                            <option class="text-capitalize" value="0" {{old('type')==0?'selected':''}}>Dielectric Test</option>
                            <option class="text-capitalize" value="1" {{old('type')==1?'selected':''}}>DOT</option>
                            <option class="text-capitalize" value="2" {{old('type')==2?'selected':''}}>Custom</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date" class="form-control-label">Date <font style="color:red;">*</font></label>
                        <input type="text" name="date" class="form-control form-datepicker" id="record_date" value="{{old('date')}}" required/>
                    </div>
                    <div class="form-group">
                        <label for="note" class="form-control-label">Note</label>
                        <textarea type="text" name="note" class="form-control" id="record_note">{{old('note')}}</textarea>
                    </div>
                    <div class="mt-3">
                        <label for="record_file_upload" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click to upload">
                        <div class="btn btn-primary">Attach Document File</div>          
                        </label>
                        <input type='file' id="record_file_upload" style="display:none" name="picture_upload"/>
                        <br>
                    </div>
                    <div id="container-files-add">
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

@if(SICAPHelper::checkPermission(6, 1)||SICAPHelper::checkPermission(6, 2))
<!--start: Modal add element -->
<div class="modal fade" id="modal_tool" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="tool">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input type="hidden" name="id" id="tool_id"/>
                <div class="modal-body" class="d-flex justify-content-center">
                    <div class="form-group">
                        <label for="text-tool">Inventory <font style="color:red;">*</font></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectTool()">Select a inventory</button>
                            </div>
                            <input class="form-control" id="text_tool" name="text-tool" readonly required/>
                        </div>
                        <input type="hidden" name="id_tool" id="tool_id_tool"/>
                        <input type="hidden" name="return_tool" id="tool_return_tool"/>
                    </div>
                    <div class="form-group">
                        <label for="assign_date" class="form-control-label">Assign Date <font style="color:red;">*</font></label>
                        <input type="text" name="assign_date" class="form-control form-datepicker" id="tool_assign_date" value="{{old('assign_date')}}" required/>
                    </div>
                    <div class="form-group return-date-input">
                        <label for="return_date" class="form-control-label">Return Date <font style="color:red;">*</font></label>
                        <input type="text" name="return_date" class="form-control form-datepicker" id="tool_return_date" value="{{old('return_date')}}"/>
                    </div>
                    <div class="form-group">
                        <label for="note" class="form-control-label">Note</label>
                        <textarea type="text" name="note" class="form-control" id="tool_note">{{old('note')}}</textarea>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal show files  -->

<!--start: Modal select tool -->
<div class="modal fade" id="modal_select_tool" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="select-tool">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select a inventory</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modal_tool').modal('show');"></button>
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
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#modal_tool').modal('show');">Cancel</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="$('#modal_tool').modal('show');">Select</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal select tool -->

<!--start: Modal select tool -->
<div class="modal fade" id="modal_select_manager" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="select-manager">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select manager</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" >
        <div class="container">
          <!--begin: Datatable -->
          <table class="table-bordered table-hover table-data-custom" id="kt_table_manager_selected">
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal select tool -->

<!--start: Modal add element -->
<div class="modal fade" id="modal_checkout" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="checkout">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$id_check_out?"Check-In":"Check-Out"}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input type="hidden" name="id_check_out" id="id_check_out" value="{{$id_check_out}}"/>
                <div class="modal-body" class="d-flex justify-content-center">
                    <div class="form-group">
                        <label for="text-tool">Driver <font style="color:red;">*</font></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary btn-input" type="button" style="height: 38px; color:white;" onclick="showModalSelectDriver()">Select driver</button>
                            </div>
                            <input class="form-control" id="text_driver" name="text-driver" readonly required/>
                        </div>
                        <input type="hidden" name="id_driver" id="checkout_id_driver"/>
                    </div>
                    <div class="form-group">
                        <label for="model" class="form-control-label">Yard Location <font style="color:red;">*</font></label>
                        <select name="yard" class="select2 form-control" id="checkout_yard" required></select>
                    </div>
                    <div class="form-group">
                        <label for="assign_date" class="form-control-label">{{$id_check_out?"Check-In":"Check-Out"}} Date <font style="color:red;">*</font></label>
                        <input type="text" name="date" class="form-control form-datepicker" id="checkout_date" value="{{old('date')}}" required/>
                    </div>
                    <div class="form-group">
                        <label for="assign_date" class="form-control-label">Odometers <font style="color:red;">*</font></label>
                        <input type="number" name="odometer" class="form-control" id="checkout_odometer" value="{{old('odometer')}}" required/>
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

<!--start: Modal select tool -->
<div class="modal fade" id="modal_select_driver" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="select-driver">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select driver</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" >
        <div class="container">
          <!--begin: Datatable -->
          <table class="table-bordered table-hover table-data-custom" id="kt_table_driver_selected">
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--end: Modal select tool -->
<input type="hidden" id="id" value="{{$fleet->id}}">
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
<script src="{{asset("assets")}}/js/page-fleet-detail.js" type="text/javascript"></script>
<script>
    jQuery.fn.ForceNumericOnly = function(){
        return this.each(function()
        {
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

    $("#price").ForceNumericOnly();

    var routePublicImages = @json(asset("assets")."/images/");
    var routePublicStorageInterface = @json(Storage::url("images/interface/"));
</script>
@endsection

