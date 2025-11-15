@extends('portal::layouts.index')

@section('title', 'Kelola pengajuan | ')

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

                <div class="d-flex align-items-center mb-4">
                    <a class="text-decoration-none" href="{{ request('next', route('portal::overtime.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Kelola lembur</h2>
                        <div class="text-muted">Kamu dapat menyetujui/menolak/merevisi lembur karyawan!</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="mdi mdi-calendar-multiselect"></i> Data pengajuan lembur
                            </div>
                            @if (request('pending'))
                                <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                                    Hanya menampilkan pengajuan yang masih tertunda/berstatus <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-timer-outline"></i> Menunggu</div>
                                </div>
                            @endif
                            <div class="table-responsive table-responsive-xl">
                                <table class="mb-0 table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Karyawan</th>
                                            <th>Kegiatan</th>
                                            <th nowrap>Tgl pengajuan</th>
                                            <th nowrap>Waktu lembur</th>
                                            <th class="text-center">Lampiran</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($overtimes as $overtime)
                                            <tr @if ($overtime->trashed()) class="text-muted" @endif>
                                                <td>{{ $overtime->employee->user->name }}</td>
                                                <td style="max-width: 480px;" class="py-3">
                                                    <div>{{ $overtime->name }}</div>
                                                    <small class="text-muted">{{ Str::words($overtime->description, 6) }}</small>
                                                </td>
                                                <td class="small">{{ $overtime->created_at->formatLocalized('%d %B %Y') }}</td>
                                                <td style="min-width: 200px;">
                                                    @if ($overtime->dates)
                                                        @foreach ($overtime->dates?->take(3) as $date)
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
                                                    @else
                                                        <span class="text-muted small">Belum terlaksana</span>
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
                                                                <li><a class="dropdown-item" href="{{ route('portal::overtime.manage.show', ['overtime' => $overtime->id, 'next' => request('next')]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
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
                                                    <td class="p-0" colspan="7">
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
                    <div class="col-xl-4">
                        <div class="card border-0">
                            <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                                <div>
                                    <div class="display-4">{{ $pending_overtimes_count }}</div>
                                    <div class="small fw-bold text-secondary text-uppercase">Jumlah pengajuan tertunda</div>
                                </div>
                                <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                            </div>
                            <div class="list-group list-group-flush border-top">
                                <a class="list-group-item list-group-item-action text-danger" href="{{ route('portal::overtime.manage.index', ['pending' => !request('pending')]) }}"><i class="mdi mdi-progress-clock"></i> {{ request('pending') == 1 ? 'Tampilkan semua pengajuan' : 'Hanya tampilkan pengajuan yang tertunda' }}</a>
                            </div>
                        </div>
                        <a class="btn btn-outline-primary w-100 d-flex text-primary mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('portal::overtime.manage.schedule.create') }}">
                            <i class="mdi mdi-book-plus-multiple-outline me-3"></i>
                            <div>Buat perintah lembur <br> <small style="opacity: 0.8;">Jadwalkan lembur untuk tim kamu di sini!</small></div>
                        </a>
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="mdi mdi-filter-outline"></i> Filter
                            </div>
                            <div class="card-body border-top">
                                <form class="form-block" action="{{ route('portal::overtime.manage.index') }}" method="get">
                                    <div class="mb-3">
                                        <label class="form-label required">Periode pengajuan</label>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                                <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                                <span class="d-none d-sm-inline">Rentang waktu</span>
                                            </button>
                                            <input class="form-control" type="date" name="start_at" value="{{ date('Y-m-d', strtotime($start_at)) }}" required>
                                            <input class="form-control" type="date" name="end_at" value="{{ date('Y-m-d', strtotime($end_at)) }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="select-positions">Karyawan</label>
                                        <input class="form-control" name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" />
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                                        <a class="btn btn-light" href="{{ route('portal::overtime.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                                    </div>
                                </form>
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
@endpush
