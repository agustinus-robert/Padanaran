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
                            <a class="nav-link arrow-none" href="{{ route('portal::dashboard-msdm.index') }}" id="topnav-dashboard" role="button">
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
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="mdi mdi-calendar-multiselect"></i> Jadwal kerja Hari Ini
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
                                            <th>#</th>
                                            <th></th>
                                            <th>Nama</th>
                                            <th>Periode</th>
                                            <th class="text-center">Jumlah jam kerja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employees as $employee)
                                            @php($contract = $employee->contract ?: $employee->contractWithin7Days)
                                            @php($schedule = $employee->schedulesTeachers->first())
                                            <tr @class(['table-active' => is_null($contract)])>
                                                <td>{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                                <td width="10">
                                                    <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                                </td>
                                                <td nowrap>
                                                    <strong>{{ $employee->user->name }}</strong> <br>
                                                    <small class="text-muted">{{ $contract->position?->position->name ?? '' }}</small>
                                                </td>
                                                <td>{{ strftime('%B %Y', strtotime(request('month', date('Y-m')))) }}</td>
                                                <td class="text-center">{{ $employee->schedulesTeachers->first()->workdays_count ?? 'tidak ada jadwal hari' }}</td>
                                                <td class="py-2 text-end" nowrap>
                                                    @if ($contract)
                                                        @if ($schedule)
                                                            {{-- @can('show', $schedule)
                                                                <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('portal::schedule-teacher.manages.show', ['schedule' => $schedule->id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                            @endcan --}}
                                                        @else
                                                            {{-- @can('store', Modules\HRMS\Models\EmployeeScheduleTeacher::class)
                                                                <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('portal::schedule-teacher.manages.create', ['employee' => $employee->id, 'month' => request('month', date('Y-m')), 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Buat baru"><i class="mdi mdi-plus-circle-outline"></i></a>
                                            @endcan --}}
                                                        @endif
                                                        {{-- @can('destroy', $schedule)
                                                            <form class="form-block form-confirm d-inline" action="{{ route('portal::schedule-teacher.manages.destroy', ['schedule' => $schedule->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                                <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                            </form>
                                                        @endcan --}}
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    @include('components.notfound')
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-body">
                                {{ $employees->appends(request()->all())->links() }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card border-0">
                            <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                                <div>
                                    <div class="display-4">{{ $employee_count }}</div>
                                    <div class="small fw-bold text-secondary text-uppercase">Jumlah guru</div>
                                </div>
                                <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                            </div>
                        </div>
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="mdi mdi-filter-outline"></i> Filter
                            </div>
                            <div class="card-body border-top">
                                <form class="form-block" action="{{ route('portal::schedule-teacher.manages.index') }}" method="get">
                                    {{-- <div class="mb-3">
                                        <label class="form-label required" for="month">Periode</label>
                                        <input type="month" class="form-control" id="month" name="month" value="{{ request('month', date('Y-m')) }}" required>
                        </div> --}}
                                    <div class="mb-3">
                                        <label class="form-label required" for="month">Pencarian</label>
                                        <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                                    </div>
                                    <div>
                                        <button class="btn btn-soft-danger"><i class="mdi mdi-magnify"></i> Filter</button>
                                        <a class="btn btn-soft-secondary" href="{{ route('portal::schedule-teacher.manages.index') }}"><i class="mdi mdi-sync"></i> Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <a class="btn btn-outline-primary w-100 d-flex text-primary mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('portal::schedule-teacher.manages.collective') }}">
                            <i class="mdi mdi-calendar-multiple-check me-3"></i>
                            <div>Absensi jadwal kerja kolektif <br> <small style="opacity: 0.6;"></small></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
