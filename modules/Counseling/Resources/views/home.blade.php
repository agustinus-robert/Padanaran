@extends('layouts.horizontal-layout')

@section('title', 'Beranda - ')
@section('titleTemplate', config('counseling.config.name'))
@section('navtitle', 'Dashboard')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-7 col-lg-8">
                
                {{-- WELCOME CARD --}}
                <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4 position-relative">
                        <div class="row align-items-center">
                            <div class="col-sm-8">
                                <h3 class="fw-bold mb-1 text-dark">Assalamu'alaikum {{ \Str::title(auth()->user()->profile->full_name) }}!</h3>
                                <p class="text-secondary opacity-7 mb-4">Selamat datang kembali di {{ config('counseling.home.name') }}</p>
                                <div class="d-inline-block px-3 py-1 bg-light border rounded-pill">
                                    <small class="text-dark fw-bold">Tahun Ajaran: {{ $acsem->full_name }}</small>
                                </div>
                            </div>
                            <div class="col-sm-4 text-center d-none d-md-block">
                                <i class="mdi mdi-shield-check-outline text-primary opacity-2" style="font-size: 8rem; position: absolute; right: 10px; top: -20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KASUS AKHIR-AKHIR INI --}}
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="mdi mdi-alert-circle-outline me-2 text-danger"></i>Kasus Terbaru
                    </h5>
                    <a href="{{ route('counseling::cases.index') }}" class="btn btn-sm btn-light text-primary fw-bold rounded-pill shadow-none px-3">Lihat Semua</a>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <div class="list-group list-group-flush">
                        @forelse($last_cases as $case)
                            <div class="list-group-item border-0 p-3 hover-bg-light" style="transition: 0.2s;">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-gray-100 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="mdi mdi-account-alert text-secondary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0 fw-bold text-dark">{{ $case->semester->student->full_name }}</h6>
                                            <span class="text-danger fw-bold fs-5">{{ $case->point ?: '0' }} <small class="text-muted" style="font-size: 0.6rem;">Poin</small></span>
                                        </div>
                                        <div class="text-xs text-secondary mb-1">
                                            <span class="badge bg-light text-dark fw-normal border">{{ $case->semester->classroom->name }}</span> • {{ $case->category->name }}
                                        </div>
                                        <p class="text-sm text-muted mb-1">{{ \Str::limit($case->description, 80) }}</p>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">
                                            <i class="mdi mdi-clock-outline me-1"></i>{{ $case->break_at->diffForHumans() }} &bull; Saksi: {{ $case->witness }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0 mx-3 opacity-5">
                        @empty
                            <div class="p-5 text-center">
                                <i class="mdi mdi-check-circle-outline text-success display-4 d-block mb-2"></i>
                                <span class="text-muted">Tidak ada data kasus aktif saat ini.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- KONSELING AKHIR-AKHIR INI --}}
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="mdi mdi-comment-account-outline me-2 text-primary"></i>Log Konseling
                    </h5>
                    <a href="{{ route('counseling::counselings.index') }}" class="btn btn-sm btn-light text-primary fw-bold rounded-pill shadow-none px-3">Daftar Riwayat</a>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <div class="list-group list-group-flush">
                        @forelse($last_counselings as $counseling)
                            <div class="list-group-item border-0 p-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 bg-light-primary border-radius-md p-2 me-3">
                                        <i class="mdi mdi-chat-processing-outline text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $counseling->semester->student->full_name }}</h6>
                                        <small class="text-primary mb-2 d-block">{{ $counseling->semester->classroom->name }}</small>
                                        <p class="text-sm text-muted mb-2 bg-gray-100 p-2 rounded">{{ $counseling->description }}</p>
                                        <div class="d-flex align-items-center text-xs">
                                            <span class="fw-bold text-dark me-2">Tindak Lanjut:</span>
                                            <span class="{{ $counseling->follow_up ? 'text-success' : 'text-warning italic' }}">
                                                {{ $counseling->follow_up ?: 'Menunggu tindak lanjut...' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0 mx-3 opacity-5">
                        @empty
                            <div class="p-4 text-center text-muted">Belum ada data konseling terbaru.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="col-md-5 col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    @include('counseling::includes.employee-info', ['employee' => $employee])
                    <div class="mt-3">
                        @include('account::includes.account-info')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .bg-gray-100 { background-color: #f8f9fa; }
    .bg-light-primary { background-color: #eef2ff; }
    .text-xs { font-size: 0.75rem; }
    .hover-bg-light:hover { background-color: #fcfcfc; }
</style>