@extends('layouts.horizontal-layout')

@section('title', 'Peran | ')
@section('navtitle', 'Peran')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')
    @include('components.navbar-admin')

   <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center bg-gradient-white">
                        <span class="material-symbols-rounded me-2 text-black">format_list_bulleted</span>
                        <h5 class="mb-0 text-black">Daftar Peran</h5>
                    </div>
                    <div class="card-body border-top">
                    <form class="row gx-2 gy-2 align-items-stretch" action="{{ route('core::company.roles.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">

                            <!-- Input dengan border dan padding -->
                            <div class="col">
                                <input class="form-control border border-light rounded-2"
                                    name="search"
                                    placeholder="Cari nama ..."
                                    value="{{ request('search') }}"
                                    style="padding-left: 0.75rem; padding-right: 0.75rem;" />
                            </div>

                            <!-- Tombol Reset -->
                            <div class="col-auto">
                                <a class="btn btn-light d-flex align-items-center justify-content-center h-75 px-3"
                                href="{{ route('core::company.roles.index', request()->only('trashed', 'closed')) }}">
                                    <span class="material-symbols-rounded me-1">refresh</span> Reset
                                </a>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark d-flex align-items-center justify-content-center h-75 px-3">
                                    <span class="material-symbols-rounded me-1">search</span> Cari
                                </button>
                            </div>
                        </form>


                    </div>

                    {{-- Notifikasi --}}
                    <div class="col-12">
                        <div class="container">
                            @if (Session::has('success'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert alert-success">{!! Session::get('success') !!}</div>
                                </div>
                            @endif
                            @if (Session::has('danger'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert alert-danger">{!! Session::get('danger') !!}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- List Roles --}}
                    <div class="list-group list-group-flush border-top">
                        @forelse($roles as $role)
                            <div class="list-group-item py-3">
                                <div class="row align-items-center">
                                    <div class="col-10">
                                        <h5>{{ $role->name }} <small class="text-muted">{{ $role->grade->name }}</small></h5>
                                        <p class="mb-0">
                                            @forelse($role->permissions->take(8) as $permission)
                                                <span class="badge bg-dark fw-normal">{{ $permission->key }}</span>
                                            @empty
                                                <span class="text-muted fst-italic">Tidak ada hak akses yang diberikan</span>
                                            @endforelse
                                            @if ($role->permissions->count() > 8)
                                                <span class="badge bg-secondary fw-normal">+{{ $role->permissions->count() - 8 }} lainnya</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-2 text-end">
                                        <span class="material-symbols-rounded text-muted" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $role->users_count ?: 0 }} pengguna">groups</span>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    @can('update', $role)
                                        <a class="btn btn-outline-info btn-sm rounded" href="{{ route('core::company.roles.show', ['role' => $role->id]) }}">
                                            <span class="material-symbols-rounded">visibility</span> Lihat detail
                                        </a>
                                    @endcan
                                    @can('destroy', $role)
                                        <form class="d-inline form-block form-confirm" action="{{ route('core::company.roles.destroy', ['role' => $role->id]) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-primary btn-sm rounded" data-toggle="tooltip" title="Hapus permanen">
                                                <span class="material-symbols-rounded">delete_forever</span>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-muted text-center">
                                @include('components.notfound')
                                @can('store', Modules\Core\Models\CompanyRole::class)
                                    <div class="mt-4">
                                        <button class="btn btn-soft-danger" onclick='document.querySelector(`[name="name"]`).focus()'>
                                            <span class="material-symbols-rounded">add</span> Tambah peran baru
                                        </button>
                                    </div>
                                @endcan
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if ($roles->hasPages())
                        <div class="card-body">{{ $roles->links() }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                {{-- Statistik jumlah role --}}
                <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4 mb-3 shadow-sm">
                    <div>
                        <div class="display-4">{{ $roles_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Jumlah peran</div>
                    </div>
                    <div><span class="material-symbols-rounded" style="font-size:48px; color:#6c757d;">local_police</span></div>
                </div>

                {{-- Form Tambah Role --}}
                @can('store', Modules\Core\Models\CompanyRole::class)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <span class="material-symbols-rounded me-2">encrypted_add_circle</span> Tambah Peran
                        </div>
                        <div class="card-body border-top">
                            <form class="form-block" action="{{ route('core::company.roles.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Kode</label>

                                    <input type="text" class="form-control p-2 border border-light rounded-2 @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd') }}" required autocomplete="off">
                                    @error('kd') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama peran</label>
                                    <input type="text" class="form-control p-2 border border-light rounded-2 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off">
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="mb-0">
                                    <x-btn variant="success"><span class="material-symbols-rounded">check</span> Simpan</x-btn>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>

@endsection
