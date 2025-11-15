@extends('portal::layouts.index')

@section('title', 'Cuti | ')

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

                <div class="d-flex align-items-center mb-4">
                    <a class="text-decoration-none" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Cuti</h2>
                        <div class="text-muted">Berikut adalah informasi detail pengajuan cuti/libur hari raya karyawan!</div>
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
                                            <span class="badge bg-dark fw-normal user-select-none text-white">{{ $vacation->dates->count() }} dikompensasikan</span>
                                        @else
                                            @foreach ($vacation->dates as $date)
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
                            @if ($vacation->approvables->count())
                                <div class="card-header border-top d-none d-md-block border-0">
                                    <div class="row">
                                        <div class="col-md-6 small text-muted"> Penanggungjawab </div>
                                        <div class="col-md-6 small text-muted"> Persetujuan </div>
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
                                                <div class="h-100 d-sm-flex align-items-center">
                                                    <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                    <div class="ms-sm-3 mt-sm-0 mt-2">{{ $approvable->reason }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($approvable->history)
                                            <div class="row">
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
                            @endif
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
                        @if ($vacation->can('deleted'))
                            <form class="form-block form-confirm" action="{{ route('portal::vacation.submission.destroy', ['vacation' => $vacation->id]) }}" method="post"> @csrf @method('delete')
                                <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                                    <i class="mdi mdi-trash-can-outline me-3"></i>
                                    <div>Batalkan pengajuan <br> <small class="text-muted">Hapus data pengajuan {{ $vacation->hasApprovables() ? 'sebelum disetujui oleh atasan' : '' }}</small></div>
                                </button>
                            </form>
                        @endif
                        @if ($vacation->can('canceled'))
                            <a class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start" style="cursor: pointer;" href="{{ route('portal::vacation.cancelation.show', ['vacation' => $vacation->id]) }}">
                                <i class="mdi mdi-progress-upload me-3"></i>
                                <div>Ajukan pembatalan <br> <small class="text-muted">Ajukan jika Anda membatalkan pengajuan yang telah disetujui</small></div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
