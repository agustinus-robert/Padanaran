@extends('portal::layouts.default')

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
                    <a class="text-decoration-none" href="{{ request('next', route('portal::outwork.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                    <div class="ms-4">
                        <h2 class="mb-1">Kelola pengajuan</h2>
                        <div class="text-muted">Kamu dapat menyetujui/menolak/merevisi pengajuan kegiatan yang diajukan karyawan!</div>
                    </div>
                </div>
                @if ($outwork->trashed())
                    <div class="alert alert-danger border-0">
                        <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
                    </div>
                @endif
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="mdi mdi-eye-outline"></i> Detail pengajuan
                            </div>
                            <div class="card-body border-top">
                                <div class="row gy-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Tanggal pengajuan</div>
                                        <div class="fw-bold"> {{ $outwork->created_at->formatLocalized('%A, %d %B %Y') }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Nama kegiatan</div>
                                        <div class="fw-bold"> {{ $outwork->name }}</div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="small text-muted mb-1">Tanggal kegiatan yang diajukan</div>
                                    <div>
                                        @foreach ($outwork->dates as $date)
                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset style="font-size: 14px;">
                                                @isset($date['p'])
                                                    <i class="mdi mdi-account-network-outline text-danger" data-bs-toggle="tooltip" title="Persiapan"></i>
                                                @endisset
                                                {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                                @isset($date['t_s'])
                                                    pukul {{ $date['t_s'] }}
                                                @endisset
                                                @isset($date['t_e'])
                                                    s.d. {{ $date['t_e'] }}
                                                @endisset
                                                (istirahat {{ $date['b'] ?? 0 }} menit)
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="small text-muted mb-1">Kategori</div>
                                    <div class="fw-bold">{{ $outwork->category?->name ?: '-' }}
                                        @isset($outwork->category->description)
                                            - {{ $outwork->category->description }}
                                        @endisset
                                        -
                                        @if (isset($outwork->dates->take(1)->first()['p']))
                                            <span class="badge bg-soft-secondary text-dark">Persiapan</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="small text-muted mb-1">Deskripsi/catatan/alasan</div>
                                    <div class="fw-bold">{{ $outwork->description ?: '-' }}</div>
                                </div>
                                <div class="mb-4">
                                    <div class="small text-muted mb-1">Status</div>
                                    <div>@include('portal::outwork.components.status', ['outwork' => $outwork])</div>
                                </div>
                                <div>
                                    <div class="small text-muted mb-1">Lampiran</div>
                                    @if (isset($outwork->attachment) && Storage::exists($outwork->attachment))
                                        <a href="{{ Storage::url($outwork->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i> Lihat lampiran</a>
                                    @else
                                        <div> Tidak diunggah </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-header border-top d-none d-md-block border-0">
                                <div class="row">
                                    <div class="col-md-6 small text-muted"> Penanggungjawab </div>
                                    <div class="col-md-6 small text-muted"> Status </div>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                @foreach ($outwork->approvables as $approvable)
                                    <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">
                                                {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                            </div>
                                            <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            @if ($approvable->userable->is($employee->position) && !$outwork->trashed())
                                                <form class="form-block" action="{{ route('portal::outwork.manage.update', ['approvable' => $approvable->id, 'next' => request('next', route('portal::outwork.manage.index'))]) }}" method="post"> @csrf @method('PUT')
                                                    <div class="mb-3">
                                                        <select class="@error('result') is-invalid @enderror form-select" name="result">
                                                            @foreach ($results as $result)
                                                                @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeLeave::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeLeave::$approvable_disable_result ?? []))
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
                                                    <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('portal::outwork.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
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
                        @include('portal::components.employee-detail-card', ['employee' => $outwork->employee])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
