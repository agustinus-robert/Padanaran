@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Posting Image')

@section('navtitle', 'Posting Image')

@section('content')

    <!--begin::Row-->
    <div class="row g-5 g-lg-10">
        <!--end::Col-->
        <div class="col-xl-4">
            <!--begin::Row-->
            <div class="row g-5 g-lg-10">
                <!--begin::Col-->
                <div class="col-lg-12 mb-lg-10 mb-5">
                    <!--begin::Mixed Widget 3-->
                    <div class="h-100 bgi-no-repeat bgi-size-cover h-lg-100 card">
                        <!--begin::Body-->
                        <div class="d-flex flex-column justify-content-between card-body">
                            <i class="ki-duotone ki-element-11 fs-2hx ms-n1 flex-grow-1 text-gray-900">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <select class="form-select mb-5" id="yearSelect">
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>

                                <select class="form-select mb-5" id="monthSelect">
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($months as $month)
                                        <option value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </select>

                                <canvas id="visitorChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Mixed Widget 3-->
                </div>

                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--begin::Col-->
        <div class="col-xl-4">
            <!--begin::Row-->
            <div class="row g-5 g-lg-10">
                <!--begin::Col-->
                <div class="col-lg-12 mb-lg-10 mb-5">
                    <!--begin::Mixed Widget 3-->
                    <div class="h-100 bgi-no-repeat bgi-size-cover h-lg-100 card">
                        <!--begin::Body-->
                        <div class="d-flex flex-column justify-content-between card-body">
                            <!--begin::Title-->
                            <div id="weatherapi-weather-widget-2"></div>
                            <script type='text/javascript' src='https://www.weatherapi.com/weather/widget.ashx?loc=3026315&wid=2&tu=2&div=weatherapi-weather-widget-2' async></script><noscript><a href="https://www.weatherapi.com/weather/q/jakarta-3026315" alt="Hour by hour Jakarta weather">10 day hour by hour Jakarta weather</a></noscript>
                            <!--end::Link-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Mixed Widget 3-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row g-5 g-lg-10">
        <!--begin::Col-->
        <div class="col-xl-4 mb-xl-10">
            <!--begin::List Widget 3-->
            <div class="h-xl-100 card">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <h3 class="align-items-start flex-column card-title">
                        <span class="fw-bold mb-2 text-gray-900">Promo Terbaru</span>
                        <span class="text-muted fw-semibold fs-7">3 Promo terbaru yang telah terdaftar</span>
                    </h3>

                    <div class="card-toolbar">
                        <!--begin::Menu-->
                        <button type="button" class="btn-icon btn-color-primary btn-active-light-primary btn btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-category fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </button>
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
            </div>
            <!--end:List Widget 3-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-4 mb-xl-10">
            <!--begin::List Widget 5-->
            <div class="h-xl-100 card">
                <!--begin::Header-->
                <div class="card-header align-items-center mt-4 border-0">
                    <h3 class="align-items-start flex-column card-title">
                        <span class="fw-bold mb-2 text-gray-900">Blog Terbaru</span>
                        <span class="text-muted fw-semibold fs-7">3 Blog terbaru yang telah terdaftar</span>
                    </h3>
                    <div class="card-toolbar">
                        <!--begin::Menu-->
                        <button type="button" class="btn-icon btn-color-primary btn-active-light-primary btn btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-category fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </button>
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <div class="card-body pt-0">
                    <!--begin::Item-->
                    @php
                        $numCareer = 0;
                    @endphp

                    @foreach ($karir as $key => $value)
                        @if (isset(get_content_json($val)['id']['post1']) && strtotime(date('Y-m-d')) < strtotime(get_content_json($val)['id']['post1']))
                            @php
                                $numCareer++;
                            @endphp
                            <div class="d-flex align-items-center bg-light-warning mb-7 rounded p-5">
                                <i class="ki-duotone ki-abstract-26 fs-1 me-5 text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <!--begin::Title-->
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="fw-bold text-hover-primary fs-6 text-gray-800">{{ get_content_json($val)['id']['title'] }}</a>
                                    <span class="text-muted fw-semibold d-block">@php
                                        $date1 = new DateTime('now');
                                        $date2 = new DateTime(get_content_json($val)['id']['post0']);
                                        $interval = $date1->diff($date2);
                                        echo $interval->days;
                                    @endphp</span>
                                </div>
                                <!--end::Title-->
                                <!--begin::Lable-->
                                <span class="fw-bold py-1 text-warning">New</span>
                                <!--end::Lable-->
                            </div>
                        @endif
                    @endforeach

                    @if ($numCareer == 0)
                        <div class="d-flex align-items-center bg-light-primary mb-7 rounded p-5">
                            <i class="ki-duotone ki-abstract-26 fs-1 me-5 text-warning">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <!--begin::Title-->
                            <div class="flex-grow-1 me-2">
                                <a href="#" class="fw-bold text-hover-primary fs-6 text-gray-800">Belum ada Karir dibuka</a>
                                <span class="text-muted fw-semibold d-block">YSBY Career</span>
                            </div>
                            <!--end::Title-->
                            <!--begin::Lable-->
                            <!--end::Lable-->
                        </div>
                    @endif
                    <!--end::Item-->
                </div>
            </div>
            <!--end: List Widget 5-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-4 mb-xl-10">
            <!--begin::List Widget 6-->
            <div class="h-xl-100 card">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <h3 class="align-items-start flex-column card-title">
                        <span class="fw-bold mb-2 text-gray-900">Kontak Terbaru</span>
                        <span class="text-muted fw-semibold fs-7">3 Kontak terbaru yang telah terdaftar</span>
                    </h3>

                    <div class="card-toolbar">
                        <!--begin::Menu-->
                        <button type="button" class="btn-icon btn-color-primary btn-active-light-primary btn btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-category fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </button>
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-0">
                    <!--begin::Item-->
                    @if (count($contact) > 0)
                        @foreach ($contact as $key => $val)
                            <div class="d-flex align-items-center bg-light-info mb-7 rounded p-5">
                                <i class="ki-duotone ki-abstract-26 fs-1 me-5 text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <!--begin::Title-->
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="fw-bold text-hover-primary fs-6 text-gray-800">{{ $val->first_name . ' ' . $val->last_name }}</a>
                                    <span class="text-muted fw-semibold d-block">{{ $val->email }}</span>
                                </div>
                                <!--end::Title-->
                                <!--begin::Lable-->
                                <span class="fw-bold py-1 text-info">{!! tgl_indo(date('Y-m-d', strtotime($val->created_at))) . ' <br /> Jam: ' . date('H:i:s', strtotime($val->created_at)) !!}</span>
                                <!--end::Lable-->
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex align-items-center bg-primary-info mb-7 rounded p-5">
                            <i class="ki-duotone ki-abstract-26 fs-1 me-5 text-warning">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <!--begin::Title-->
                            <div class="flex-grow-1 me-2">
                                <a href="#" class="fw-bold text-hover-primary fs-6 text-gray-800">Belum ada Kontak Masuk</a>
                                <span class="text-muted fw-semibold d-block">Guest YSBY Contact</span>
                            </div>
                            <!--end::Title-->
                            <!--begin::Lable-->
                            <!--end::Lable-->
                        </div>

                    @endif
                    <!--end::Item-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::List Widget 6-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--end::Row-->
    @push('scripts')
        <script>
            // Data dari PHP
            var months = @json($months); // Array nama bulan dan tahun dari PHP
            var totals = @json($totals); // Array total pengunjung dari PHP

            var ctx = document.getElementById('visitorChart').getContext('2d');
            var visitorChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months, // Langsung menggunakan nama bulan dan tahun
                    datasets: [{
                        label: 'Jumlah Pengunjung',
                        data: totals,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        fill: true,
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Jumlah Pengunjung'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // Fungsi untuk memfilter data berdasarkan tahun dan bulan
            function filterData() {
                var selectedYear = document.getElementById('yearSelect').value;
                var selectedMonth = document.getElementById('monthSelect').value;

                // Filter data berdasarkan tahun dan bulan
                var filteredMonths = [];
                var filteredTotals = [];

                for (var i = 0; i < months.length; i++) {
                    if ((selectedYear === "" || months[i].includes(selectedYear)) &&
                        (selectedMonth === "" || months[i].startsWith(selectedMonth.split(' ')[0]))) {
                        filteredMonths.push(months[i]);
                        filteredTotals.push(totals[i]);
                    }
                }

                // Update chart dengan data yang difilter
                visitorChart.data.labels = filteredMonths;
                visitorChart.data.datasets[0].data = filteredTotals;
                visitorChart.update();
            }

            // Event listener untuk dropdown
            document.getElementById('yearSelect').addEventListener('change', filterData);
            document.getElementById('monthSelect').addEventListener('change', filterData);
        </script>
    @endpush

@endsection
