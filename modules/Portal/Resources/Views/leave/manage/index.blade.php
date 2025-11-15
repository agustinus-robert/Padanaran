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

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-customize"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <div class="px-lg-2">

                            @if (env('DEMO') == 1)
                                <div class="row g-0">
                                    <div class="row no-gutters">
                                        @foreach (outletList(Auth::user()->id) as $key => $value)
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="{{ route('poz::dashboard') }}?outlet={{ $value->id }}">
                                                    <i class="bx bx-building-house" style="font-size:24px;"></i>
                                                    <span>{{ $value->name }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (env('DEMO') == 0)
                                <div class="row g-0">
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('core::dashboard') }}">
                                            <i class="bx bxs-wrench" style='font-size:30px;'></i>
                                            <span>Setting</span>
                                        </a>
                                    </div>
                                    {{-- <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('portal::dashboard.index') }}">
                                            <i class="bx bx-cart-alt" style='font-size:30px;'></i>
                                            <span>Dashboard POS</span>
                                        </a>
                                    </div> --}}

                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('administration::dashboard') }}">
                                            <i class="bx bxs-buildings" style='font-size:30px;'></i>
                                            <span>Tata Usaha</span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if (env('DEMO') == 0)
                                <div class="row no-gutters">
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('teacher::home') }}">
                                            <i class="bx bxs-book-content" style='font-size:30px;'></i>
                                            <span>Guru</span>
                                        </a>
                                    </div>

                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('academic::home') }}">
                                            <i class="bx bxs-chalkboard" style='font-size:30px;'></i>
                                            <span>Akademik</span>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('counseling::home') }}">
                                            <i class="bx bx-user-voice" style='font-size:30px;'></i>
                                            <span>Konseling</span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if (env('DEMO') == 0)
                                <div class="row no-gutters">
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('hrms::dashboard') }}">
                                            <i class="bx bxs-user-pin" style='font-size:30px;'></i>
                                            <span>HRMS</span>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('portal::dashboard-msdm.index') }}">
                                            <i class="bx bxs-user-pin" style='font-size:30px;'></i>
                                            <span>Dashboard MSDM</span>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{ route('finance::dashboard') }}">
                                            <i class="bx bx-money" style='font-size:30px;'></i>
                                            <span>Finance</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
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

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="#" id="topnav-uielement" role="button">
                                <i class="bx bx-user-pin me-2"></i>
                                <span key="t-ui-elements"> Profil</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('portal::outlet.manage-outlet.index') }}" id="topnav-uielement" role="button">
                                <i class="bx bx-building-house me-2"></i>
                                <span key="t-ui-elements"> Kelola Outlet</span>
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
            <h2 class="mb-1">Kelola pengajuan</h2>
            <div class="text-muted">Kamu dapat menyetujui/menolak/merevisi pengajuan izin yang diajukan guru!</div>
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
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Data pengajuan izin
                </div>
                <div class="card-body border-top border-light">
                    <form class="form-block row gy-2 gx-2" action="{{ route('portal::leave.manage.index') }}" method="get">
                        <input name="pending" type="hidden" value="{{ request('pending') }}">
                        <div class="flex-grow-1 col-auto">
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('portal::leave.manage.index', request()->only('pending')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
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
                                <th>Kategori</th>
                                <th nowrap>Tgl pengajuan</th>
                                <th nowrap>Waktu izin</th>
                                <th class="text-center">Lampiran</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                                <tr @if ($leave->trashed()) class="text-muted" @endif>
                                    <td>{{ $leave->employee->user->name }}</td>
                                    <td style="min-width: 200px;" class="py-3">
                                        <div>{{ $leave->category->name }}</div>
                                        <small class="text-muted">{{ $leave->description }}</small>
                                    </td>
                                    <td class="small">{{ $leave->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td style="min-width: 200px;">
                                        @foreach (collect($leave->dates)->take(3) as $date)
                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset>
                                                @isset($date['f']) <i class="mdi mdi-account-network-outline text-danger"></i>
                                            @endif {{ strftime('%d %B %Y', strtotime($date['d'])) }} @isset($date['t_s']) pukul {{ $date['t_s'] }} @endisset @isset($date['t_e']) s.d. {{ $date['t_e'] }} @endisset
                                            </span>
                                @endforeach
                                @php($remain = collect($leave->dates)->count() - 3)
                                @if ($remain > 0)
                                    <span class="badge text-dark fw-normal user-select-none">+{{ $remain }} lainnya</span>
                                @endif
                                </td>
                                <td class="text-center">
                                    @if (isset($leave->attachment) && Storage::exists($leave->attachment))
                                        <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($leave->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                    @endif
                                </td>
                                <td nowrap>@include('portal::leave.components.status', ['leave' => $leave])</td>
                                <td nowrap class="text-end py-1">
                                    @unless($leave->trashed())
                                        @if ($leave->hasApprovables())
                                            <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $leave->id }}">
                                                <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                            </span>
                                        @endif
                                        <div class="dropstart d-inline">
                                            <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                            <ul class="dropdown-menu border-0 shadow">
                                                <li><a class="dropdown-item" href="{{ route('portal::leave.manage.show', ['leave' => $leave->id, 'next' => request('next')]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                @if (isset($leave->attachment) && Storage::exists($leave->attachment))
                                                    <li><a class="dropdown-item" href="{{ Storage::url($leave->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline me-1"></i> Lihat lampiran</a></li>
                                                @endif
                                                <li><a class="dropdown-item" href="{{ route('portal::leave.print', ['leave' => $leave->id]) }}" target="_blank"><i class="mdi mdi-printer-outline me-1"></i> Cetak dokumen (.pdf)</a></li>
                                            </ul>
                                        </div>
                                    @endunless
                                </td>
                                </tr>
                                @if ($leave->hasApprovables() && !$leave->trashed())
                                    <tr>
                                        <td class="p-0" colspan="7">
                                            <div class="collapse @if ($leave->hasAnyApprovableResultIn('PENDING')) show @endif" id="collapse-{{ $leave->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th class="border-bottom fw-normal">Jenis</th>
                                                            <th class="border-bottom fw-normal" colspan="2">Persetujuan</th>
                                                            <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($leave->approvables->sortBy('level') as $approvable)
                                                            <tr>
                                                                <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                <td @if ($loop->last) class="border-0" @endif>
                                                                    <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                </td>
                                                                <td class="ps-0 small">{{ $approvable->reason }}</td>
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
                            {{ $leaves->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card border-0">
                        <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                            <div>
                                <div class="display-4">{{ $pending_leaves_count }}</div>
                                <div class="small fw-bold text-secondary text-uppercase">Jumlah pengajuan tertunda</div>
                            </div>
                            <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                        </div>
                        <div class="list-group list-group-flush border-top">
                            <a class="list-group-item list-group-item-action text-danger" href="{{ route('portal::leave.manage.index', ['pending' => !request('pending')]) }}"><i class="mdi mdi-progress-clock"></i> {{ request('pending') == 1 ? 'Tampilkan semua pengajuan' : 'Hanya tampilkan pengajuan yang tertunda' }}</a>
                        </div>
                    </div>
                    <a class="btn w-100 text-start d-flex justify-content-between align-items-center bg-white py-3" style="cursor: pointer;" href="{{ route('portal::leave.submission.create', ['next' => url()->full()]) }}">
                        <div>Pengajuan izin baru <br> <small class="text-muted">Silakan buat pengajuan izin kamu di sini</small></div>
                        <i class="mdi mdi-chevron-right-circle-outline ms-3"></i>
                    </a>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
