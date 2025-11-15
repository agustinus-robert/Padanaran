@extends('core::layouts.default')

@section('title', 'Peran | ')
@section('navtitle', 'Peran')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted float-left mr-2"></i> Daftar peran
                    </div>
                    <div class="card-body border-top">
                        <form class="form-block row gy-2 gx-2" action="{{ route('core::company.roles.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::company.roles.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 p-2">
                        <div class="container">
                            @if (Session::has('success'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert alert-success">
                                        {!! Session::get('success') !!}
                                    </div>
                                </div>
                            @endif 

                            @if (Session::has('danger'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert-danger alert">
                                        {!! Session::get('danger') !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="list-group list-group-flush border-top">
                        @forelse($roles as $role)
                            <div class="list-group-item py-3">
                                <div class="row">
                                    <div class="col-10">
                                        <h5>{{ $role->name }} <small class="text-muted">{{ $role->grade->name }}</small></h5>
                                        <p>
                                            @forelse($role->permissions->take(8) as $permission)
                                                <span class="badge bg-dark fw-normal">{{ $permission->key }}</span>
                                            @empty
                                                <span class="text-muted font-italic">Tidak ada hak akses yang diberikan</span>
                                            @endforelse
                                            @if ($role->permissions->count() > 8)
                                                <span class="badge bg-secondary fw-normal">+{{ $role->permissions->count() - 8 }} lainnya</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-2">
                                        <h1 class="mb-0 text-end"><i class="mdi mdi-account-group-outline text-muted" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $role->users_count ?: 0 }} pengguna"></i></h1>
                                    </div>
                                </div>
                                @can('update', $role)
                                    <a class="btn btn-soft-primary btn-sm rounded" href="{{ route('core::company.roles.show', ['role' => $role->id]) }}"><i class="mdi mdi-eye-outline"></i> Lihat detail</a>
                                @endcan
                                @can('destroy', $role)
                                    <form class="d-inline form-block form-confirm" action="{{ route('core::company.roles.destroy', ['role' => $role->id]) }}" method="POST"> @csrf @method('DELETE')
                                        <button class="btn btn-soft-danger btn-sm rounded" data-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-delete-forever-outline"></i></button>
                                    </form>
                                @endcan
                            </div>
                        @empty
                            <div class="list-group-item text-muted">
                                @include('components.notfound')
                                @if (!request('trash'))
                                    @can('store', Modules\Core\Models\CompanyRole::class)
                                        <div class="mb-lg-5 mb-4 text-center">
                                            <button class="btn btn-soft-danger" onclick='document.querySelector(`[name="name"]`).focus()'><i class="mdi mdi-plus"></i> Tambah peran baru</button>
                                        </div>
                                    @endcan
                                @endif
                            </div>
                        @endforelse
                    </div>
                    @if ($roles->hasPages())
                        <div class="card-body">
                            {{ $roles->links() }}
                        </div>
                    @endif
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $roles_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah peran</div>
                </div>
                <div><i class="mdi mdi-shield-star-outline mdi-48px text-light"></i></div>
            </div>
            @can('store', Modules\Core\Models\CompanyRole::class)
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-shield-plus-outline float-left mr-2"></i> Tambah peran
                    </div>
                    <div class="card-body border-top">
                        <form class="form-block" action="{{ route('core::company.roles.store') }}" method="POST"> @csrf
                            <div class="mb-3">
                                <label class="form-label">Kode</label>
                                <input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd') }}" required autocomplete="off">
                                @error('kd')
                                    <small class="text-danger"> {{ $message }} </small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama peran</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
                                @error('name')
                                    <small class="text-danger"> {{ $message }} </small>
                                @enderror
                            </div>
                            <div class="mb-0 mb-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
