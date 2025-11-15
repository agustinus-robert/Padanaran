@extends('portal::layouts.index')

@section('contents')
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('skote/images/logo.svg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('skote/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('skote/images/logo-light.svg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('skote/images/logo-light.png') }}" alt="" height="39">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm font-size-16 d-lg-none header-item waves-effect waves-light px-3" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <i class="fa fa-fw fa-bars"></i>
                </button>

            </div>

            <div class="d-flex">

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-customize"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <div class="px-lg-2">

                            <div class="row g-0">
                                <div class="row no-gutters">
                                    @if(count(outletList(Auth::user()->id)) > 0)
                                        @foreach (outletList(Auth::user()->id) as $key => $value)
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="{{ route('poz::dashboard') }}?outlet={{ $value->id }}">
                                                    <i class="bx bx-building-house" style="font-size:24px;"></i>
                                                    <span>{{ $value->name }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    @else
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="javascript:void(0)">
                                                    Tidak ada Outlet
                                                </a>
                                            </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('layouts.nav_name')
            </div>
    </header>

    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                <div class="navbar-collapse collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('portal::dashboard.index') }}" id="topnav-dashboard" role="button">
                                <i class="bx bx-home-circle me-2"></i><span key="t-dashboards">Dashboards</span>
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('account::user.profile') }}" id="topnav-uielement" role="button">
                                <i class="bx bx-user-pin me-2"></i>
                                <span key="t-ui-elements"> Profil</span>
                            </a>
                        </li> --}}

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('portal::outlet.manage-outlet.index') }}" id="topnav-uielement" role="button">
                                <i class="bx bx-building-house me-2"></i>
                                <span key="t-ui-elements"> Kelola Outlet</span>
                            </a>
                        </li>

                        {{-- <li class="nav-item text-right">
                            <a class="nav-link arrow-none" href="{{ route('portal::dashboard-msdm.index') }}" id="topnav-uielement" role="button">
                                <i class="bx bxs-user-voice me-2"></i>
                                <span key="t-ui-elements"> Kelola MSDM</span>
                            </a>
                        </li> --}}
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                @if (Session::has('msg-sukses'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-success">
                            {{ Session::get('msg-sukses') }}
                        </div>
                    </div>
                @endif

                @if (Session::has('msg-gagal'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert-danger alert">
                            {{ Session::get('msg-gagal') }}
                        </div>
                    </div>
                @endif
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Portal</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card overflow-hidden">
                            <div class="bg-primary-subtle">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-3">
                                            <h5 class="text-primary">Selamat Datang!</h5>
                                            <p>Point Of Sales</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ asset('skote/images/profile-img.png') }}" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <img src="{{ asset('skote/images/users/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                                        </div>
                                        <h5 class="font-size-15 text-truncate">{{ $user->name }}</h5>
                                        <p class="text-muted text-truncate mb-0">{{ $user->email }}</p>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="pt-4">

                                            <div class="row">
                                                <div class="col-6">
                                                    <h5 class="font-size-15">{{ $summarySellMonth['qty'] }}</h5>
                                                    <p class="text-muted mb-0">Penjualan</p>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="font-size-15">
                                                        {{ $summaryBuyMonth['qty'] }}
                                                    </h5>
                                                    <p class="text-muted mb-0">Pembelian</p>
                                                </div>
                                            </div>
                                            {{-- <div class="mt-4">
                                                <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">Kelola Profil <i class="mdi mdi-arrow-right ms-1"></i></a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Pendapatan Bulanan</h4>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="text-muted">Pendapatan Bulan ini</p>
                                        <h3>{{ number_format($summarySellMonth['total'] - $summaryBuyMonth['total'], 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Pembelian</p>
                                                <h4 class="mb-0">{{ number_format($summaryBuy['total'], 0, ',', '.') }}</h4>
                                            </div>

                                            <div class="align-self-center flex-shrink-0">
                                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                    <span class="avatar-title">
                                                        <i class="bx bx-copy-alt font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Penjualan</p>
                                                <h4 class="mb-0">{{ number_format($summarySell['total'], 0, ',', '.') }}</h4>
                                            </div>

                                            <div class="align-self-center flex-shrink-0">
                                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="bx bx-archive-in font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Keuntungan</p>
                                                <h4 class="mb-0">{{ number_format($summarySell['total'] - $summaryBuy['total'], 0, ',', '.') }}</h4>
                                            </div>

                                            <div class="align-self-center flex-shrink-0">
                                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="card">
                            <div class="card-body">
                                {{-- <div class="d-sm-flex flex-wrap">
                                    <h4 class="card-title mb-4">Email Sent</h4>
                                    <div class="ms-auto">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Week</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Month</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#">Year</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div> --}}


                                <canvas id="pie" data-colors='["--bs-success", "--bs-danger"]' class="chartjs-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Barang paling banyak terjual</h4>
                                <div class="table-responsive">
                                    <table class="table-nowrap mb-0 table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="align-middle">No</th>
                                                <th class="align-middle">Nama</th>
                                                <th class="align-middle">Kuantitas terjual</th>
                                                <th class="align-middle">Total Penjualan</th>
                                                <th class="align-middle">Total Pembelian</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topProducts as $value)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $value['product']->name ?? '-' }}</td>
                                                    <td>{{ $value['qty_sold'] }}</td>
                                                    <td>{{ number_format($value['total_sales'], 0, ',', '.') }}</td>
                                                    <td>{{ number_format($value['total_wholesale'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <!-- Modal -->
        <div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" aria-labelledby="transaction-detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transaction-detailModalLabel">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">Product id: <span class="text-primary">#SK2540</span></p>
                        <p class="mb-4">Billing Name: <span class="text-primary">Neal Matthews</span></p>

                        <div class="table-responsive">
                            <table class="table-nowrap table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <div>
                                                <img src="{{ asset('skote/images/product/img-7.png') }}" alt="" class="avatar-sm">
                                            </div>
                                        </th>
                                        <td>
                                            <div>
                                                <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                                                <p class="text-muted mb-0">$ 225 x 1</p>
                                            </div>
                                        </td>
                                        <td>$ 255</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <div>
                                                <img src="{{ asset('skote/images/product/img-4.png') }}" alt="" class="avatar-sm">
                                            </div>
                                        </th>
                                        <td>
                                            <div>
                                                <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                                                <p class="text-muted mb-0">$ 145 x 1</p>
                                            </div>
                                        </td>
                                        <td>$ 145</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <h6 class="m-0 text-right">Sub Total:</h6>
                                        </td>
                                        <td>
                                            $ 400
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <h6 class="m-0 text-right">Shipping:</h6>
                                        </td>
                                        <td>
                                            Free
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <h6 class="m-0 text-right">Total:</h6>
                                        </td>
                                        <td>
                                            $ 400
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal -->

        <!-- subscribeModal -->

        <!-- end modal -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Skote.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Design & Develop by Themesbrand
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        function getChartColorsArray(r) {
            if (null !== document.getElementById(r)) {
                var o = document.getElementById(r).getAttribute("data-colors");
                if (o) return (o = JSON.parse(o)).map(function(r) {
                    var o = r.replace(" ", "");
                    if (-1 === o.indexOf(",")) {
                        var e = getComputedStyle(document.documentElement).getPropertyValue(o);
                        return e || o
                    }
                    var t = r.split(",");
                    return 2 != t.length ? o : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(t[0]) + "," + t[1] + ")"
                })
            }
        }
        Chart.defaults.borderColor = "rgba(133, 141, 152, 0.1)", Chart.defaults.color = "#858d98",
            function(p) {
                "use strict";

                function r() {}
                r.prototype.respChart = function(r, o, e, t) {
                    var a = r.get(0).getContext("2d"),
                        n = p(r).parent();

                    function l() {
                        r.attr("width", p(n).width());
                        switch (o) {
                            case "Line":
                                new Chart(a, {
                                    type: "line",
                                    data: e,
                                    options: t
                                });
                                break;
                            case "Doughnut":
                                new Chart(a, {
                                    type: "doughnut",
                                    data: e,
                                    options: t
                                });
                                break;
                            case "Pie":
                                new Chart(a, {
                                    type: "pie",
                                    data: e,
                                    options: t
                                });
                                break;
                            case "Bar":
                                new Chart(a, {
                                    type: "bar",
                                    data: e,
                                    options: t
                                });
                                break;
                            case "Radar":
                                new Chart(a, {
                                    type: "radar",
                                    data: e,
                                    options: t
                                });
                                break;
                            case "PolarArea":
                                new Chart(a, {
                                    data: e,
                                    type: "polarArea",
                                    options: t
                                })
                        }
                    }
                    p(window).resize(l), l()
                }, r.prototype.init = function() {
                    var r, o = getChartColorsArray("lineChart");
                    o && (r = {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
                        datasets: [{
                            label: "Sales Analytics",
                            fill: !0,
                            lineTension: .5,
                            backgroundColor: o[0],
                            borderColor: o[1],
                            borderCapStyle: "butt",
                            borderDash: [],
                            borderDashOffset: 0,
                            borderJoinStyle: "miter",
                            pointBorderColor: o[1],
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: o[1],
                            pointHoverBorderColor: "#fff",
                            pointHoverBorderWidth: 2,
                            pointRadius: 1,
                            pointHitRadius: 10,
                            data: [65, 59, 80, 81, 56, 55, 40, 55, 30, 80]
                        }, {
                            label: "Monthly Earnings",
                            fill: !0,
                            lineTension: .5,
                            backgroundColor: o[2],
                            borderColor: o[3],
                            borderCapStyle: "butt",
                            borderDash: [],
                            borderDashOffset: 0,
                            borderJoinStyle: "miter",
                            pointBorderColor: o[3],
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: o[3],
                            pointHoverBorderColor: "#eef0f2",
                            pointHoverBorderWidth: 2,
                            pointRadius: 1,
                            pointHitRadius: 10,
                            data: [80, 23, 56, 65, 23, 35, 85, 25, 92, 36]
                        }]
                    }, this.respChart(p("#lineChart"), "Line", r));
                    var e, t = getChartColorsArray("doughnut");
                    t && (e = {
                        labels: ["Desktops", "Tablets"],
                        datasets: [{
                            data: [300, 210],
                            backgroundColor: t,
                            hoverBackgroundColor: t,
                            hoverBorderColor: "#fff"
                        }]
                    }, this.respChart(p("#doughnut"), "Doughnut", e));
                    var a, n = getChartColorsArray("pie");
                    n && (a = {
                        labels: ["Pembelian", "Penjualan"],
                        datasets: [{
                            data: [{{ $summaryBuy['total'] }}, {{ $summarySell['total'] }}],
                            backgroundColor: n,
                            hoverBackgroundColor: n,
                            hoverBorderColor: "#fff"
                        }]
                    }, this.respChart(p("#pie"), "Pie", a));
                    var l, i = getChartColorsArray("bar");
                    i && (l = {
                        labels: ["January", "February", "March", "April", "May", "June", "July"],
                        datasets: [{
                            label: "Sales Analytics",
                            backgroundColor: i[0],
                            borderColor: i[0],
                            borderWidth: 1,
                            hoverBackgroundColor: i[1],
                            hoverBorderColor: i[1],
                            data: [65, 59, 81, 45, 56, 80, 50, 20]
                        }]
                    }, this.respChart(p("#bar"), "Bar", l));
                    var d, s = getChartColorsArray("radar");
                    s && (d = {
                        labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
                        datasets: [{
                            label: "Desktops",
                            backgroundColor: s[0],
                            borderColor: s[1],
                            pointBackgroundColor: s[1],
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: s[1],
                            data: [65, 59, 90, 81, 56, 55, 40]
                        }, {
                            label: "Tablets",
                            backgroundColor: s[2],
                            borderColor: s[3],
                            pointBackgroundColor: s[3],
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: s[3],
                            data: [28, 48, 40, 19, 96, 27, 100]
                        }]
                    }, this.respChart(p("#radar"), "Radar", d));
                    var C, u = getChartColorsArray("polarArea");
                    u && (C = {
                        datasets: [{
                            data: [11, 16, 7, 18],
                            backgroundColor: u,
                            label: "My dataset",
                            hoverBorderColor: "#fff"
                        }],
                        labels: ["Series 1", "Series 2", "Series 3", "Series 4"]
                    }, this.respChart(p("#polarArea"), "PolarArea", C))
                }, p.ChartJs = new r, p.ChartJs.Constructor = r
            }(window.jQuery),
            function() {
                "use strict";
                window.jQuery.ChartJs.init()
            }();
    </script>
@endpush
