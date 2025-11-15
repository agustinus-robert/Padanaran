@extends('portal::layouts.index')

@section('title', 'Lembur | ')

@include('components.tourguide', [
    'steps' => array_values(
        array_filter(
            [
                [
                    'selector' => '.tg-steps-overtime-submission',
                    'title' => 'Pengajuan lembur',
                    'content' => 'Tekan tombol ini untuk melakukan pengajuan lembur.',
                ],
                [
                    'disabled' => !$approvers->contains($employee->position->id),
                    'selector' => '.tg-steps-overtime-manage',
                    'title' => 'Kelola lembur',
                    'content' => 'Silakan akses menu ini buat mengelola pengajuan lembur karyawan.',
                ],
                [
                    'selector' => '.tg-steps-overtime-filter',
                    'title' => 'Filter riwayat lembur',
                    'content' => 'Gunakan filter ini untuk melihat riwayat lembur pada bulan-bulan sebelumnya.',
                ],
                [
                    'selector' => '.tg-steps-overtime-table',
                    'title' => 'Tabel riwayat lembur',
                    'content' => 'Menampilkan riwayat lembur berdasarkan filter yang diterapkan.',
                ],
            ],
            fn($step) => !($step['disabled'] ?? false))),
])

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
                @php($user=auth()->user())
                @include('portal::layouts.components.notifications')
                
                @include('layouts.shortcut_menu')

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
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex align-items-center mb-4">
                    <a class="text-decoration-none" href="{{ request('next', route('portal::dashboard-msdm.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Lembur</h2>
                        <div class="text-muted">Jangan lupa isi riwayat lemburmu kalo ada kerjaan di luar jam kerja!</div>
                    </div>
                </div>

                <div class="row">
                @if (Session::has('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    </div>
                @endif 

                @if (Session::has('danger'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert-danger alert">
                            {{ Session::get('danger') }}
                        </div>
                    </div>
                @endif
                </div>
                
                <div class="row">
                    <div class="col-xl-4">
                        <div class="card tg-steps-overtime-submission border-0">
                            <div class="card-body py-4 text-center">
                                <div class="my-4">
                                    <a class="btn btn-soft-primary rounded-circle d-flex justify-content-center align-items-center mx-auto" href="{{ route('portal::overtime.submission.create', ['next' => url()->full()]) }}" style="width: 100px; height: 100px;"><i class="mdi mdi-exit-to-app mdi-48px"></i></a>
                                </div>
                                <h4 class="mb-1">Pengajuan baru</h4>
                                <p class="text-muted mb-0">Silakan tekan tombol di atas untuk mengisi riwayat lembur baru</p>
                            </div>
                        </div>

                        @if (
                            isset($employee->position->position_id) &&
                            in_array(
                                $employee->position->position_id,
                                [
                                    \Modules\Core\Enums\PositionTypeEnum::KEPALASEKOLAH->value,
                                    \Modules\Core\Enums\PositionTypeEnum::HUMAS->value
                                ],
                                true
                            )
                        ) 

                            @if ($approvers->contains($employee->position->id))
                                <div class="list-group mb-4">
                                    <a class="list-group-item list-group-item-action p-4" href="{{ route('portal::overtime.manage.index', ['next' => url()->current()]) }}" style="border-style: dashed;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-inline-block bg-soft-secondary text-primary me-2 rounded text-center" style="height: 36px; width: 36px;">
                                                <i class="mdi mdi-calendar-check-outline mdi-24px"></i>
                                            </div>
                                            <div class="flex-grow-1">Kelola lembur</div>
                                            <i class="mdi mdi-chevron-right-circle-outline"></i>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endif
                        <div class="list-group mb-4">
                            <a class="list-group-item list-group-item-action p-4" href="javascript:;" onclick="exportExcel()" style="border-style: dashed;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-inline-block bg-soft-secondary text-success me-2 rounded text-center" style="height: 36px; width: 36px;">
                                        <i class="mdi mdi-file-excel-outline mdi-24px"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Ekspor pengajuan lembur
                                    </div>
                                    <i class="mdi mdi-chevron-right-circle-outline"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card border-0">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="mdi mdi-calendar-multiselect"></i> Riwayat lembur
                                </div>
                                <input type="checkbox" class="btn-check" id="collapse-btn" autocomplete="off" @if (request('search')) checked @endif>
                                <label class="btn btn-outline-secondary text-dark btn-sm rounded px-2 py-1" data-bs-toggle="collapse" data-bs-target="#collapse-filter" for="collapse-btn"><i class="mdi mdi-filter-outline"></i> <span class="d-none d-sm-inline">Filter</span></label>
                            </div>
                            <div class="card-body border-top border-bottom tg-steps-overtime-filter">
                                <form class="form-block row gy-2 gx-2" action="{{ route('portal::overtime.submission.index') }}" method="get">
                                    <div class="col-12 flex-grow-1 my-0">
                                        <div class="@if (request('search')) show @endif collapse" id="collapse-filter">
                                            <input class="form-control" type="search" name="search" placeholder="Cari nama/deskripsi di sini ..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 col-auto">
                                        <div class="input-group">
                                            <div class="input-group-text"><span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span><span class="d-none d-sm-inline">Periode</span></div>
                                            <button type="button" class="btn btn-light dropdown-toggle d-none d-sm-block" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">Rentang waktu</button>
                                            <input class="form-control" type="date" name="start_at" value="{{ request('start_at') }}">
                                            <input class="form-control" type="date" name="end_at" value="{{ request('end_at') }}">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a class="btn btn-light" href="{{ route('portal::overtime.submission.index') }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive table-responsive-xl tg-steps-overtime-table">
                                <table class="mb-0 table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Kegiatan</th>
                                            <th nowrap>Tgl pengajuan</th>
                                            <th nowrap>Jadwal lembur</th>
                                            <th class="text-center">Lampiran</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($overtimes as $overtime)
                                            <tr @if ($overtime->trashed()) class="text-muted" @endif>
                                                <td style="max-width: 480px;" class="py-3">
                                                    <div>{{ $overtime->name }}</div>
                                                    <small class="text-muted">{{ Str::words($overtime->description, 6) }}</small>
                                                </td>
                                                <td class="small">{{ $overtime->created_at->formatLocalized('%d %B %Y') }}</td>
                                                <td style="min-width: 200px;">
                                                    @if ($overtime->schedules)
                                                        @foreach ($overtime->schedules->take(3) as $date)
                                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset>
                                                                @isset($date['f'])
                                                                    <i class="mdi mdi-account-network-outline text-danger"></i>
                                                                @endisset
                                                                {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                                                @isset($date['t_s'])
                                                                    pukul {{ $date['t_s'] }}
                                                                @endisset
                                                                @isset($date['t_e'])
                                                                    s.d. {{ $date['t_e'] }}
                                                                @endisset
                                                            </span>
                                                        @endforeach
                                                        @php($remain = $overtime->schedules->count() - 3)
                                                        @if ($remain > 0)
                                                            <span class="badge text-dark fw-normal user-select-none">+{{ $remain }} lainnya</span>
                                                        @endif
                                                    @endif
                                                    @if ($overtime->dates)
                                                        @foreach ($overtime->dates->take(3) as $date)
                                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset>
                                                                @isset($date['f'])
                                                                    <i class="mdi mdi-account-network-outline text-danger"></i>
                                                                @endisset
                                                                {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                                                @isset($date['t_s'])
                                                                    pukul {{ $date['t_s'] }}
                                                                @endisset
                                                                @isset($date['t_e'])
                                                                    s.d. {{ $date['t_e'] }}
                                                                @endisset
                                                            </span>
                                                        @endforeach
                                                        @php($remain = $overtime->dates->count() - 3)
                                                        @if ($remain > 0)
                                                            <span class="badge text-dark fw-normal user-select-none">+{{ $remain }} lainnya</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (isset($overtime->attachment) && Storage::exists($overtime->attachment))
                                                        <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($overtime->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                                    @endif
                                                </td>
                                                <td nowrap>@include('portal::overtime.components.status', ['overtime' => $overtime])</td>
                                                <td nowrap class="py-1 text-end">
                                                    @unless ($overtime->trashed())
                                                        @if ($overtime->hasApprovables())
                                                            <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $overtime->id }}">
                                                                <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                                            </span>
                                                        @endif
                                                        <div class="dropstart d-inline">
                                                            <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                            <ul class="dropdown-menu border-0 shadow">
                                                                <li><a class="dropdown-item" href="{{ route('portal::overtime.submission.show', ['overtime' => $overtime->id, 'next' => request('next')]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                                @if (isset($overtime->attachment) && Storage::exists($overtime->attachment))
                                                                    <li><a class="dropdown-item" href="{{ Storage::url($overtime->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline me-1"></i> Lihat lampiran</a></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @endunless
                                                </td>
                                            </tr>
                                            @if ($overtime->hasApprovables() && !$overtime->trashed())
                                                <tr>
                                                    <td class="p-0" colspan="6">
                                                        <div class="@if ($overtime->hasAnyApprovableResultIn('PENDING')) show @endif collapse" id="collapse-{{ $overtime->id }}">
                                                            <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                                <thead>
                                                                    <tr class="text-muted small bg-light">
                                                                        <th class="border-bottom fw-normal">Jenis</th>
                                                                        <th class="border-bottom fw-normal" colspan="2">Persetujuan</th>
                                                                        <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($overtime->approvables as $approvable)
                                                                        <tr>
                                                                            <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                            <td @if ($loop->last) class="border-0" @endif>
                                                                                <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                            </td>
                                                                            <td class="small ps-0">{{ $approvable->reason }}</td>
                                                                            <td class="small">{{ $approvable->userable->getApproverLabel() }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="5">@include('components.notfound')</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-body">
                                {{ $overtimes->appends(request()->all())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
    @include('portal::overtime.components.excel-script')
@endpush
