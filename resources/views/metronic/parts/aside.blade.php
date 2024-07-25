<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
    <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
        <div class="row w-100 h-100">
            <div class="col-10 col-xl-10 d-flex justify-content-center align-items-center">
                <a href="{{route('dashboard')}}">
                    <img class="logo-dashboard-long" alt="Logo" src="{{asset("assets")}}/images/logo-white.png" />
                </a>
            </div>
            <div class="col-2 col-xl-2 d-flex justify-content-center  align-items-center">
                <div class="kt-aside__brand-tools">
                    <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                                <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                                <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                                </g>
                            </svg>
                        </span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" />
                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                </g>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
            <ul class="kt-menu__nav">
                <!-- dashboard -->
                <li class="kt-menu__item {{ SICAPHelper::getMenuEnable('dashboard')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true">
                    <a href="{{route('dashboard')}}" class="kt-menu__link "><span class="kt-menu__link-icon">
                        <svg fill="#ffffff" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" id="dashboard" class="icon glyph"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><rect x="2" y="2" width="9" height="11" rx="2"></rect><rect x="13" y="2" width="9" height="7" rx="2"></rect><rect x="2" y="15" width="9" height="7" rx="2"></rect><rect x="13" y="11" width="9" height="11" rx="2"></rect></g></svg></span><span class="kt-menu__link-text">Dashboard</span>
                    </a>
                </li>

                @if(SICAPHelper::checkPermission(1,0))
                <!-- users -->
                <li class="kt-menu__item {{ SICAPHelper::getMenuEnable('users')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true">
                    <a href="{{route('users')}}" class="kt-menu__link "><span class="kt-menu__link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                            </g>
                        </svg></span><span class="kt-menu__link-text">Users</span>
                    </a>
                </li>
                @endif

                @if(SICAPHelper::checkPermission(3,0)||
                SICAPHelper::checkPermission(9,0)||
                SICAPHelper::checkPermission(10,0)||
                SICAPHelper::checkPermission(11,0))
                <!-- vehicle -->
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon">
                    <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="#ffffff" d="M60,28c0-8.301-5.016-24-24-24h-8C9.016,4,4,19.699,4,28c-2.211,0-4,1.789-4,4v16c0,2.211,1.789,4,4,4h4v4 c0,2.211,1.789,4,4,4h4c2.211,0,4-1.789,4-4v-4h24v4c0,2.211,1.789,4,4,4h4c2.211,0,4-1.789,4-4v-4h4c2.211,0,4-1.789,4-4V32 C64,29.789,62.211,28,60,28z M16,44c-2.211,0-4-1.789-4-4s1.789-4,4-4s4,1.789,4,4S18.211,44,16,44z M12,28c0-0.652,0.184-16,16-16 h8c15.41,0,15.984,14.379,16,16H12z M48,44c-2.211,0-4-1.789-4-4s1.789-4,4-4s4,1.789,4,4S50.211,44,48,44z"></path> </g></svg>
                    </span><span class="kt-menu__link-text">Vehicle Manager</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            @if(SICAPHelper::checkPermission(3,0))
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('trucks_cars')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('trucks_cars')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Vehicles</span></a></li>
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('trailers')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('trailers')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Trailers</span></a></li>
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('equipment')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('equipment')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Equipment</span></a></li>
                            @endif
                            @if(SICAPHelper::checkPermission(9,0))
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('interfaces')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('interfaces')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Inspection Items</span></a></li>
                            @endif
                            @if(SICAPHelper::checkPermission(10,0))
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('request_categories')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('request_categories')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Request categories</span></a></li>
                            @endif
                            @if(SICAPHelper::checkPermission(11,0))
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('fleet_manager_services')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('fleet_manager_services')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Services</span></a></li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(SICAPHelper::checkPermission(13,0)||SICAPHelper::checkPermission(15,0))
                <!-- inventory -->
                <li class="kt-menu__item kt-menu__item--submenu kt-menu__item--open" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                    <a href="{{route('tools')}}" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="#000000" d="M10.3 8.2l-0.9 0.9 0.9 0.9-1.2 1.2 4.3 4.3c0.6 0.6 1.5 0.6 2.1 0s0.6-1.5 0-2.1l-5.2-5.2zM14.2 15c-0.4 0-0.8-0.3-0.8-0.8 0-0.4 0.3-0.8 0.8-0.8s0.8 0.3 0.8 0.8c0 0.5-0.3 0.8-0.8 0.8z"></path> <path fill="#000000" d="M3.6 8l0.9-0.6 1.5-1.7 0.9 0.9 0.9-0.9-0.1-0.1c0.2-0.5 0.3-1 0.3-1.6 0-2.2-1.8-4-4-4-0.6 0-1.1 0.1-1.6 0.3l2.9 2.9-2.1 2.1-2.9-2.9c-0.2 0.5-0.3 1-0.3 1.6 0 2.1 1.6 3.7 3.6 4z"></path> <path fill="#000000" d="M8 10.8l0.9-0.8-0.9-0.9 5.7-5.7 1.2-0.4 1.1-2.2-0.7-0.7-2.3 1-0.5 1.2-5.6 5.7-0.9-0.9-0.8 0.9c0 0 0.8 0.6-0.1 1.5-0.5 0.5-1.3-0.1-2.8 1.4-0.5 0.5-2.1 2.1-2.1 2.1s-0.6 1 0.6 2.2 2.2 0.6 2.2 0.6 1.6-1.6 2.1-2.1c1.4-1.4 0.9-2.3 1.3-2.7 0.9-0.9 1.6-0.2 1.6-0.2zM4.9 10.4l0.7 0.7-3.8 3.8-0.7-0.7z"></path> </g></svg>
                        </span>
                        <span class="kt-menu__link-text">Inventory Manager</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            @if(SICAPHelper::checkPermission(13,0))
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('tools_fleet')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('tools_fleet')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Vehicle</span></a></li>
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('tools_office')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('tools_office')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Office</span></a></li>
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('tools_shop')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('tools_shop')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Shop</span></a></li>
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('tools_general')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('tools_general')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">General Tools</span></a></li>
                            @endif
                            @if(SICAPHelper::checkPermission(15,0))
                            <li class="kt-menu__item {{SICAPHelper::getMenuEnable('rental')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true"><a href="{{route('rental')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Rentals</span></a></li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(SICAPHelper::checkPermission(16,0))
                <!-- reports -->
                <li class="kt-menu__item {{ SICAPHelper::getMenuEnable('reports')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true">
                    <a href="{{route('reports')}}" class="kt-menu__link ">
                        <span class="kt-menu__link-icon">
                            <svg fill="#ffffff" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M54.193,12.87c-0.731,0-1.325,0.594-1.325,1.326c0,0.068,0.029,0.127,0.039,0.193h-0.039v31.438 c0,0.732,0.594,1.326,1.325,1.326H85.63v-0.039c0.066,0.01,0.125,0.039,0.194,0.039c0.731,0,1.325-0.594,1.325-1.326 C86.961,27.707,72.313,13.059,54.193,12.87z"></path> </g> <g> <path d="M79.485,53.46c0-0.732-0.593-1.326-1.325-1.326H49.261c-0.732,0-1.325-0.594-1.325-1.326V22.015h-0.039 c0.01-0.066,0.039-0.125,0.039-0.193c0-0.733-0.594-1.326-1.326-1.326c-0.032,0-0.058,0.016-0.089,0.018v-0.009 c-0.118-0.001-0.235-0.009-0.353-0.009c-18.4,0-33.317,14.917-33.317,33.317c0,18.4,14.917,33.317,33.317,33.317 s33.317-14.917,33.317-33.317c0-0.106-0.005-0.211-0.007-0.318C79.478,53.482,79.485,53.472,79.485,53.46z"></path> </g> </g> </g></svg>
                        </span>
                        <span class="kt-menu__link-text">Reports</span>
                    </a>
                </li>
                @endif

                @if(SICAPHelper::checkRol('super_admin')||SICAPHelper::checkPermission(8,0))
                <!-- settings -->
                <li class="kt-menu__item {{ SICAPHelper::getMenuEnable('settings')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true">
                    <a href="{{route('settings')}}" class="kt-menu__link "><span class="kt-menu__link-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7848 0.449982C13.8239 0.449982 14.7167 1.16546 14.9122 2.15495L14.9991 2.59495C15.3408 4.32442 17.1859 5.35722 18.9016 4.7794L19.3383 4.63233C20.3199 4.30175 21.4054 4.69358 21.9249 5.56605L22.7097 6.88386C23.2293 7.75636 23.0365 8.86366 22.2504 9.52253L21.9008 9.81555C20.5267 10.9672 20.5267 13.0328 21.9008 14.1844L22.2504 14.4774C23.0365 15.1363 23.2293 16.2436 22.7097 17.1161L21.925 18.4339C21.4054 19.3064 20.3199 19.6982 19.3382 19.3676L18.9017 19.2205C17.1859 18.6426 15.3408 19.6754 14.9991 21.405L14.9122 21.845C14.7167 22.8345 13.8239 23.55 12.7848 23.55H11.2152C10.1761 23.55 9.28331 22.8345 9.08781 21.8451L9.00082 21.4048C8.65909 19.6754 6.81395 18.6426 5.09822 19.2205L4.66179 19.3675C3.68016 19.6982 2.59465 19.3063 2.07505 18.4338L1.2903 17.1161C0.770719 16.2436 0.963446 15.1363 1.74956 14.4774L2.09922 14.1844C3.47324 13.0327 3.47324 10.9672 2.09922 9.8156L1.74956 9.52254C0.963446 8.86366 0.77072 7.75638 1.2903 6.8839L2.07508 5.56608C2.59466 4.69359 3.68014 4.30176 4.66176 4.63236L5.09831 4.77939C6.81401 5.35722 8.65909 4.32449 9.00082 2.59506L9.0878 2.15487C9.28331 1.16542 10.176 0.449982 11.2152 0.449982H12.7848ZM12 15.3C13.8225 15.3 15.3 13.8225 15.3 12C15.3 10.1774 13.8225 8.69998 12 8.69998C10.1774 8.69998 8.69997 10.1774 8.69997 12C8.69997 13.8225 10.1774 15.3 12 15.3Z" fill="#000000"></path> </g></svg>    </span><span class="kt-menu__link-text">Settings</span>
                    </a>
                </li>
                @endif

                <!-- notification -->
                <li class="kt-menu__item {{ SICAPHelper::getMenuEnable('notification')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true">
                    <a href="{{route('notification')}}" class="kt-menu__link "><span class="kt-menu__link-icon">
                        <svg fill="#ffffff" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M10,20h4a2,2,0,0,1-4,0Zm8-4V10a6,6,0,0,0-5-5.91V3a1,1,0,0,0-2,0V4.09A6,6,0,0,0,6,10v6L4,18H20Z"></path></g></svg>
                    </span><span class="kt-menu__link-text">Notifications</span></a>
                </li>

                <!-- logout -->
                <li class="kt-menu__item {{ SICAPHelper::getMenuEnable('admin_invoice')? 'kt-menu__item--active ' : '' }}" aria-haspopup="true">
                    <a href="{{route('logout')}}" class="kt-menu__link "><span class="kt-menu__link-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M22 6.62219V17.245C22 18.3579 21.2857 19.4708 20.1633 19.8754L15.0612 21.7977C14.7551 21.8988 14.449 22 14.0408 22C13.5306 22 12.9184 21.7977 12.4082 21.4942C12.2041 21.2918 11.898 21.0895 11.7959 20.8871H7.91837C6.38776 20.8871 5.06122 19.6731 5.06122 18.0544V17.0427C5.06122 16.638 5.36735 16.2333 5.87755 16.2333C6.38776 16.2333 6.69388 16.5368 6.69388 17.0427V18.0544C6.69388 18.7626 7.30612 19.2684 7.91837 19.2684H11.2857V4.69997H7.91837C7.20408 4.69997 6.69388 5.20582 6.69388 5.91401V6.9257C6.69388 7.33038 6.38776 7.73506 5.87755 7.73506C5.36735 7.73506 5.06122 7.33038 5.06122 6.9257V5.91401C5.06122 4.39646 6.28572 3.08125 7.91837 3.08125H11.7959C12 2.87891 12.2041 2.67657 12.4082 2.47423C13.2245 1.96838 14.1429 1.86721 15.0612 2.17072L20.1633 4.09295C21.1837 4.39646 22 5.50933 22 6.62219Z" fill="#ffffff"></path> <path d="M4.85714 14.8169C4.65306 14.8169 4.44898 14.7158 4.34694 14.6146L2.30612 12.5912C2.20408 12.49 2.20408 12.3889 2.10204 12.3889C2.10204 12.2877 2 12.1865 2 12.0854C2 11.9842 2 11.883 2.10204 11.7819C2.10204 11.6807 2.20408 11.5795 2.30612 11.5795L4.34694 9.55612C4.65306 9.25261 5.16327 9.25261 5.46939 9.55612C5.77551 9.85963 5.77551 10.3655 5.46939 10.669L4.7551 11.3772H8.93878C9.34694 11.3772 9.7551 11.6807 9.7551 12.1865C9.7551 12.6924 9.34694 12.7936 8.93878 12.7936H4.65306L5.36735 13.5017C5.67347 13.8052 5.67347 14.3111 5.36735 14.6146C5.26531 14.7158 5.06122 14.8169 4.85714 14.8169Z" fill="#ffffff"></path> </g></svg>
                    </span><span class="kt-menu__link-text">Logout</span></a>
                </li>
            </ul>
        </div>
    </div>
    <input type="hidden" id="___p" value="{{ Auth::user()->permissions->map(function ($p){
        return [
            'id' => $p->id,
            'read' => $p->read,
            'write' => $p->write,
            'create' => $p->create,
            'delete' => $p->delete
        ];
    }) }}"/>
</div>