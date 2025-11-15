<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Admin Shopper | Dashboard</title><!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE v4 | Dashboard">
    <meta name="author" content="ColorlibHQ">
    <link rel="icon" type="image/png" href="{{ asset('icons/medic.png') }}">
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"><!--end::Primary Meta Tags--><!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous"><!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <style>
        .bg-body-secondary-2 {
            background-color: #000;
        }

        .sidebar-brand {
            border-bottom: 1px solid #a3bdd8;
        }

        .sidebar-wrapper .nav-treeview>.nav-item>.nav-link {
            color: #e9f1ff;
        }

        [data-bs-theme="dark"].app-sidebar {
            --lte-sidebar-color: white;
        }

        .navbar-dark {
            --bs-navbar-color: white;
        }

        .bg-body-2 {
            border-left: 1px solid #a3bdd8;
            background-color: #FF6347;
            color: white;
        }

        .bg-body-3 {
            border-left: 1px solid #a3bdd8;
            background-color: #007BFF;
        }

        .bg-body-4 {
            border-left: 2px solid #464646;
            background-color: #000;
        }

        .w10 {
            width: 10px;
        }

        .w20 {
            width: 20px;
        }

        .w30 {
            width: 30px;
        }

        .w40 {
            width: 40px;
        }

        .w50 {
            width: 50px;
        }
    </style>
