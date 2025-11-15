<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="">
		<title>Digipemad Project Management {{date('Y')}}</title>
		<link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicon_io/apple-touch-icon.png')}}">
    	<link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon_io/favicon-32x32.png')}}">
    	<link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon_io/favicon-16x16.png')}}">
    	<link rel="manifest" href="{{asset('favicon_io/site.webmanifest')}}">
    	<meta name="msapplication-TileImage" content="{{asset('images/mstile-150x150.png')}}">
    	<meta name="theme-color" content="#ffffff">
		<meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
		<meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta charset="utf-8" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
		<meta property="og:url" content="https://keenthemes.com/metronic" />
		<meta property="og:site_name" content="Keenthemes | Metronic" />

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

	    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
	    {{-- <link rel="stylesheet" href="{{asset('icpc/css/bootstrap-iconpicker.min.css')}}" /> --}}
	    <link href="{{asset('amsify/css/amsify.suggestags.css')}}" rel="stylesheet" />
	    <link href="https://cdn.jsdelivr.net/gh/sniperwolf/taggingJS/example/tag-basic-style.css" rel="stylesheet"/>
	    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css" />
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		

	   <!--  <link href="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/skins/ui/oxide/content.min.css" rel="stylesheet"> -->

	<!-- include summernote css/js -->
	    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
	   

	     <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	     <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery.cookie@1.4.1/jquery.cookie.min.js"></script>
	     <script src="{{asset('moment/moment.js')}}"></script>
	     <script src="{{asset('moment/moment-with-locales.js')}}"></script>
	     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
	     <script src="https://cdn.tiny.cloud/1/7jc8msr0tnfjp8i8cs2ohsuaqrfdsbwgfncvnawv9i3o41ev/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
	     <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

	    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
	    <link rel="stylesheet" href="{{asset('vendor/file-manager/css/file-manager.css') }}">

		<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Vendor Stylesheets(used by this page)-->
		<link href="{{asset('metronic/plugins/custom/leaflet/leaflet.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Page Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{asset('metronic/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('metronic/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		@auth
        	<meta name="oauth-token" content="{{ json_decode(Cookie::get(config('auth.cookie')))->access_token }}" />
    	@endauth
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}">
					<!--begin::Header container-->
					<div class="app-container container-xxl d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
						<!--begin::Header mobile toggle-->
						<div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
								<i class="ki-outline ki-abstract-14 fs-2"></i>
							</div>
						</div>
						<!--end::Header mobile toggle-->
						<!--begin::Logo-->
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-18">
							<a href="index.html">
								<img alt="Logo" src="{{asset('metronic/media/logos/demo34-small.svg')}}" class="h-25px d-sm-none" />
								<img alt="Logo" src="{{asset('metronic/media/logos/demo34.png')}}" class="h-25px d-none d-sm-block" />
							</a>
						</div>
						<!--end::Logo-->
						<!--begin::Header wrapper-->
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<!--begin::Menu wrapper-->
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch">
								<!--begin::Menu-->
								<div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu">
									<!--begin:Menu item-->
									<div class="menu-item {{str_contains(url()->full(), 'dashboard') ? 'here' : ''}} show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
										<!--begin:Menu link-->
										<span class="menu-link">
											<a href="{{route('management::dashboard')}}" class="menu-title">Dashboards</a>
											<span class="menu-arrow d-lg-none"></span>
										</span>
									</div>
								</div>

								<div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu">
									<!--begin:Menu item-->
									<div class="menu-item {{str_contains(url()->full(), 'inquiry') ? 'here' : ''}} show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
										<!--begin:Menu link-->
										<span class="menu-link">
											<a href="{{route('management::transaction.inquiry.index')}}" class="menu-title">Inquiry</a>
											<span class="menu-arrow d-lg-none"></span>
										</span>
									</div>
								</div>
									<!--end:Menu item-->
								<!--end::Menu-->
							</div>
							<!--end::Menu wrapper-->
							<!--begin::Navbar-->
							<div class="app-navbar flex-shrink-0">
								<!--begin::Notifications-->
								
								<div class="app-navbar-item ms-5" id="kt_header_user_menu_toggle">
									<!--begin::Menu wrapper-->
									<div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<img class="symbol symbol-circle symbol-35px symbol-md-40px" src="{{asset('metronic/media/avatars/no-image.png')}}" alt="user" />
									</div>
									<!--begin::User account menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<!--begin::Avatar-->
												<div class="symbol symbol-50px me-5">
													<img alt="Logo" src="{{asset('metronic/media/avatars/no-image.png')}}" />
												</div>
												<!--end::Avatar-->
												<!--begin::Username-->
												<div class="d-flex flex-column">
													<div class="fw-bold d-flex align-items-center fs-5">{{Auth::user()->name}} 
													<span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span></div>
													<a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{Auth::user()->email}} </a>
												</div>
												<!--end::Username-->
											</div>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="account/overview.html" class="menu-link px-5">My Profile</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="apps/projects/list.html" class="menu-link px-5">
												<span class="menu-text">My Projects</span>
												<span class="menu-badge">
													<span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span>
												</span>
											</a>
										</div>
									
						
										<div class="separator my-2"></div>
						
										<div class="menu-item px-5 my-1">
											<a href="account/settings.html" class="menu-link px-5">Account Settings</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="authentication/layouts/corporate/sign-in.html" class="menu-link px-5">Sign Out</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::User account menu-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::User menu-->
							</div>
							<!--end::Navbar-->
						</div>
						<!--end::Header wrapper-->
					</div>
					<!--end::Header container-->
				</div>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Toolbar-->
					<div id="kt_app_toolbar" class="app-toolbar py-6">
						<!--begin::Toolbar container-->
						<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
							<!--begin::Toolbar container-->
							<div class="d-flex flex-column flex-row-fluid">
								<!--begin::Toolbar wrapper-->
								<div class="d-flex align-items-center pt-1">
									<!--begin::Breadcrumb-->
									<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
										<!--begin::Item-->
										<li class="breadcrumb-item text-white fw-bold lh-1">
											<a href="index.html" class="text-white text-hover-primary">
												<i class="ki-outline ki-home text-gray-700 fs-6"></i>
											</a>
										</li>
										<!--end::Item-->
										<!--begin::Item-->
										<li class="breadcrumb-item">
											<i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
										</li>
										<!--end::Item-->
										<!--begin::Item-->
										<li class="breadcrumb-item text-white fw-bold lh-1">Dashboards</li>
										<!--end::Item-->
									</ul>
									<!--end::Breadcrumb-->
								</div>
								<!--end::Toolbar wrapper=-->
								<!--begin::Toolbar wrapper=-->
								<div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-13 pb-6">
									<!--begin::Page title-->
									<div class="page-title me-5">
										<!--begin::Title-->
										<h1 class="page-heading d-flex text-white fw-bold fs-2 flex-column justify-content-center my-0">Welcome back, {{Auth::user()->name}} 
										<!--begin::Description-->
										<span class="page-desc text-gray-600 fw-semibold fs-6 pt-3">Your are #1 seller across market’s Marketing Category</span>
										<!--end::Description--></h1>
										<!--end::Title-->
									</div>
									<!--end::Page title-->
									<!--begin::Stats-->
									<div class="d-flex align-self-center flex-center flex-shrink-0">
										<a href="#" class="btn btn-flex btn-sm btn-outline btn-active-color-primary btn-custom px-4" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
										<i class="ki-outline ki-plus-square fs-4 me-2"></i>Invite</a>
										<a href="#" class="btn btn-sm btn-active-color-primary btn-outline btn-custom ms-3 px-4" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">Set Your Target</a>
									</div>
									<!--end::Stats-->
								</div>
								<!--end::Toolbar wrapper=-->
							</div>
							<!--end::Toolbar container=-->
						</div>
						<!--end::Toolbar container-->
					</div>
					<!--end::Toolbar-->
					<!--begin::Wrapper container-->
					<div class="app-container container-xxl">
					    <!--begin::Main-->
					    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

							@yield('header')	

							
							@yield('content')	
							<!--end::Wrapper container-->

							 <div id="kt_app_footer" class="app-footer d-flex flex-column flex-md-row align-items-center flex-center flex-md-stack py-2 py-lg-4">
					            <!--begin::Copyright-->
					            <div class="text-gray-900 order-2 order-md-1">
					                <span class="text-muted fw-semibold me-1">2024&copy;</span>
					                <a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">Keenthemes</a>
					            </div>
					            <!--end::Copyright-->
					            <!--begin::Menu-->
					            <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
					                <li class="menu-item">
					                    <a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
					                </li>
					                <li class="menu-item">
					                    <a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
					                </li>
					                <li class="menu-item">
					                    <a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
					                </li>
					            </ul>
					            <!--end::Menu-->
					        </div>
					     </div>
					</div>
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Drawers-->
		<!--begin::Activities drawer-->
		<!--end::Chat drawer-->
		<!--end::Drawers-->
		<!--begin::Scrolltop-->
		
		@livewireScripts
		<script src="{{asset('metronic/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{asset('metronic/js/scripts.bundle.js')}}"></script>
		<script src="{{asset('metronic/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>

    	<script src="https://cdn.jsdelivr.net/npm/notiflix/dist/AIO/notiflix-aio-1.9.1.min.js"></script>

    	<script src="https://unpkg.com/codethereal-iconpicker@1.2.1/dist/iconpicker.js"></script>   
    	<script src="https://cdn.jsdelivr.net/gh/sniperwolf/taggingJS/tagging.min.js"></script>

    	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    	<script src="{{asset('amsify/js/jquery.amsify.suggestags.js')}}"></script>

		<script src="{{asset('vendor/file-manager/js/file-manager.js')}}"></script>
    	<script type="text/javascript" src="{{ asset('timepicker/jquery.datetimepicker.full.min.js') }}"></script>
    	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.9/dayjs.min.js"></script>
    	<script type="text/javascript" src="{{ asset('timepicker/timepickers.js') }}"></script>


 
    	<script src="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
   		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    	<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>

		<!--end::Scrolltop-->
		<!--end::Main-->
		<x-livewire-alert::scripts />
 
    <script type="text/javascript">

        (function(){

          $('#msbo').on('click', function(){
            $('body').toggleClass('msb-x');
          });

          $('#change_page').on('change', function(){
            cout = 10000
            expires = "; expires=" + cout;
            document.cookie = 'k_language'+' = '+$('#change_language').val()+ expires + "; path=/";

            if($(this).val() == '1'){
                
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_status'+' = '+'1'+ expires + "; path=/";

                location.reload()
            } else {
                
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_status'+' = '+'2'+ expires + "; path=/";

                location.reload()
            }
          })

            

          $('#change_language').on('change', function(){
            if($(this).val() == 'id'){
                
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_language'+' = '+'id'+ expires + "; path=/";

                location.reload()
            } else {
                
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_language'+' = '+'eng'+ expires + "; path=/";

                location.reload()
            }
          })

        }());
    </script>

		<script>var hostUrl = "{{asset('metronic/')}}";</script>
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->

		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{asset('metronic/js/widgets.bundle.js')}}"></script>
		<script src="{{asset('metronic/js/custom/widgets.js')}}"></script>
		<script src="{{asset('metronic/js/custom/apps/chat/chat.js')}}"></script>
		<script src="{{asset('metronic/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
		<script src="{{asset('metronic/js/custom/utilities/modals/new-target.js')}}"></script>
		<script src="{{asset('metronic/js/custom/utilities/modals/create-app.js')}}"></script>
		<script src="{{asset('metronic/js/custom/utilities/modals/users-search.js')}}"></script>
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->

		@stack('scripts')
	</body>
	<!--end::Body-->
</html>