@extends('poz::layout.index')

@section('title', 'Dashboard POS')

@section('navtitle', 'Dashboard POS')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-9 col-sm-8">
            <div class="p-4">
                <h5 class="text-primary">Selamat Datang !</h5>
                <p>{{ Auth::user()->name }}</p>

                <div class="text-muted">
                    <p class="mb-1"><i class="mdi mdi-circle-medium text-primary me-1 align-middle"></i> Silahkan kelola produk anda</p>
                    <p class="mb-1"><i class="mdi mdi-circle-medium text-primary me-1 align-middle"></i> Dapatkan keuntungan maksimal</p>
                    <p class="mb-0"><i class="mdi mdi-circle-medium text-primary me-1 align-middle"></i> Point Of Sale</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-4 align-self-center">
            <div>
                <img src="{{ asset('skote/images/crypto/features-img/img-1.png') }}" alt="" class="img-fluid d-block">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Perbandingan antara penjualan dan pembelian</h4>
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($summaryBuyMonth['total'], 0, ',', '.') }}</h5>
                            <p class="text-muted text-truncate">Pembelian</p>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($summarySellMonth['total'], 0, ',', '.') }}</h5>
                            <p class="text-muted text-truncate">Penjualan</p>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($summarySellMonth['total'] - $summaryBuyMonth['total'], 0, ',', '.') }}</h5>
                            <p class="text-muted text-truncate">Pendapatan</p>
                        </div>
                    </div>
                    <canvas id="pie" data-colors='["--bs-primary", "--bs-warning"]' class="chartjs-chart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Barang paling banyak terjual</h4>
                    {{-- <div class="row text-center">
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($summaryBuyMonth['total'], 0, ',', '.') }}</h5>
                            <p class="text-muted text-truncate">Pembelian</p>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($summarySellMonth['total'], 0, ',', '.') }}</h5>
                            <p class="text-muted text-truncate">Penjualan</p>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ number_format($summarySellMonth['total'] - $summaryBuyMonth['total'], 0, ',', '.') }}</h5>
                            <p class="text-muted text-truncate">Pendapatan</p>
                        </div>
                    </div> --}}
                    <canvas id="lineChart" data-colors='["--bs-primary-rgb, 0.2", "--bs-primary", "--bs-light-rgb, 0.2", "--bs-light"]' height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const productLabels = {!! json_encode($labels) !!};
        const productDataSales = {!! json_encode($dataSales) !!};

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
                        labels: productLabels,
                        datasets: [{
                            label: "Grafik Stock Produk",
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
                            data: productDataSales
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
                            data: [{{ $summaryBuyMonth['total'] }}, {{ $summarySellMonth['total'] }}],
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
