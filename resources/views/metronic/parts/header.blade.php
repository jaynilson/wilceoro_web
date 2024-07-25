@php 
$notifications=SICAPHelper::getNotificationsUser(auth()->user()->id,auth()->user()->id_rol); 
$notificationsNoRead=SICAPHelper::getNotificationsNoRead(auth()->user()->id,auth()->user()->id_rol); 
@endphp
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
    <!-- begin:: Header -->
    <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

        <!-- begin:: Header Menu -->
        <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
        <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
       
        </div>

        <!-- end:: Header Menu -->

        <!-- begin:: Header Topbar -->
        <div class="kt-header__topbar">
            <!--begin: Notifications -->
            <div class="kt-header__topbar-item dropdown">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
                    <div id="count-notifications">{{$notificationsNoRead}}</div>
                    <span class="kt-header__topbar-icon kt-pulse kt-pulse--brand">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <path d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z" id="Combined-Shape" fill="#000000"/>
                                <rect id="Rectangle-23" fill="#000000" opacity="0.3" x="10" y="16" width="4" height="4" rx="2"/>
                            </g>
                        </svg> <span class="kt-pulse__ring"></span>
                    </span>
                </div>
                <div id="menu-notifications" class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg">
                    <form>

                        <!--begin: Head -->
                        <div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b pt-0 p-5" style="background-image: url({{asset("assets/$theme")}}/media/misc/bg-1.jpg);padding:10px !important;">
                            <h3 class="kt-head__title" id="container-new-notifications">
Notifications
                          
                                &nbsp;
                                <a href="{{route('notification')}}"> <span class="btn btn-danger btn-sm btn-bold btn-font-md">  See all </span></a>
                             
                            </h3>
                           {{-- <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications" role="tab" aria-selected="true">Alerts</a>
                                </li>
                                 <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#topbar_notifications_events" role="tab" aria-selected="false">Events</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs" role="tab" aria-selected="false">Logs</a>
                                </li>
                            </ul> --}}
                        </div>

                        <!--end: Head -->
                       
                        <div class="tab-content">
                            <div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
                                <div id="container-notifications" class="kt-notification kt-margin-t-0 kt-margin-b-0 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
                                 
                                    @foreach($notifications as $notification) 
                                    <a href="#"  onclick="readOpenNotification({{$notification->id}},this)" class="kt-notification__item {{($notification->status=='no_read')?'no_read':''}}">
                                        {{-- <div class="kt-notification__item-icon">
                                            @if($notification->type_icon=='html-class')
                                           <i class="{{$notification->icon}}"></i>
                                            @endif
                                        </div> --}}
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                {{$notification->title}}
                                            </div>
                                            <div class="kt-notification__item-time msg-notification">
                                                {{$notification->message}}
                                            </div>
                                            <div class="kt-notification__item-time time-notification">
                                                {{$notification->date}}
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach   
                                    @if($notifications->count()<=0)
                                    <div id="default-notification-text" class="w-100 h-100 d-flex justify-content-center align-items-center"><span class="kt-grid-nav__desc">Nínguna notificación para mostrar.</span> </div>
                                    @endif
                                    {{-- <a href="#" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-time kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title">
                                                Falta injustificada
                                            </div>
                                            <div class="kt-notification__item-time">
                                                Empleado: Neha Murazik Dooley
                                            </div>
                                        </div>
                                    </a> --}}

                                    <div>

                                    </div>
                                  
                              
                                </div>
                            </div>
                        
                            
                        </div>
                    </form>
                </div>
            </div>

            <!--end: Notifications -->
        



            <!--end: Quick Actions --> 

    

            <!--begin: User Bar -->
            <div class="kt-header__topbar-item kt-header__topbar-item--user">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                    <div class="kt-header__topbar-user">
                        <span class="kt-header__topbar-welcome kt-hidden-mobile"><strong>Hola,</strong></span>
                    <span class="kt-header__topbar-username kt-hidden-mobile">{{ auth()->user()->name }}</span>
                        <img class="kt-hidden" alt="Pic" src="{{asset("assets/$theme")}}/media/users/300_25.jpg" />

                        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                        <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold picture-profile-span">
                                <img  class="picture-profile w-100" src="{{ (empty(auth()->user()->picture))? asset("assets/images/user_default.png") : Storage::url("images/profiles/".auth()->user()->picture) }}"  />   
                               
                        </span>
                    </div>
                </div>
                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

                    <!--begin: Head -->
                    <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{asset("assets/$theme")}}/media/misc/bg-1.jpg)">
                        <div class="kt-user-card__avatar">
                            <img class="kt-hidden" alt="Pic" src="{{asset("assets/$theme")}}/media/users/300_25.jpg" />

                            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                            <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success picture-profile-span">
                                    <img  class="picture-profile" src="{{ (empty(auth()->user()->picture))? asset("assets/images/user_default.png") : Storage::url("images/profiles/".auth()->user()->picture) }}"  />  
                            </span>
                        </div>
                        <div class="kt-user-card__name">
                                {{ auth()->user()->name.' '.auth()->user()->last_name }}
                              
                        </div>
                   
                    </div>

                    <!--end: Head -->

                    <!--begin: Navigation -->
                    <div class="kt-notification">
                        <div class="kt-user-card__badge w-100 text-center m-2">
                        <span class="btn btn-success btn-sm btn-bold btn-font-md text-capitalize">{{SICAPHelper::getNameRolById(auth()->user()->id_rol)}}</span>
                        </div>
                   
                    <a href="{{route('user_profile')}}" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-calendar-3 kt-font-brand"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    My profile
                                </div>
                                <div class="kt-notification__item-time">
                                    {{-- Configuraciones de cuenta y más --}}
                                </div>
                            </div>
                        </a>

                    
  

{{--                   
                    <a  class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon-calendar-2 kt-font-brand"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title kt-font-bold">
                                    Mi horario
                                </div>
                                <div class="kt-notification__item-time">
                                  Mi horario de trabajo y vacaciónes
                                </div>
                            </div>
                        </a> --}}
                        <div class="kt-notification__custom">
                        <a href="{{route('logout')}}"  class="btn btn-brand btn-elevate btn-icon-sm">Cerrar sesión</a>
                        </div>
                    </div>

                    <!--end: Navigation -->
                </div>
            </div>

            <!--end: User Bar -->
        </div>

        <!-- end:: Header Topbar -->
    </div>

    <!-- end:: Header -->
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
<!-- begin:: Subheader -->
        {{-- <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            @include("$theme/parts/breadcrumbs")
        </div> --}}
        <!-- end:: Subheader -->