</head> <!--end::Head--> <!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
        <nav class="app-header navbar-expand navbar-dark bg-body-3 navbar"> <!--begin::Container-->
            <div class="container-fluid"> <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="javascript:void(0)" role="button"> <i class="bi bi-list"></i> </a> </li>
                    {{-- <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Home</a> </li>
                    <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Contact</a> </li> --}}
                </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto"> <!--begin::Navbar Search-->
                    {{-- <li class="nav-item"> <a class="nav-link" data-widget="navbar-search" href="#" role="button"> <i class="bi bi-search"></i> </a> </li> <!--end::Navbar Search--> <!--begin::Messages Dropdown Menu--> --}}
                    {{-- <li class="nav-item dropdown"> <a class="nav-link" data-bs-toggle="dropdown" href="#"> <i class="bi bi-chat-text"></i> <span class="navbar-badge text-bg-danger badge">3</span> </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <a href="#" class="dropdown-item"> <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0"> <img src="../../dist/assets/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3"> </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="fs-7 text-danger float-end"><i class="bi bi-star-fill"></i></span>
                                        </h3>
                                        <p class="fs-7">Call me whenever you can...</p>
                                        <p class="fs-7 text-secondary"> <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div> <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"> <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0"> <img src="../../dist/assets/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3"> </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            John Pierce
                                            <span class="fs-7 float-end text-secondary"> <i class="bi bi-star-fill"></i> </span>
                                        </h3>
                                        <p class="fs-7">I got your message bro</p>
                                        <p class="fs-7 text-secondary"> <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div> <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"> <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0"> <img src="../../dist/assets/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3"> </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Nora Silvester
                                            <span class="fs-7 float-end text-warning"> <i class="bi bi-star-fill"></i> </span>
                                        </h3>
                                        <p class="fs-7">The subject goes here</p>
                                        <p class="fs-7 text-secondary"> <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div> <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li> --}}
                    <!--end::Messages Dropdown Menu--> <!--begin::Notifications Dropdown Menu-->
                    {{-- <li class="nav-item dropdown"> <a class="nav-link" data-bs-toggle="dropdown" href="#"> <i class="bi bi-bell-fill"></i> <span class="navbar-badge text-bg-warning badge">15</span> </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"> <i class="bi bi-envelope me-2"></i> 4 new messages
                                <span class="fs-7 float-end text-secondary">3 mins</span> </a>
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"> <i class="bi bi-people-fill me-2"></i> 8 friend requests
                                <span class="fs-7 float-end text-secondary">12 hours</span> </a>
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"> <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                                <span class="fs-7 float-end text-secondary">2 days</span> </a>
                            <div class="dropdown-divider"></div> <a href="#" class="dropdown-item dropdown-footer">
                                See All Notifications
                            </a>
                        </div>
                    </li> <!--end::Notifications Dropdown Menu--> <!--begin::Fullscreen Toggle--> --}}
                    <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i> </a> </li> <!--end::Fullscreen Toggle--> <!--begin::User Menu Dropdown-->
                    <li class="nav-item user-menu dropdown"> <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> <img src="{{ asset('img/avatars/1.jpg') }}" class="user-image rounded-circle shadow" alt="User Image"> <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <!--begin::User Image-->
                            <li class="user-header text-bg-primary"> <img src="{{ asset('img/avatars/1.jpg') }}" class="rounded-circle shadow" alt="User Image">
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Member since Nov. 2024</small>
                                </p>
                            </li> <!--end::User Image--> <!--begin::Menu Body-->

                            <li class="user-footer"> <a href="#" class="btn-default btn-flat btn">Profile</a>

                                <button onclick="signout()" class="btn-default btn-flat btn float-end">Sign out</a>
                            </li> <!--end::Menu Footer-->
                        </ul>
                    </li> <!--end::User Menu Dropdown-->
                </ul> <!--end::End Navbar Links-->
            </div> <!--end::Container-->
        </nav> <!--end::Header--> <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-3 shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
            <div class="sidebar-brand"> <!--begin::Brand Link--> <a href="./index.html" class="brand-link"> <!--begin::Brand Image--> <img src="{{ asset('icons/stethoscope.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> <!--end::Brand Image--> <!--begin::Brand Text--> <span class="brand-text fw-light">Shopper Medical</span> <!--end::Brand Text--> </a> <!--end::Brand Link--> </div> <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2"> <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                        <li class="nav-item"> <a href="{{ route('poz::dashboard') }}" class="nav-link"> <i class="nav-icon bi bi-speedometer"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item {{ in_array(Route::currentRouteName(), ['poz::transaction.product.index', 'poz::transaction.product.create', 'poz::transaction.product.edit']) ? 'menu-open' : '' }}"> <a href="#" class="nav-link"> <i class="nav-icon bi bi-box-seam-fill"></i>
                                <p>
                                    Produk
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> <a href="{{ route('poz::transaction.product.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::transaction.product.index', 'poz::transaction.product.create', 'poz::transaction.product.edit']) ? 'active' : '' }}">
                                        <p>Kelola Produk</p>
                                    </a> </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ in_array(Route::currentRouteName(), ['poz::transaction.sale.index', 'poz::transaction.sale.create', 'poz::transaction.sale.edit']) ? 'menu-open' : '' }}"> <a href="#" class="nav-link"> <i class="nav-icon fa fa-shopping-cart"></i>
                                <p>
                                    Penjualan
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> <a href="{{ route('poz::transaction.pos-sale.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::transaction.pos-sale.index']) ? 'active' : '' }}">
                                        <p>Penjualan POS</p>
                                    </a>
                                </li>

                                <li class="nav-item"> <a href="{{ route('poz::transaction.sale.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::transaction.sale.index', 'poz::transaction.sale.create', 'poz::transaction.sale.edit']) ? 'active' : '' }}">
                                        <p>Penjualan Reguler</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ in_array(Route::currentRouteName(), ['poz::transaction.transfer.index', 'poz::transaction.transfer.create', 'poz::transaction.transfer.edit']) ? 'menu-open' : '' }}"> <a href="#" class="nav-link"> <i class="nav-icon fa fa-exchange"></i>
                                <p>
                                    Transfer
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> <a href="{{ route('poz::transaction.transfer.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::transaction.transfer.index', 'poz::transaction.transfer.create', 'poz::transaction.transfer.edit']) ? 'active' : '' }}">
                                        <p>Transfer Gudang</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ in_array(Route::currentRouteName(), ['poz::transaction.purchase.index', 'poz::transaction.purchase.create', 'poz::transaction.purchase.edit']) ? 'menu-open' : '' }}"> <a href="#" class="nav-link"> <i class="nav-icon fa-solid fa-bag-shopping"></i>
                                <p>
                                    Pembelian
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> <a href="{{ route('poz::transaction.purchase.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::transaction.purchase.index', 'poz::transaction.purchase.create', 'poz::transaction.purchase.edit']) ? 'active' : '' }}">
                                        <p>Pembelian Produk</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ in_array(Route::currentRouteName(), [
                                'poz::master.brand.index',
                                'poz::master.brand.create',
                                'poz::master.brand.edit',
                                'poz::master.category.index',
                                'poz::master.category.create',
                                'poz::master.category.edit',
                                'lessons.show',
                                'poz::master.unit.index',
                                'poz::master.unit.create',
                                'poz::master.unit.edit',
                                'poz::master.tax.index',
                                'poz::master.tax.create',
                                'poz::master.tax.edit',
                                'poz::master.warehouse.index',
                                'poz::master.warehouse.create',
                                'poz::master.warehouse.edit',
                                'poz::master.outlet.index',
                                'poz::master.outlet.create',
                                'poz::master.outlet.edit',
                                'poz::master.casier.index',
                                'poz::master.casier.create',
                                'poz::master.casier.edit',
                            ])
                                ? 'menu-open'
                                : '' }}">
                            <a href="#" class="nav-link"> <i class="nav-icon fa fa-database"></i>
                                <p>
                                    Reference
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"> <a href="{{ route('poz::master.brand.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::master.brand.index', 'poz::master.brand.create', 'poz::master.brand.edit']) ? 'active' : '' }}">
                                        <p>Brand</p>
                                    </a> </li>
                                <li class="nav-item"> <a href="{{ route('poz::master.category.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::master.category.index', 'poz::master.category.create', 'poz::master.category.edit']) ? 'active' : '' }}">
                                        <p>Category</p>
                                    </a> </li>
                                <li class="nav-item"> <a href="{{ route('poz::master.unit.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::master.unit.index', 'poz::master.unit.create', 'poz::master.unit.edit']) ? 'active' : '' }}">
                                        <p>Unit</p>
                                    </a> </li>
                                <li class="nav-item"> <a href="{{ route('poz::master.tax.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::master.tax.index', 'poz::master.tax.create', 'poz::master.tax.edit']) ? 'active' : '' }}" class="nav-link">
                                        <p>Tax Rate</p>
                                    </a> </li>
                                <li class="nav-item"> <a href="{{ route('poz::master.warehouse.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::master.warehouse.index', 'poz::master.warehouse.create', 'poz::master.warehouse.edit']) ? 'active' : '' }}">
                                        <p>Warehouse</p>
                                    </a> </li>
                                <li class="nav-item"> <a href="{{ route('poz::master.outlet.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::master.outlet.index', 'poz::master.outlet.create', 'poz::master.outlet.edit']) ? 'active' : '' }}">
                                        <p>Outlet</p>
                                    </a> </li>
                                <li class="nav-item"> <a href="{{ route('poz::master.casier.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['poz::master.casier.index', 'poz::master.casier.create', 'poz::master.casier.edit']) ? 'active' : '' }}">
                                        <p>Casier</p>
                                    </a> </li>
                            </ul>
                        </li>
                    </ul> <!--end::Sidebar Menu-->
                </nav>
            </div> <!--end::Sidebar Wrapper-->
        </aside> <!--end::Sidebar--> <!--begin::App Main-->
        <main class="app-main"> <!--begin::App Content Header-->
            @yield('header')


            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    @yield('content')
                </div> <!--end::Container-->
            </div> <!--end::App Content-->
        </main> <!--end::App Main--> <!--begin::Footer-->
        <footer class="app-footer"> <!--begin::To the end-->
            <div class="d-none d-sm-inline float-end">Anything you want</div> <!--end::To the end--> <!--begin::Copyright--> <strong>
                Copyright &copy; @php echo date('Y')@endphp &nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">DigiPemad POS</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer> <!--end::Footer-->
    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('adminlte/js/adminlte.js') }}"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script> <!--end::OverlayScrollbars Configure--> <!-- OPTIONAL SCRIPTS --> <!-- sortablejs -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script> <!-- sortablejs -->
    <script>
        const connectedSortables =
            document.querySelectorAll(".connectedSortable");
        connectedSortables.forEach((connectedSortable) => {
            let sortable = new Sortable(connectedSortable, {
                group: "shared",
                handle: ".card-header",
            });
        });

        const cardHeaders = document.querySelectorAll(
            ".connectedSortable .card-header",
        );
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = "move";
        });
    </script> <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script> <!-- ChartJS -->
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const sales_chart_options = {
            series: [{
                    name: "Digital Goods",
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: "Electronics",
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 300,
                type: "area",
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ["#0d6efd", "#20c997"],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
            },
            xaxis: {
                type: "datetime",
                categories: [
                    "2023-01-01",
                    "2023-02-01",
                    "2023-03-01",
                    "2023-04-01",
                    "2023-05-01",
                    "2023-06-01",
                    "2023-07-01",
                ],
            },
            tooltip: {
                x: {
                    format: "MMMM yyyy",
                },
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector("#revenue-chart"),
            sales_chart_options,
        );
        sales_chart.render();
    </script> <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> <!-- jsvectormap -->
    @livewireScripts

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="{{ asset('timepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.9/dayjs.min.js"></script>
    <script type="text/javascript" src="{{ asset('timepicker/timepickers.js') }}"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

    <!--end::Scrolltop-->
    <!--end::Main-->
    <x-livewire-alert::scripts />

    @auth
        @if (Route::has(config('modules.auth.signout.route')))
            <script>
                const signout = (e) => {
                    if (confirm('Apakah Anda yakin?')) {
                        document.getElementById('signout-form').submit();
                    }
                }
            </script>
            <form class="form-block form-confirm" id="signout-form" action="{{ route(config('modules.auth.signout.route')) }}" method="POST" style="display: none;"> @csrf
            </form>
        @endif
    @endauth

    <script>
        const visitorsData = {
            US: 398, // USA
            SA: 400, // Saudi Arabia
            CA: 1000, // Canada
            DE: 500, // Germany
            FR: 760, // France
            CN: 300, // China
            AU: 700, // Australia
            BR: 600, // Brazil
            IN: 800, // India
            GB: 320, // Great Britain
            RU: 3000, // Russia
        };

        // World map by jsVectorMap
        const map = new jsVectorMap({
            selector: "#world-map",
            map: "world",
        });

        // Sparkline charts
        const option_sparkline1 = {
            series: [{
                data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
            }, ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline1 = new ApexCharts(
            document.querySelector("#sparkline-1"),
            option_sparkline1,
        );
        sparkline1.render();

        const option_sparkline2 = {
            series: [{
                data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
            }, ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline2 = new ApexCharts(
            document.querySelector("#sparkline-2"),
            option_sparkline2,
        );
        sparkline2.render();

        const option_sparkline3 = {
            series: [{
                data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
            }, ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline3 = new ApexCharts(
            document.querySelector("#sparkline-3"),
            option_sparkline3,
        );
        sparkline3.render();
    </script> <!--end::Script-->
</body><!--end::Body-->

</html>
