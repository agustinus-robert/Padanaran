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
                    <a class="text-decoration-none" href="{{ request('next', route('portal::vacation.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Kelola pengajuan</h2>
                        <div class="text-muted">Kamu dapat menyetujui/menolak/merevisi pengajuan cuti/libur hari raya yang diajukan karyawan!</div>
                    </div>
                </div>
                @if ($vacation->trashed())
                    <div class="alert alert-danger border-0">
                        <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
                    </div>
                @endif
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card border-0">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div><i class="mdi mdi-eye-outline"></i> Detail pengajuan</div>
                                @if (!$vacation->trashed())
                                    <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="{{ route('portal::vacation.print', ['vacation' => $vacation->id]) }}" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
                                @endif
                            </div>
                            <div class="card-body border-top">
                                <div class="row gy-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Tanggal pengajuan</div>
                                        <div class="fw-bold"> {{ $vacation->created_at->formatLocalized('%A, %d %B %Y') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Kategori cuti</div>
                                        <div class="fw-bold"> {{ $vacation->quota->category->name }}</div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="small text-muted mb-1">Tanggal cuti/libur hari raya yang diajukan</div>
                                    <div>
                                        @isset(collect($vacation->dates)->first()['cashable'])
                                            <span class="badge bg-dark fw-normal user-select-none text-white">{{ collect($vacation->dates)->count() }} dikompensasikan</span>
                                        @else
                                            @foreach (collect($vacation->dates) as $date)
                                                <span class="badge bg-soft-secondary text-dark fw-normal {{ isset($date['c']) ? 'text-decoration-line-through' : '' }} user-select-none" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset style="font-size: 14px;">
                                                    @isset($date['f'])
                                                        <i class="mdi mdi-account-network-outline text-danger"></i>
                                                    @endisset {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                                </span>
                                            @endforeach
                                        @endisset
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="small text-muted mb-1">Deskripsi/catatan/alasan</div>
                                    <div class="fw-bold">{{ $vacation->description ?: '-' }}</div>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Status</div>
                                    <div>@include('portal::vacation.components.status', ['vacation' => $vacation])</div>
                                </div>
                            </div>
                            <div class="card-header border-top d-none d-md-block border-0">
                                <div class="row">
                                    <div class="col-md-6 small text-muted"> Penanggungjawab </div>
                                    <div class="col-md-6 small text-muted"> Status </div>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                @foreach ($vacation->approvables as $approvable)
                                    <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">
                                                {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                            </div>
                                            <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if ($approvable->userable->is($employee->position) && !$vacation->trashed())
                                                <form class="form-block" action="{{ route('portal::vacation.manage.update', ['approvable' => $approvable->id, 'next' => request('next', route('portal::vacation.manage.index'))]) }}" method="post"> @csrf @method('PUT')
                                                    <div class="mb-3">
                                                        <select class="@error('result') is-invalid @enderror form-select" name="result">
                                                            @foreach ($results as $result)
                                                                @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeVacation::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeVacation::$approvable_disable_result ?? []))
                                                                    <option value="{{ $result->value }}" @selected($result->value == old('result', $approvable->result->value))>{{ $result->label() }}</option>
                                                                @endunless
                                                            @endforeach
                                                        </select>
                                                        @error('result')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea class="form-control @error('reason') is-invalid @enderror" type="text" name="reason" placeholder="Alasan ...">{{ old('reason', $approvable->reason) }}</textarea>
                                                        @error('reason')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                                    <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('portal::vacation.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
                                                </form>
                                            @else
                                                <div class="h-100 d-flex">
                                                    <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($approvable->history)
                                        <div class="row p-0">
                                            <div class="col-md-6 offset-md-6">
                                                <hr class="text-muted mt-0">
                                                <p class="small text-muted mb-1">Catatan riwayat sebelumnya</p>
                                                {{ $approvable->history->reason }}
                                            </div>
                                        </div>
                                    @endif
                                    @if (!$loop->last)
                                        <hr class="text-muted">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="mdi mdi-account-box-multiple-outline"></i> Detail karyawan
                            </div>
                            <div class="list-group list-group-flush border-top">
                                @foreach (array_filter([
            'Nama karyawan' => $vacation->quota->employee->user->name,
            'NIP' => $vacation->quota->employee->kd ?: '-',
            'Jabatan' => $vacation->quota->employee->position->position->name ?? '-',
            'Departemen' => $vacation->quota->employee->position->position->department->name ?? '-',
            'Manajer' => $vacation->quota->employee->position->position->parents->firstWhere('level.value', 4)?->employees->first()->user->name,
        ]) as $label => $value)
                                    <div class="list-group-item">
                                        <div class="row d-flex align-items-center">
                                            <div class="col-sm-6 col-xl-12">
                                                <div class="small text-muted">{{ $label }}</div>
                                            </div>
                                            <div class="col-sm-6 col-xl-12 fw-bold"> {{ $value }} </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
