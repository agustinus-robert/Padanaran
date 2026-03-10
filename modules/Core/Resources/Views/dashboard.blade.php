@extends('layouts.horizontal-layout')

@section('title', 'Dasbor | ')

@section('navtitle', 'Dasbor')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 p-3">
                    <h2 class="fw-normal text-white">Selamat datang {{ Auth::user()->name }}!</h2>
                    <div class="text-light">di {{ config('modules.core.name') }}</div>
                </div>
            </div>

            <div class="row mt-4 mb-4">
                <div class="col-md-8">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header mx-4 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="material-symbols-rounded opacity-10">account_tree</i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0">Departemen</h6>
                                    <span class="text-xs">Jumlah departemen</span>
                                    <hr class="horizontal dark my-3">
                                    <h5 class="mb-0">{{ $statistics['departments_count'] }}</h5>
                                </div>
                            </div>
                        </div>

                        {{-- JABATAN --}}
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header mx-4 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="material-symbols-rounded opacity-10">sell</i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0">Jabatan</h6>
                                    <span class="text-xs">Jumlah jabatan</span>
                                    <hr class="horizontal dark my-3">
                                    <h5 class="mb-0">{{ $statistics['positions_count'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header mx-4 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="material-symbols-rounded opacity-10">badge</i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0">Karyawan</h6>
                                    <span class="text-xs">Jumlah karyawan</span>
                                    <hr class="horizontal dark my-3">
                                    <h5 class="mb-0">{{ $statistics['employees_count'] }}</h5>
                                </div>
                            </div>
                        </div>

                        {{-- PENGGUNA --}}
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header mx-4 p-3 text-center">
                                    <div class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="material-symbols-rounded opacity-10">groups</i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h6 class="text-center mb-0">Pengguna</h6>
                                    <span class="text-xs">Jumlah pengguna</span>
                                    <hr class="horizontal dark my-3">
                                    <h5 class="mb-0">{{ $statistics['users_count'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="row">
                        @can('access', \Modules\Account\Models\UserLog::class)
                            <div class="card" style="height: 470px;"> {{-- set height sesuai kebutuhan --}}
                                <div class="card-header pb-0">
                                    <h6>Aktivitas Terakhir Pengguna</h6>
                                </div>
                                <div class="card-body p-3" style="overflow-y:auto; height: calc(400px - 60px);"> {{-- scrollable --}}
                                    @forelse($recent_activities as $activity)
                                        <div class="d-flex align-items-center mb-3 p-2 border rounded-2">
                                            <div class="rounded-circle me-3" style="width:40px; height:40px; overflow:hidden; background:url('{{ $activity->user->profile_avatar_path }}') center center no-repeat; background-size:cover;"></div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0">
                                                    <strong>{{ $activity->user->display_name }}</strong> {!! $activity->message !!}
                                                </p>
                                                <small class="text-muted">{{ $activity->created_at->format('d M H:i') }}</small>
                                            </div>
                                            <i class="material-symbols-rounded text-primary ms-2">chevron_right</i>
                                        </div>
                                    @empty
                                        <div class="d-flex align-items-center p-3 text-muted border rounded-2">
                                            <i class="material-symbols-rounded me-2">info</i>
                                            <span>Tidak ada aktivitas dari pengguna akhir-akhir ini</span>
                                        </div>
                                    @endforelse

                                    @if($recent_activities->count())
                                        <div class="mt-2 text-end">
                                            <a href="{{ route('core::system.user-logs.index') }}" class="text-danger small font-weight-bold">
                                                Lihat selengkapnya &raquo;
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
