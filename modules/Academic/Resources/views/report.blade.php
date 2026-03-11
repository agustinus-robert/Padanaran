@extends('layouts.horizontal-layout')

@section('title', 'Rapor Murid - ')

@section('navtitle', 'Rapor Murid')

@section('breadcrumb')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item active">Rapor</li>
@endsection

@push('nav')
@include('academic::layouts.includes.navbar-academic')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @include('components.alertion-message')
            </div>
            {{-- KONTEN UTAMA --}}
            <div class="col-lg-8">
                {{-- WELCOME CARD --}}
                <div class="card card-body shadow-sm mb-4 border-0">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold text-dark">Assalamu'alaikum {{ \Str::title(auth()->user()->name) }}!</h3>
                            <p class="text-muted mb-0">Selamat datang di <span class="text-primary fw-bold">{{ config('academic.home.name') }}</span></p>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-gradient-primary text-xxs">T.A. {{ $acsem->full_name }}</span>
                        </div>
                    </div>
                </div>

                {{-- TABEL NILAI --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header pb-0 bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bolder text-dark">
                                <i class="material-symbols-rounded me-2 align-middle text-primary">school</i>Nilai Raport
                            </h6>
                            <span class="badge bg-light text-dark text-xxs border">Periode: {{ $acsem->full_name }}</span>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 table-hover">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center border-end" rowspan="2" style="width: 5%">No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-end" rowspan="2">Mata Pelajaran</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-end bg-white" colspan="2">Pengetahuan (KI-3)</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-white" colspan="2">Keterampilan (KI-4)</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Angka</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-end">Pred</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Angka</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pred</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($stsem))
                                        @forelse($stsem->reports as $report)
                                            <tr class="border-bottom">
                                                <td class="text-center text-sm border-end bg-gray-100-soft">{{ $loop->iteration }}</td>
                                                <td class="ps-4 border-end">
                                                    <span class="text-sm font-weight-bold mb-0 text-dark">{{ $report->subject->name }}</span>
                                                </td>
                                                {{-- Pengetahuan --}}
                                                <td class="align-middle text-center text-sm">
                                                    <span class="text-xs font-weight-bold">{{ $report->ki3_value }}</span>
                                                </td>
                                                <td class="align-middle text-center border-end">
                                                    <span class="badge badge-sm border border-info text-info bg-transparent">{{ $report->ki3_predicate }}</span>
                                                </td>
                                                {{-- Keterampilan --}}
                                                <td class="align-middle text-center text-sm">
                                                    <span class="text-xs font-weight-bold">{{ $report->ki4_value }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="badge badge-sm border border-success text-success bg-transparent">{{ $report->ki4_predicate }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center p-5 text-muted text-sm">Belum ada data nilai untuk semester ini.</td></tr>
                                        @endforelse
                                    @else
                                        <tr><td colspan="6" class="text-center p-5 text-danger text-sm">Data akademik/semester aktif tidak ditemukan.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR PROFIL --}}
            <div class="col-lg-4">
                @if (!empty($student->nis))
                    <div class="card card-profile shadow-sm border-0 mb-4">
                        <div class="card-body text-center p-4">
                            <div class="position-relative">
                                <img src="{{ asset('img/default-avatar.svg') }}" class="rounded-circle shadow-lg mb-3" width="100">
                            </div>
                            <h5 class="mb-1 text-dark">{{ $user->profile->full_name }}</h5>
                            <p class="text-xs text-secondary mb-3">NIS. {{ $student->nis }} @if($student->nisn) | NISN. {{ $student->nisn }} @endif</p>
                            
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                @if (!empty($user->phone->whatsapp))
                                    <a href="https://wa.me/{{ $user->phone->number }}" target="_blank" class="btn btn-icon-only btn-rounded btn-outline-success">
                                        <i class="mdi mdi-whatsapp"></i>
                                    </a>
                                @endif
                                @if (!empty($user->email->verified_at))
                                    <a href="mailto:{{ $user->email->address }}" class="btn btn-icon-only btn-rounded btn-outline-danger">
                                        <i class="mdi mdi-email-outline"></i>
                                    </a>
                                @endif
                            </div>

                            <div class="text-start bg-gray-100 p-3 border-radius-lg">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-xs text-secondary">Angkatan:</span>
                                    <span class="text-xs font-weight-bold text-dark">{{ optional($student->generation)->name ?: '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-xs text-secondary">Masuk:</span>
                                    <span class="text-xs font-weight-bold text-dark">{{ optional($student->entered_at)->diffForHumans() ?: '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-xs text-secondary">User ID:</span>
                                    <span class="text-xs font-weight-bold text-dark">#{{ $user->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- LANJUTAN/CETAK --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3">
                            <h6 class="text-sm mb-3">Opsi Lanjutan</h6>
                            <a class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center py-2" href="{{ route('academic::report.print') }}" target="_blank">
                                <i class="material-symbols-rounded me-2">print</i> Cetak Raport
                            </a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info border-0 text-white shadow-sm" role="alert">
                        <div class="d-flex">
                            <i class="material-symbols-rounded me-2">info</i>
                            <span class="text-sm">User ini tidak terdaftar sebagai murid aktif.</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
