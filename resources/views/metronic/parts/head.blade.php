<!DOCTYPE html>
<html lang="es">
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>@yield('title','WilcoERP')</title>
		<meta name="description" content="Wilco ERP">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<script src="{{asset("assets/$theme")}}/vendors/general/webfont/1.6.16/webfont.js"></script>
		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->
         @yield('styles_page_vendors')
		<!--end::Page Vendors Styles -->

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
		<link href="{{asset("assets/css")}}/pace.css" rel="stylesheet" type="text/css" />
		<!--begin::Custom Global Theme Styles(used by all pages) -->
	</head>

	<!-- end::Head -->
	<div class="overlay-pilates" id="overlay-pilates">
		<div class="img-overlay  d-flex justify-content-center align-items-center">
			<div class="text-center">
				<img class="img-overlay-1 m-4" src="{{asset("assets/images")}}/logo-white.png" alt="">
					<br>
				<img class="img-overlay-2" src="{{asset("assets/images")}}/loading.gif" alt="">
			</div>
		</div>
	</div>

	<!-- begin::Body -->
	<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading ">
		<!-- begin:: Page -->

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="index.html">
					<img  class="logo-dashboard-mobile" alt="Logo" src="{{asset("assets")}}/images/logo-white.png" />
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
			
				<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">