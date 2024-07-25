@extends("$theme/layout")
@section('title') WilcoERP - Reminder Create @endsection
@section('styles_page_vendors')
<link href="{{asset("assets/$theme")}}/vendors/custom/jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css">
<link href="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/$theme")}}/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
@endsection
<link href="/css/reminder_create.scss" rel="stylesheet" type="text/css" />
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
            <form  action="{{route('reminder_save')}}"  method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input type="hidden" value="{{$reminder->id}}" name="id" id="edit_id"/>
                <input type="hidden" value="{{$id_fleet}}" name="prev_id_fleet" id="prev_id_fleet"/>
                <div class="row">
                    <div class="form-group col-6">
                        <label for="task" class="form-control-label">Fleet <font style="color:red;">*</font></label>
                        <input type="hidden" value="{{$reminder->id_fleet}}" name="reminder_id_fleet" id="reminder_id_fleet" required/>
                        <select class="select2 form-control text-capitalize" name="id_fleet" id="edit_id_fleet">
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-6">
                        <label for="task" class="form-control-label">Type <font style="color:red;">*</font></label>
                        <select class="form-control text-capitalize" name="type" id="edit_type" required>
                            <option value="service" {{$reminder->type=='service'?'selected':''}}>SERVICE</option>
                            <option value="renewal" {{$reminder->type=='renewal'?'selected':''}}>INSURANCE RENEWAL</option>
                            <option value="electric" {{$reminder->type=='electric'?'selected':''}}>DI-ELECTRIC TEST</option>
                            <option value="dot" {{$reminder->type=='dot'?'selected':''}}>DOT INSPECTION</option>
                            <option value="custom" {{$reminder->type=='custom'?'selected':''}}>CUSTOM</option>
                        </select>
                    </div>
                    <div class="form-group col-6 select-service-wrapper">
                        <label for="task" class="form-control-label">Service <font style="color:red;">*</font></label>
                        <input type="hidden" value="{{$reminder->id_service}}" name="reminder_id_service" id="reminder_id_service"/>
                        <select class="select2 form-control text-capitalize" name="id_service" id="edit_id_service">
                        </select>
                    </div>
                    <div class="form-group col-6 select-interface-wrapper">
                        <label for="task" class="form-control-label">Interface <font style="color:red;">*</font></label>
                        <input type="hidden" value="{{$reminder->id_interface}}" name="reminder_id_interface" id="reminder_id_interface"/>
                        <select class="select2 form-control text-capitalize" name="id_interface" id="edit_id_interface">
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-12">
                        <label for="task" class="form-control-label">Task name <font style="color:red;">*</font></label>
                        <input type="text" name="task" class="form-control" id="edit_task" value="{{$reminder->task}}" required/>
                    </div>
                    <div class="form-group col-12">
                        <label for="description" class="form-control-label">Details</label>
                        <textarea name="description" class="form-control" id="edit_description">{{$reminder->description}}</textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-12">
                        <label for="task" class="form-control-label">Watchers <font style="color:red;">*</font></label>
                        <input type="hidden" value="" name="reminder_id_watchers" id="reminder_id_watchers"/>
                        <select class="select2 form-control text-capitalize" name="id_watchers[]" id="edit_id_watchers">
                        </select>
                    </div>
                </div>
                <div class="row mt-3">   
                    <div class="form-group col-6">
                        <label for="time_interval" class="form-control-label">Trigger <font style="color:red;">*</font></label>
                        <div class="d-flex gap-10" id="edit_common_interval_wrapper">
                            <select class="form-control text-capitalize" name="time_interval_unit" id="edit_time_interval_unit">
                                <option value="0" {{$reminder->time_interval_unit==0?'selected':''}}>DATE</option>
                                <option value="1" {{$reminder->time_interval_unit==1?'selected':''}}>TIME</option>
                                <option value="2" {{$reminder->time_interval_unit==2?'selected':''}}>ODOMETER</option>
                            </select>
                            <input type="text" name="common_interval" class="form-control" id="edit_common_interval" value="{{$reminder->common_interval}}" required/>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-6">
                        <label class="">Status</label>
                        <div class="kt-radio-inline d-flex justify-content-center p-2">
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="status" id="edit_status_true" value="true" checked> ENABLED
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="status" id="edit_status_false" value="false"> DISABLED
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-6" style="position:relative;">
                        <div class="kt-login__actions"  style="position:absolute;right:15px;top:10px;">
                            <button id="kt_login_signin_submit" type="submit" class="btn btn-brand btn-pill" style="width:120px;">Save</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>
<input type="hidden" name="_token" id="token_ajax" value="{{ Session::token() }}">
@endsection
@section('js_page_vendors')
    <script src="{{asset("assets/$theme")}}/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/bootstrap-datepicker/init.js" type="text/javascript"></script>
    <script src="{{asset("assets/$theme")}}/vendors/general/select2/dist/js/select2.js" type="text/javascript"></script>
@endsection
@section('js_optional_vendors')
@endsection
@section('js_page_scripts')
<script src="{{asset("assets")}}/js/page-reminder-create.js" type="text/javascript"></script>
<script>
    var routePublicImages = @json(asset("assets")."/images/");
</script>
@endsection