@extends("$theme/layout")
@section('title') WilcoERP - Perfil @endsection
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
["route"=>"user_profile","name"=>"Mi perfil"],
]) !!}
@endsection
@section('content_page')
<!-- begin:: Content -->
<form action="{{route('user_profile_update')}}"  method="POST" autocomplete="off"  enctype="multipart/form-data" >
    @csrf
    @method('put')
    <input type="hidden" name="id" value="{{$user->id}}">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Profile
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <button class="btn btn-brand btn-elevate btn-icon-sm" type="submit">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <label for="img-change"  data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Click for change">
                    <img id="img-change-profile" class="picture-profile" src="{{ (empty($user->picture))? asset("assets/images/user_default.png") : Storage::url("images/profiles/".$user->picture) }}"  />                 
                    </label>
                    <input type='file' id="img-change" style="display:none" name="picture_upload" accept="image/*"/>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        @foreach ($roles as $rol)
                            @if ($rol->id==old('id_rol',$user->id_rol))
                            <label for="id-rol" class="form-control-label">Role <font style="color:red;">*</font></label>
                            <input type="text" name="id_rol" class="form-control" id="id-rol" value="{{$rol->display_name}}" disabled/>
                            @endif
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Name <font style="color:red;">*</font></label>
                        <input type="text" name="name" class="form-control" id="recipient-name" value="{{ old('name',$user->name ?? '')}}" />
                    </div>
                    <div class="form-group">
                            <label for="recipient-last-name" class="form-control-label">Last name <font style="color:red;">*</font></label>
                            <input type="text" name="last_name" class="form-control" id="recipient-last-name" value="{{old('last_name',$user->last_name ??'')}}" />
                    </div>
                    <div class="form-group">
                            <label for="recipient-email" class="form-control-label">Email <font style="color:red;">*</font></label>
                            <input type="email" name="email" class="form-control" id="recipient-email" value="{{old('email',$user->email ??'')}}" />
                    </div>
                    <div class="form-group" >
                        <label for="recipient-tel" class="form-control-label">Phone <font style="color:red;">*</font></label>
                        <input type="tel" name="tel" class="form-control" id="recipient-tel" maxlength="10" value="{{old('tel',$user->tel ??'')}}" >
                        </div>
                        <br>
                        <div class="w-100">
                        <span>Change password</span> <span>  <hr></span>
                    </div>
                    <div class="form-group">
                            <label for="recipient-password" class="form-control-label">Password </label>
                            <input type="password" name="password" class="form-control" id="recipient-password" autocomplete="new-password" value=""/>
                    </div>
                    <div class="form-group">
                        <label for="recipient-re-password" class="form-control-label">Confirm password </label>
                        <input type="password" name="password_confirmation" class="form-control" id="recipient-re-password" value=""/>
                    </div>
                    <div class="form-group">
                        <br>
                        <div class="w-100">
                            <span>Would you like to receive the following notifications?</span>
                            <span><hr></span>
                        </div>
                    </div>
                    <div class="form-group d-flex mt-4">
                        <label style="width: 250px;" for="notify_accident" class="form-control-label">Accident Reports</label>
                        <input type="checkbox" name="notify_accident" class="form-control" id="notify_accident" {{old('notify_accident',$user->notify_accident ??true)?'checked':''}}/>
                    </div>
                    <div class="form-group d-flex mt-4">
                        <label style="width: 250px;" for="notify_checkout" class="form-control-label">Check-Out/In</label>
                        <input type="checkbox" name="notify_checkout" class="form-control" id="notify_checkout" {{old('notify_checkout',$user->notify_checkout ??true)?'checked':''}}/>
                    </div>
                    <div class="form-group d-flex mt-4">
                        <label style="width: 250px;" for="notify_service" class="form-control-label">Service Events</label>
                        <input type="checkbox" name="notify_service" class="form-control" id="notify_service" {{old('notify_service',$user->notify_service ??true)?'checked':''}}/>
                    </div>
                    <div class="form-group d-flex mt-4">
                        <label style="width: 250px;" for="notify_outofstock" class="form-control-label">Inventory Out-Of-Stock</label>
                        <input type="checkbox" name="notify_outofstock" class="form-control" id="notify_outofstock" {{old('notify_outofstock',$user->notify_outofstock ??true)?'checked':''}}/>
                    </div>
                    <div class="form-group d-flex mt-4">
                        <label style="width: 250px;" for="notify_damage" class="form-control-label">Damage Reports</label>
                        <input type="checkbox" name="notify_damage" class="form-control" id="notify_damage" {{old('notify_damage',$user->notify_damage ??true)?'checked':''}}/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
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
<script>
var input = document.querySelector("#recipient-tel");
  window.intlTelInput(input, {
    // allowDropdown: false,
    autoHideDialCode: false,
    autoPlaceholder: "off",
    // dropdownContainer: document.body,
    // excludeCountries: ["us"],
    formatOnDisplay: false,
    // geoIpLookup: function(callback) {
    //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
    //     var countryCode = (resp && resp.country) ? resp.country : "";
    //     callback(countryCode);
    //   });
    // },
    hiddenInput: "tel",
    initialCountry: "mx",
    // localizedCountries: { 'de': 'Deutschland' },
    // nationalMode: false,
    //onlyCountries: ['mx','us', 'gb', 'ch', 'ca', 'do'],
    // placeholderNumberType: "MOBILE",
    preferredCountries: ['mx', 'us'],
    separateDialCode: true,
    utilsScript:'{{asset("assets/$theme")}}/vendors/general/intlTelInput/utils.js',
  });
  $('#recipient-tel').keypress(function (e) {    
    var charCode = (e.which) ? e.which : event.keyCode    
    if (String.fromCharCode(charCode).match(/[^0-9]/g))    
        return false;                        
});  
</script>
<script src="{{asset("assets")}}/js/page-management-client.js" type="text/javascript"></script>
@endsection

