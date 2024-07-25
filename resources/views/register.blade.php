<!DOCTYPE html>

<html lang="es">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>WilcoERP - Registrate</title>
		<meta name="register" content="Register page ">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<script src="{{asset("assets/$theme")}}/vendors/general/webfont/1.6.16/webfont.js"></script>

		<!--end::Fonts -->

		<!--begin::Page Custom Styles(used by this page) -->
		<link href="{{asset("assets/$theme")}}/app/custom/login/login-v4.default.css" rel="stylesheet" type="text/css" />

		<!--end::Page Custom Styles -->

		<!--begin:: Global Mandatory Vendors -->
		<link href="{{asset("assets/$theme")}}/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Mandatory Vendors -->

	<!--begin:: Global Optional Vendors -->
    <link href="{{asset("assets/$theme")}}/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/$theme")}}/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/$theme")}}/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/$theme")}}/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/$theme")}}/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/$theme")}}/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/$theme")}}/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/$theme")}}/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/$theme")}}/vendors/custom/vendors/fontawesome5/css/all.min.css" rel="stylesheet" type="text/css" />
    @yield('styles_optional_vendors')
    <!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="{{asset("assets/$theme")}}/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="{{asset("assets/$theme")}}/demo/default/skins/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="{{asset("assets/$theme")}}/demo/default/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="{{asset("assets/$theme")}}/demo/default/skins/brand/dark.css" rel="stylesheet" type="text/css" />
		<link href="{{asset("assets/$theme")}}/demo/default/skins/aside/dark.css" rel="stylesheet" type="text/css" />

		<!--end::Layout Skins -->
        <link rel="shortcut icon" href="{{asset("assets")}}/images/favicon.ico" />
        
        		<!--begin::Custom Global Theme Styles(used by all pages) -->
		<link href="{{asset("assets/css")}}/custom.style.css" rel="stylesheet" type="text/css" />
		<link href="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.css" rel="stylesheet" type="text/css" />
