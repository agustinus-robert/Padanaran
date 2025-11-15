@extends('portal::layouts.default')

@section('title', 'Presensi | ')

@include('components.tourguide', [
    'steps' => array_filter([
        [
            'selector' => '.tg-steps-presence-button',
            'title' => 'Scan presensi',
            'content' => 'Silakan tekan tombol scan untuk melakukan presensi masuk dan pulang baik WFO maupun WFA.',
        ],
        [
            'selector' => '.tg-steps-presence-history',
            'title' => 'Lihat riwayat scan',
            'content' => 'Kalau mau lihat riwayat presensi pada bulan-bulan sebelum nya, pilih bulan lalu tekan tombol tampilkan.',
        ],
        [
            'selector' => '.tg-steps-presence-calendar',
            'title' => 'Kalender presensi',
            'content' => 'Menampilkan kalender presensi beserta scanlognya di bulan berjalan atau sesuai dengan bulan yang di pilih.',
        ],
        $location->value == Modules\Core\Enums\WorkLocationEnum::WFA->value
            ? [
                'selector' => '.tg-steps-presence-move',
                'title' => 'Pindah lokasi',
                'content' => 'Tekan aja tombol ini kalau mau pindah lokasi kerja dari WFA ke WFO, aktivitasmu terekam kok.',
            ]
            : [],
    ]),
])

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::dashboard-msdm.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Presensi</h2>
            <div class="text-muted">Yuk! Lakukan check-in dan check-out untuk hindari keterlambatan, usahakan selalu tepat waktu ya!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0">
                @if (request('month', date('Y-m')) == date('Y-m'))
                    <button id="request-access" class="btn btn-soft-info rounded-0 rounded-top card-body border-0 py-2" onclick="getLocation()">
                        <i class="mdi mdi-map-marker-outline"></i> Minta akses lokasi perangkat
                    </button>
                @endif
                <div class="card-body py-md-5 py-4 text-center">
                    <p class="text-muted mb-0">{{ strftime('%A, %d %B %Y') }}</p>
                    <div id="time" class="display-4">{{ date('H:i:s') }}</div>
                    <div class="tg-steps-presence-button my-4">

                        {{-- Modified --}}
                        {{-- @if ($current_schedule && !$vacations->contains(date('Y-m-d'))) --}}
                        @if ($current_schedule && !$vacations->pluck('d')->contains(date('Y-m-d')))
                            @if (request('month', date('Y-m')) == date('Y-m'))
                                <form class="form-block" action="{{ route('portal::attendance.presence.index') }}" method="post"> @csrf
                                    <input class="d-block form-scan form-control mx-auto mb-2 text-center" type="{{ in_array(auth()->user()->employee->id, [27, 46, 53]) ? 'text' : 'hidden' }}" name="latlong" value="">
                                    <input type="hidden" name="location" value="{{ $location->value }}">
                                    @if (in_array(auth()->user()->employee->id, [46, 53]))
                                        <label class="bg-danger d-block mb-4 text-white">
                                            <input type="checkbox" id="fake-location" onclick="jancuk(this)"> Centang untuk set lokasi dari kantor
                                        </label>
                                        <script>
                                            const saved_latlong = document.querySelector('[name="latlong"]').value;

                                            function _generateRandom(min = -100, max = 100) {
                                                let difference = max - min;
                                                let rand = Math.random();
                                                rand = Math.floor(rand * difference);
                                                rand = rand + min;
                                                return (rand / 1e7);
                                            }
                                            const jancuk = (el) => {
                                                navigator.geolocation.getCurrentPosition((p) => {
                                                    let pemad_lat = -7.7180276;
                                                    let pemad_long = 110.4045849;
                                                    document.querySelector('[name="latlong"]').value = el.checked ? `[${(pemad_lat + _generateRandom()).toFixed(7)},${(pemad_long + _generateRandom()).toFixed(7)}]` : `[${p.coords.latitude},${p.coords.longitude}]`;
                                                })
                                            }
                                        </script>
                                    @endif
                                    <button class="btn btn-soft-secondary disabled rounded-circle form-scan mx-auto mb-4" style="width: 100px; height: 100px;" type="submit" name="submit"><i class="mdi mdi-fingerprint mdi-48px"></i></button>
                                    <div id="geolocation-notice" class="text-danger text-center">Biar bisa presensi, <br> kamu wajib ngaktifin lokasi browser kamu!</div>
                                </form>
                            @else
                                <button type="button" class="btn btn-soft-secondary disabled rounded-circle text-uppercase mx-auto mb-4" style="width: 100px; height: 100px;"><i class="mdi mdi-fingerprint mdi-48px"></i></button>
                            @endif
                        @else
                            @if ($isTeacher == true)

                                <form class="form-block" action="{{ route('portal::attendance.presence.index') }}" method="post"> @csrf
                                    <input class="d-block form-scan form-control mx-auto mb-2 text-center" type="{{ in_array(auth()->user()->employee->id, [27, 46, 53]) ? 'text' : 'hidden' }}" name="latlong" value="">
                                    <input type="hidden" name="location" value="{{ $location->value }}">
                                    @if (in_array(auth()->user()->employee->id, [46, 53]))
                                        <label class="bg-danger d-block mb-4 text-white">
                                            <input type="checkbox" id="fake-location" onclick="jancuk(this)"> Centang untuk set lokasi dari kantor
                                        </label>
                                        <script>
                                            const saved_latlong = document.querySelector('[name="latlong"]').value;

                                            function _generateRandom(min = -100, max = 100) {
                                                let difference = max - min;
                                                let rand = Math.random();
                                                rand = Math.floor(rand * difference);
                                                rand = rand + min;
                                                return (rand / 1e7);
                                            }
                                            const jancuk = (el) => {
                                                navigator.geolocation.getCurrentPosition((p) => {
                                                    let pemad_lat = -7.7180276;
                                                    let pemad_long = 110.4045849;
                                                    document.querySelector('[name="latlong"]').value = el.checked ? `[${(pemad_lat + _generateRandom()).toFixed(7)},${(pemad_long + _generateRandom()).toFixed(7)}]` : `[${p.coords.latitude},${p.coords.longitude}]`;
                                                })
                                            }
                                        </script>
                                    @endif
                                    <button class="btn btn-soft-secondary disabled rounded-circle form-scan mx-auto mb-4" style="width: 100px; height: 100px;" type="submit" name="submit"><i class="mdi mdi-fingerprint mdi-48px"></i></button>
                                    <div id="geolocation-notice" class="text-danger text-center">Biar bisa presensi, <br> kamu wajib ngaktifin lokasi browser kamu!</div>
                                </form>
                            @else
                                <button type="button" class="btn btn-soft-secondary disabled rounded-circle text-uppercase mx-auto mb-4" style="width: 100px; height: 100px;"><i class="mdi mdi-fingerprint mdi-48px"></i></button>

                                {{-- modified --}}
                                {{-- @if ($vacations->contains(date('Y-m-d'))) --}}
                                @if ($vacations->pluck('d')->contains(date('Y-m-d')))
                                    <div class="text-danger text-center">Maaf nggak bisa presensi, hari ini kamu cuti kan?</div>
                                @else
                                    <div class="text-danger text-center">Jadwal kamu bulan {{ strftime('%B %Y') }} belum ditetapkan.</div>
                                @endif
                            @endif
                        @endif
                    </div>
                    <h4 class="mb-1">Presensi Kehadiran</h4>
                    <p class="text-muted mb-0">Silakan tekan tombol di atas untuk presensi</p>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between py-2">
                    <div><i class="mdi mdi-calendar-multiselect"></i> Riwayat scan presensi </div>
                    <form class="tg-steps-presence-history" action="{{ route('portal::attendance.presence.index') }}" method="GET">
                        <div class="input-group input-group-sm">
                            <input type="hidden" name="type" value="{{ $location->name }}">
                            <input class="form-control" type="month" name="month" value="{{ $month->format('Y-m') }}">
                            <button class="btn btn-dark"><i class="mdi mdi-eye-outline"></i> <span class="d-none d-sm-inline">Tampilkan</span></button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive tg-steps-presence-calendar">
                    @php($daynames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'])
                    <table class="table-bordered calendar mb-0 table">
                        <thead>
                            <tr>
                                @foreach ($daynames as $dayname)
                                    <th class="text-center">{{ $dayname }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php($_month = $month->copy()->startOfMonth())
                            @php($day = 1)
                            @php($totalWeekOfMonth = $_month->dayOfWeek >= 5 && $_month->daysInMonth >= 30 ? 6 : ($_month->dayOfWeek == 0 && $_month->daysInMonth <= 28 ? 4 : 5))
                            @for ($week = 1; $week <= $totalWeekOfMonth; $week++)
                                <tr>
                                    @foreach ($daynames as $dayindex => $dayname)
                                        @php($_date = date('Y-m-d', mktime(0, 0, 0, $_month->month, $day, $_month->year)))
                                        <td class="{{ $_date == date('Y-m-d') ? 'bg-soft-secondary' : '' }}" style="height: 100px; min-height: 100px; min-width: 120px;">
                                            @if ((($week == 1 && $dayindex >= $_month->dayOfWeek) || $week > 1) && ($week != $totalWeekOfMonth || $day <= $_month->daysInMonth))
                                                <div class="position-relative h-100 float-start">
                                                    <div class="small position-absolute {{ $dayindex == 0 || isset($moments[$_date]) ? 'text-danger' : 'text-muted' }}" style="opacity: .8;">{{ $day }}</div>
                                                    @isset($moments[$_date])
                                                        <div class="small position-absolute text-danger bottom-0" data-bs-toggle="tooltip" title="{{ $moments[$_date]->pluck('name')->join(',', ' dan ') }}" style="opacity: .8;"><i class="mdi mdi-information-outline"></i></div>
                                                    @endisset
                                                </div>

                                                {{-- Modified --}}
                                                {{-- @if ($vacations->contains($_date)) --}}
                                                @if ($vacations->pluck('d')->contains($_date))
                                                    @php($fr = $vacations->filter(fn($i) => $i['d'] == $_date)->pluck('f'))
                                                    @php($cl = $fr[0] != null ? '--bs-warning' : '--bs-danger')
                                                    <div class="position-relative float-end">
                                                        <div class="position-absolute text-center text-white" style="transform: rotate(45deg); top: -3px; right: -23px; border-bottom: 18px solid var({{ $cl }}); font-size: .75rem; border-left: 18px solid transparent; border-right: 18px solid transparent; height: 0;">
                                                            {{ $fr[0] != null ? 'Frln' : 'Cuti' }}
                                                        </div>
                                                    </div>
                                                @endif
                                                @foreach ($scanlogs[$_date] ?? [] as $scan)
                                                    <div class="position-relative text-center">
                                                        <small class="d-block {{ $scan->location == Modules\Core\Enums\WorkLocationEnum::WFO->value ? 'fw-bold' : '' }}">{{ $scan->created_at->format('H:i:s') }}</small>
                                                        @foreach ($schedule->dates[$_date] ?? [] as $shift)
                                                            @if (count(array_filter($shift)))
                                                                @php($tolerance = Carbon\Carbon::parse(strtotime($shift[0] . '+1 minute')))
                                                                @if ($loop->parent->first && $scan->created_at->gte($tolerance))
                                                                    <i class="mdi mdi-alert-circle text-danger position-absolute end-0" data-bs-toggle="tooltip" title="Terlambat {{ $scan->created_at->longAbsoluteDiffForHumans($tolerance) }}" style="top: -2px;"></i>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                                @php($day++)
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                @if ($schedule)
                    <div class="card-body">
                        Hari efektif kerja kamu bulan {{ $month->formatLocalized('%B %Y') }} adalah <strong>{{ count($schedule?->dates->flatten()->filter() ?: []) / 2 }} hari</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        table.calendar>tbody>tr>td:hover {
            background: #fafafa;
        }
    </style>
@endpush

@push('scripts')
    <script>
        var locationAccepted;

        document.addEventListener('DOMContentLoaded', () => {
            startTime();
            getLocation();
            locationAccepted = setInterval(ensureLocationAccepted, 1000)
        });

        function getLocation() {
            @if ($current_schedule)
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((p) => {
                        Array.from(document.querySelectorAll('.form-scan[name="submit"]')).map(el => {
                            el.classList.remove('btn-soft-secondary', 'disabled');
                            el.classList.add('btn-soft-danger', 'pulse-soft-danger');
                        })
                        Array.from(document.querySelectorAll('.form-scan[name="latlong"]')).map(el => el.value = `[${p.coords.latitude},${p.coords.longitude}]`);
                        scanDelay();
                        ensureLocationAccepted();
                        setInterval(scanDelay, 1000);
                    }, e => {
                        clearInterval(locationAccepted);
                        document.querySelector('#request-access').classList.toggle('d-none', true)
                        document.querySelector('#geolocation-notice').classList.toggle('d-none', false)
                        document.querySelector('#geolocation-notice').innerHTML = 'Duh! kami nggak dibolehin sama browser kamu buat akses lokasi, coba <a href="https://www.google.com/search?q=allow+location+access+on+my+current+browser" style="text-decoration: underline;" target="_blank">ikuti petunjuk</a> untuk pengaturan izin lokasi di browser kamu.'
                    });
                }
            @elseif ($isTeacher == true)
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((p) => {
                        Array.from(document.querySelectorAll('.form-scan[name="submit"]')).map(el => {
                            el.classList.remove('btn-soft-secondary', 'disabled');
                            el.classList.add('btn-soft-danger', 'pulse-soft-danger');
                        })
                        Array.from(document.querySelectorAll('.form-scan[name="latlong"]')).map(el => el.value = `[${p.coords.latitude},${p.coords.longitude}]`);
                        scanDelay();
                        ensureLocationAccepted();
                        setInterval(scanDelay, 1000);
                    }, e => {
                        clearInterval(locationAccepted);
                        document.querySelector('#request-access').classList.toggle('d-none', true)
                        document.querySelector('#geolocation-notice').classList.toggle('d-none', false)
                        document.querySelector('#geolocation-notice').innerHTML = 'Duh! kami nggak dibolehin sama browser kamu buat akses lokasi, coba <a href="https://www.google.com/search?q=allow+location+access+on+my+current+browser" style="text-decoration: underline;" target="_blank">ikuti petunjuk</a> untuk pengaturan izin lokasi di browser kamu.'
                    });
                }
            @endif
        };

        function scanDelay() {
            if (last_scan = @json($last_scan ? $last_scan->created_at->format('Y-m-d H:i:s') : false)) {
                let diff = new Date() - new Date(last_scan);
                let timer = parseInt(diff / 1000) < 60;
                Array.from(document.querySelectorAll('.form-scan[name="submit"]')).map(el => {
                    el.classList.toggle('btn-soft-secondary', timer);
                    el.classList.toggle('disabled', timer);
                    el.classList.toggle('btn-soft-danger', !timer);
                    el.classList.toggle('pulse-soft-danger', !timer);
                });
            }
        }

        const ensureLocationAccepted = () => {
            let accepted = document.querySelector('.form-scan[name="latlong"]').value;
            document.querySelector('#request-access').classList.toggle('d-none', accepted)
            document.querySelector('#geolocation-notice').classList.toggle('d-none', accepted)
            if (accepted) {
                clearInterval(locationAccepted)
            }
        }

        function startTime() {
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            h = padTime(h);
            m = padTime(m);
            s = padTime(s);
            document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
            setTimeout(startTime, 1000);
        }

        function padTime(i) {
            if (i < 10) {
                i = "0" + i
            };
            return i;
        }
    </script>
@endpush

@push('styles')
    <style>
        .pulse-soft-danger {
            animation: pulse-soft-danger 1s infinite;
        }

        .pulse-soft-danger:hover {
            animation: none;
        }

        @-webkit-keyframes pulse-soft-danger {
            0% {
                -webkit-box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
            }

            80% {
                -webkit-box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
            }

            100% {
                -webkit-box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
            }
        }

        @keyframes pulse-soft-danger {
            0% {
                -moz-box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
                box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
            }

            80% {
                -moz-box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
                box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
            }

            100% {
                -moz-box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
                box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
            }
        }
    </style>
@endpush
