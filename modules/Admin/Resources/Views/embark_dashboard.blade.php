@extends('admin::layouts.default')

@section('title', 'Dasbor | ')

@section('navtitle', 'Dasbor')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between">
                        <div>
                            <img class="w-100" src="{{ asset('img/manypixels/Diversity_Flatline.svg') }}" alt="" style="height: 140px;">
                        </div>
                        <div class="order-md-first text-md-start text-center">
                            <div class="px-4 py-3">
                                <h2 class="fw-normal">Selamat datang {{ Auth::user()->name }}!</h2>
                                <div class="text-muted">di {{ config('modules.asset.name') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0">
                        <div class="card-body p-4">
                            <div class="float-end my-4">
                                <i class="mdi mdi-file-tree-outline h2 text-primary mb-0"></i>
                            </div>
                            <div class="display-4"></div>
                            <small class="text-muted">Jumlah inventaris</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0">
                        <div class="card-body p-4">
                            <div class="float-end my-4">
                                <i class="mdi mdi-tag-outline h2 text-danger mb-0"></i>
                            </div>
                            <div class="display-4"></div>
                            <small class="text-muted">Jumlah pengajuan</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0">
                        <div class="card-body p-4">
                            <div class="float-end my-4">
                                <i class="mdi mdi-account-box-multiple-outline h2 text-success mb-0"></i>
                            </div>
                            <div class="display-4"></div>
                            <small class="text-muted">Jumlah karyawan</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0">
                        <div class="card-body p-4">
                            <div class="float-end my-4">
                                <i class="mdi mdi-account-group-outline h2 text-warning mb-0"></i>
                            </div>
                            <div class="display-4"></div>
                            <small class="text-muted">Jumlah pengguna</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-history"></i> Aktivitas terakhir pengguna
                </div>
                <div class="list-group list-group-flush border-top">
                    @forelse($recent_activities as $activity)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="rounded-circle" style="height: 36px; min-width: 36px; background: url('{{ $activity->user->profile_avatar_path }}') center center no-repeat; background-size: cover;"></div>
                                <div class="flex-grow-1 ms-3">
                                    <strong>{{ $activity->user->display_name }}</strong> {!! $activity->message !!}
                                </div>
                                <div class="ms-3 text-end">
                                    <small class="text-muted">{{ $activity->created_at }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item py-4">
                            <div class="text-muted">Tidak ada aktivitas dari pengguna akhir-akhir ini</div>
                        </div>
                    @endforelse
                    {{-- <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::system.user-logs.index') }}">Lihat selengkapnya &raquo;</a> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