<style>
.iti--allow-dropdown{
    display: block !important;
	width: 100%;
	margin-top: 15px;
}
</style>
		<!--begin::Custom Global Theme Styles(used by all pages) -->
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor background-login" >
					<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
						<div class="kt-login__container">
							<div class="kt-login__logo">
								<a href="#">
                                    <img src="{{asset("assets")}}/images/logotipo_sicap_transparent.png" width="180">
								</a>
                            </div>
							@include("$theme/parts/alerts")
							
							
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<h3 class="kt-login__title">Crea tu cuenta</h3>
								</div>
                            <form class="kt-form" action="{{route('register_student')}}" method="POST" autocomplete="off">
                                @csrf
                                @method('post')
							     	<div class="input-group">
                                    <input class="form-control" type="text" placeholder="* Nombre" value="{{ old('name') }}" name="name" autocomplete="off" required/>
									</div>

									<div class="input-group">
                                    <input class="form-control" type="text" placeholder="* Apellido paterno" value="{{ old('paternal_surname') }}" name="paternal_surname" autocomplete="off" required/>
									</div>


									<div class="input-group">
                                    <input class="form-control" type="text" placeholder="* Apellido materno" value="{{ old('maternal_surname')}}" name="maternal_surname" autocomplete="off" required/>
									</div>

									<div class="form-group">
                                
                                    <select name="age" class="form-control " id="age" required>
                                    <option class="text-capitalize" value="" {{old('age')?'':'selected'}}  disabled>* Seleccione su edad</option>
                                    @foreach ($ages as $key => $item)
                                    <option class="text-capitalize" value="{{$key}}" {{old('age')? ($key==old('age'))? 'selected':'' :''}}>{{$item}}</option>
                                    @endforeach
                                    </select>
                                    </div>

									<div class="form-group">
      
                                    <select name="gender" class="form-control " id="gender" required>
                                    <option class="text-capitalize" value="" {{old('gender')?'':'selected'}}  disabled>* Seleccione el género</option>
                                    @foreach ($genders as $key => $item)
                                    <option class="text-capitalize" value="{{$key}}" {{old('gender')? ($key==old('gender'))? 'selected':'' :''}}>{{$item}}</option>
                                    @endforeach
                                    </select>
                                    </div>

									<div class="form-group">
                                   
                                    <select name="residence" class="form-control" id="residence" required>
                                    <option class="text-capitalize" value="" {{old('residence')?'':'selected'}}  disabled>* Seleccione el lugar de residencia</option>
                                    @foreach ($residences as $key => $item)
                                    <option class="text-capitalize" value="{{$key}}" {{old('residence')? ($key==old('residence'))? 'selected':'' :''}}>{{$item}}</option>
                                    @endforeach
                                    </select>
                                    </div>

									<div class="form-group">
              
                                    <select name="occupation" class="form-control" id="occupation" required>
                                    <option class="text-capitalize" value="" {{old('occupation')?'':'selected'}}  disabled>* Seleccione su ocupación</option>
                                    @foreach ($occupations as $key => $item)
                                    <option class="text-capitalize" value="{{$key}}" {{old('occupation')? ($key==old('occupation'))? 'selected':'' :''}}>{{$item}}</option>
                                    @endforeach
                                    </select>
                                    </div>

									<div class="form-group">
                                  
                                    <select name="level_study" class="form-control" id="level_study" required>
                                    <option class="text-capitalize" value="" {{old('level_study')?'':'selected'}}  disabled>* Seleccione su nivel de estudios</option>
                                    @foreach ($levels_study as $key => $item)
                                    <option class="text-capitalize" value="{{$key}}" {{old('level_study')? ($key==old('level_study'))? 'selected':'' :''}}>{{$item}}</option>
                                    @endforeach
                                    </select>
                                    </div>




									<div class="form-group">
                              
                                    <select name="program" class="form-control" id="program" required>
                                    <option class="text-capitalize" value="" {{old('program')?'':'selected'}}  disabled>* Seleccione cómo te enteraste del programa</option>
                                    @foreach ($programs as $key => $item)
                                    <option class="text-capitalize" value="{{$key}}" {{old('program')? ($key==old('program'))? 'selected':'' :''}}>{{$item}}</option>
                                    @endforeach
                                    </select>
                                    </div>


									<div class="input-group">
                                
                                     <input type="tel" name="tel" class="form-control" id="tel"    value="{{old('tel')}}" maxlength="10" placeholder="* Ingrese su télefono" autocomplete="new-password" required/>
                                     </div>

									 <div class="input-group">
                                    <input class="form-control" type="text" placeholder="* Email" value="{{ old('email')}}" name="email" autocomplete="off" required >
									</div>
									<div class="input-group">
                                    <input class="form-control" type="text" placeholder="Institución de procedencia" value="{{ old('institution_origin')}}" name="institution_origin" autocomplete="off" >
									</div>
                                    
													<!-- tutor -->
													<div id="tutor-part">
										
										<h5 class="mt-5 ml-2">Datos de tutor</h5>
										<hr class="white-text mb-2">
									<div class="input-group">
                                    <input class="form-control" type="text" placeholder="* Nombre" value="{{ old('tutor_name') }}" name="tutor_name" autocomplete="off" required/>
									</div>

									<div class="input-group">
                                    <input class="form-control" type="text" placeholder="* Apellido paterno" value="{{ old('tutor_paternal_surname') }}" name="tutor_paternal_surname" autocomplete="off" required/>
									</div>


									<div class="input-group">
                                    <input class="form-control" type="text" placeholder="* Apellido materno" value="{{ old('tutor_maternal_surname')}}" name="tutor_maternal_surname" autocomplete="off" required/>
									</div>

									<div class="input-group">
                                
								<input type="tel" name="tutor_tel_tmp" class="form-control" id="tutor-tel"    value="{{old('tutor_tel')}}" maxlength="10" placeholder="* Ingrese su télefono" autocomplete="new-password" required/>
								</div>

								<div class="input-group">
							   <input class="form-control" type="text" placeholder="* Email" value="{{ old('tutor_email')}}" name="tutor_email" autocomplete="off" required >
							   </div>
							   <hr class="text-white">
									</div>
					

									<div class="input-group">
										<input class="form-control" type="password" placeholder="* Tu contraseña" name="password" required/>
									</div>
									<div class="input-group">
										<input class="form-control" type="repeat-password" placeholder="* Confirmar contraseña" name="password_confirmation" autocomplete="new-password" required/>
									</div>
								
									<div class="row kt-login__extra">
								
										<div class="col kt-align-center">
										
											<a href="login" target="_self"  class="kt-login__link">Ya tengo una cuenta</a>
										
										</div>
									</div>
									<div class="kt-login__actions">
										<button id="kt_login_signin_submit" type="submit" class="btn btn-brand btn-pill kt-login__btn-primary">Registrarme</button>
									</div>
								</form>
							</div>



						
			
							<div class="kt-login__forgot">
								<div class="kt-login__head">
									<h3 class="kt-login__title">Olvidó su contraseña</h3>
									<div class="kt-login__desc">Ingrese su correo electrónico para restablecer su contraseña:</div>
								</div>
							       <form class="kt-form" action="{{ route('employee.password.email') }}" method="POST">
                                        @csrf
                                        @method('post')
									<div class="input-group">
										<input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" value="{{ (old('resetForm'))? old('email'):'' }}" autocomplete="off" required autofocus>
									</div>
                                         <input type="hidden" value="true" name="resetForm">
										 <script>
											var resetForm=@json(old('resetForm'));
										</script>
							
									<div class="kt-login__actions">
										<button id="kt_login_forgot_submit" type="submit" class="btn btn-brand btn-pill kt-login__btn-primary">Enviar</button>&nbsp;&nbsp;
										<button id="kt_login_forgot_cancel" class="btn btn-secondary btn-pill kt-login__btn-secondary">Cancelar</button>
									</div>
								</form>
							</div>
					
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Page -->

	
	<!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>

		<!-- end::Global Config -->
	

			<!--begin:: Global Mandatory Vendors -->
			<script src="{{asset("assets/$theme")}}/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
			
            <script src="{{asset("assets/$theme")}}/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
            <script src="{{asset("assets/$theme")}}/vendors/custom/components/vendors/jquery-validation/init.js" type="text/javascript"></script>
			<script src="{{asset("assets/$theme")}}/vendors/general/jquery-validation/dist/localization/messages_es.js" type="text/javascript"></script>

			<!--end:: Global Mandatory Vendors -->
			
	
			<!--begin::Global Theme Bundle(used by all pages) -->
			<script src="{{asset("assets/$theme")}}/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
	
			<!--end::Global Theme Bundle -->
	
			<!--begin::Page Scripts(used by this page) -->
			<script src="{{asset("assets")}}/js/register.js" type="text/javascript"></script>
	
			<!--end::Page Scripts -->
	
			<!--begin::Global App Bundle(used by all pages) -->
			<script src="{{asset("assets/$theme")}}/app/bundle/app.bundle.js" type="text/javascript"></script>
	
	
	        <script src="{{asset("assets/$theme")}}/vendors/general/intlTelInput/intlTelInput.js" type="text/javascript"></script>
		<!--end::Global App Bundle -->

		<script>
        var input = document.querySelector("#tel");
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
      initialCountry: "mx",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      //onlyCountries: ['mx','us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      preferredCountries: ['mx', 'us'],
      separateDialCode: true,
      utilsScript:'{{asset("assets/$theme")}}/vendors/general/intlTelInput/utils.js',
    });
	var input = document.querySelector("#tutor-tel");
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
      hiddenInput: "tutor_tel",
      initialCountry: "mx",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      //onlyCountries: ['mx','us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      preferredCountries: ['mx', 'us'],
      separateDialCode: true,
      utilsScript:'{{asset("assets/$theme")}}/vendors/general/intlTelInput/utils.js',
    });

    // Numeric only control handler
jQuery.fn.ForceNumericOnly =
function()
{
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
$("#tel").ForceNumericOnly();
$("#tutor-tel").ForceNumericOnly();
  </script>

	</body>

	<!-- end::Body -->
</html>