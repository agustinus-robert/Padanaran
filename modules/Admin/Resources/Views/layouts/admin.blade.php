<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>Shopper Company Profile - {{ date('Y') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="YSBY" />
    <meta name="keywords" content="yayasan, YSBY" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="YSBY" />
    <meta property="og:url" content="https://ysby.com" />
    <meta property="og:site_name" content="YSBY by PEMAD" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon_io/site.webmanifest') }}">
    <link rel="canonical" href="http://ysby.com/id" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="stylesheet" href="{{ asset('timepicker/jquery.datetimepicker.min.css') }}" />
    <link href="{{ asset('amsify/css/amsify.suggestags.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/sniperwolf/taggingJS/example/tag-basic-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
    <!--end::Fonts-->

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('ysby_asset/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ysby_asset/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('ysby_asset/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ysby_asset/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @auth
        <meta name="oauth-token" content="{{ json_decode(Cookie::get(config('auth.cookie')))->access_token }}" />
    @endauth
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-column-fluid flex-row">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" class="header">
                    <!--begin::Container-->
                    <div class="container-fluid d-flex flex-stack">
                        <!--begin::Brand-->
                        <div class="d-flex align-items-center me-5">
                            <!--begin::Aside toggle-->
                            <div class="d-lg-none btn-icon btn-active-color-white w-30px h-30px ms-n2 btn me-3" id="kt_aside_toggle">
                                <i class="ki-duotone ki-abstract-14 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <!--end::Aside  toggle-->
                            <!--begin::Logo-->
                            <a href="index.html">
                                <img alt="Logo" src="{{ asset('img/logo/icon-color.png') }}" class="h-30px h-lg-45px" />
                            </a>
                            <!--end::Logo-->
                            <!--begin::Nav-->
                            <div class="ms-md-10 ms-5">
                                <!--begin::Toggle-->
                                <button type="button" class="btn-flex btn-active-color-white align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between btn-color-white ps-md-6 pe-md-5 h-30px w-30px h-md-35px w-md-200px btn bg-white bg-opacity-10 px-0" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <span class="d-none d-md-inline"><?= @$_COOKIE['k_status'] == '1' || @$_COOKIE['k_status'] == '' ? 'Admininistrator' : (@$_COOKIE['k_status'] == '2' ? 'User' : '') ?></span>
                                    <i class="ki-duotone ki-down fs-4 ms-md-3 me-0 ms-2"></i>
                                </button>
                                <!--end::Toggle-->
                                <!--begin::Menu-->
                                <div class="menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-200px menu pb-3" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content fs-7 fw-bold px-3 py-4 text-gray-900">Select Role:</div>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator mb-3 opacity-75"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0)" data-stt="1" class="menu-link change_page px-3">Administrator</a>
                                    </div>

                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0)" data-stt="2" class="menu-link change_page px-3">User</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Nav-->
                        </div>
                        <!--end::Brand-->
                        <!--begin::Topbar-->
                        <div class="d-flex align-items-center flex-shrink-0">
                            <!--begin::Theme mode-->
                            <div class="d-flex align-items-center ms-1">
                                <!--begin::Menu toggle-->
                                <a href="#" class="btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-30px h-30px h-40px w-40px btn" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                    <i class="ki-duotone ki-night-day theme-light-show fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                        <span class="path7"></span>
                                        <span class="path8"></span>
                                        <span class="path9"></span>
                                        <span class="path10"></span>
                                    </i>
                                    <i class="ki-duotone ki-moon theme-dark-show fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </a>
                                <!--begin::Menu toggle-->
                                <!--begin::Menu-->
                                <div class="menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold fs-base w-150px menu py-4" data-kt-menu="true" data-kt-element="theme-mode-menu">
                                    <!--begin::Menu item-->
                                    <div class="menu-item my-0 px-3">
                                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                            <span class="menu-icon" data-kt-element="icon">
                                                <i class="ki-duotone ki-night-day fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                    <span class="path6"></span>
                                                    <span class="path7"></span>
                                                    <span class="path8"></span>
                                                    <span class="path9"></span>
                                                    <span class="path10"></span>
                                                </i>
                                            </span>
                                            <span class="menu-title">Light</span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item my-0 px-3">
                                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                            <span class="menu-icon" data-kt-element="icon">
                                                <i class="ki-duotone ki-moon fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                            <span class="menu-title">Dark</span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item my-0 px-3">
                                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                            <span class="menu-icon" data-kt-element="icon">
                                                <i class="ki-duotone ki-screen fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                            <span class="menu-title">System</span>
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Theme mode-->
                            <!--begin::User-->
                            <div class="d-flex align-items-center ms-1" id="kt_header_user_menu_toggle">
                                <!--begin::User info-->
                                <div class="btn-flex align-items-center bg-hover-white bg-hover-opacity-10 px-md-3 btn px-2 py-2" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                    <!--begin::Name-->
                                    <div class="d-none d-md-flex flex-column align-items-end justify-content-center me-md-4 me-2">
                                        <span class="text-muted fs-8 fw-semibold lh-1 mb-1">{{ Auth::user()->name }}</span>
                                        <span class="fs-8 fw-bold lh-1 text-white">{{ Auth::user()->email_address }}</span>
                                    </div>
                                    <!--end::Name-->
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-30px symbol-md-40px">
                                        <img src="{{ asset('image_globals/avatas.png') }}" alt="image" />
                                    </div>
                                    <!--end::Symbol-->
                                </div>
                                <!--end::User info-->
                                <!--begin::User account menu-->
                                <div class="menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-275px menu py-4" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content d-flex align-items-center px-3">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-50px me-5">
                                                <img alt="Logo" src="{{ asset('image_globals/avatas.png') }}" />
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Username-->
                                            <div class="d-flex flex-column">
                                                <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}
                                                    <span class="badge-light-success fw-bold fs-8 badge ms-2 px-2 py-1">Admin</span>
                                                </div>
                                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email_address }}</a>
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
                                        <a href="{{ route('admin::builder.account.index') }}" class="menu-link px-5">My Profile</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator my-2"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    {{--  <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                      <a href="#" class="menu-link px-5">
                        <span class="menu-title position-relative">Language
                        <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">English
                        <img class="w-15px h-15px rounded-1 ms-2" src="{{asset('ysby_asset/media/flags/united-states.svg')}}" alt="" /></span></span>
                      </a>
                      <!--begin::Menu sub-->
                      <div class="menu-sub menu-sub-dropdown w-175px py-4">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="account/settings.html" class="menu-link d-flex px-5 active">
                          <span class="symbol symbol-20px me-4">
                            <img class="rounded-1" src="{{asset('ysby_asset/media/flags/united-states.svg')}}" alt="" />
                          </span>English</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="account/settings.html" class="menu-link d-flex px-5">
                          <span class="symbol symbol-20px me-4">
                            <img class="rounded-1" src="{{asset('ysby_asset/media/flags/japan.svg')}}" alt="" />
                          </span>Indonesia</a>
                        </div>
                        <!--end::Menu item-->
                      </div>
                      <!--end::Menu sub-->
                    </div> --}}
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->

                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-5">
                                        <a href="javascript:;" onclick="signout()" class="menu-link px-5">Sign Out</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::User account menu-->
                            </div>
                            <!--end::User -->
                        </div>
                        <!--end::Topbar-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->
                <!--begin::Content wrapper-->
                <div class="d-flex flex-column-fluid">

                    <!--begin::Aside-->

                    @yield('navbars')
                    <!--end::Aside-->
                    <!--begin::Container-->
                    <div class="d-flex flex-column flex-column-fluid container-fluid">
                        <!--begin::Post-->
                        <div class="content flex-column-fluid" id="kt_content">
                            @yield('content')
                        </div>
                        <!--end::Post-->
                        <!--begin::Footer-->
                        <div class="d-flex flex-column flex-md-row flex-stack footer py-4" id="kt_footer">
                            <!--begin::Copyright-->
                            <div class="order-md-1 order-2 text-gray-900">
                                <span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span>
                                <a href="https://keenthemes.com" target="_blank" class="text-hover-primary text-gray-800">Administrator CMS</a>
                            </div>
                            <!--end::Copyright-->
                            <!--begin::Menu-->
                            <ul class="menu-gray-600 menu-hover-primary fw-semibold menu order-1">
                                <li class="menu-item">
                                    <a href="//pemad.id" target="_blank" class="menu-link px-2">About</a>
                                </li>
                                <li class="menu-item">
                                    <a href="//pemad.id" target="_blank" class="menu-link px-2">Support</a>
                                </li>
                            </ul>
                            <!--end::Menu-->
                        </div>
                        <!--end::Footer-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Content wrapper-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    <!--end::Main-->
    @auth
        @if (Route::has(config('modules.auth.signout.route')))
            <script>
                const signout = (e) => {
                    if (confirm('Apakah Anda yakin?')) {
                        document.getElementById('signout-form').submit();
                    }
                }
            </script>
            <form class="form-block form-confirm" id="signout-form" action="{{ route(config('modules.auth.signout.route')) }}" method="POST" style="display: none;"> @csrf </form>
        @endif
    @endauth

    @livewireScripts

    <script src="{{ asset('moment/moment.js') }}"></script>
    <script src="{{ asset('moment/moment-with-locales.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src=" https://cdn.jsdelivr.net/npm/chart.js@4.4.5/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/react@17.0.2/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17.0.2/umd/react-dom.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notiflix/dist/AIO/notiflix-aio-1.9.1.min.js"></script>

    <script src="https://unpkg.com/codethereal-iconpicker@1.2.1/dist/iconpicker.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/sniperwolf/taggingJS/tagging.min.js"></script>


    <!--begin::Javascript-->
    <script>
        var hostUrl = "{{ asset('') }}";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('ysby_asset/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('ysby_asset/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('ysby_asset/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
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
    <script src="{{ asset('ysby_asset/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('ysby_asset/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('ysby_asset/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('ysby_asset/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ asset('ysby_asset/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('ysby_asset/js/custom/utilities/modals/users-search.js') }}"></script>
    <script type="text/javascript" src="{{ asset('timepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.9/dayjs.min.js"></script>
    <script type="text/javascript" src="{{ asset('timepicker/timepickers.js') }}"></script>
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <x-livewire-alert::scripts />
    <script type="text/javascript">
        (function() {
            $('#msbo').on('click', function() {
                $('body').toggleClass('msb-x');
            });

            $(document).ready(function() {
                $('.select-2').select2({
                    theme: 'bootstrap-5',
                    minimumInputLength: 3
                });
            });

            $('.change_page').on('click', function() {
                cout = 10000
                expires = "; expires=" + cout;
                document.cookie = 'k_language' + ' = ' + 'id' + expires + "; path=/";

                if ($(this).data('stt') == '1') {

                    cout = 10000
                    expires = "; expires=" + cout;
                    document.cookie = 'k_status' + ' = ' + '1' + expires + "; path=/";

                    window.location.href = "{{ route('admin::dashboard-cms') }}"
                }
            })

            $('#change_language').on('change', function() {
                if ($(this).val() == 'id') {

                    cout = 10000
                    expires = "; expires=" + cout;
                    document.cookie = 'k_language' + ' = ' + 'id' + expires + "; path=/";

                    location.reload()
                } else {

                    cout = 10000
                    expires = "; expires=" + cout;
                    document.cookie = 'k_language' + ' = ' + 'en' + expires + "; path=/";

                    location.reload()
                }
            })

        }());
    </script>

    @stack('scripts')
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
