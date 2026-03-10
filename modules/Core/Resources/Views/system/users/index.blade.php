@extends('layouts.horizontal-layout')

@section('title', 'Pengguna | ')
@section('navtitle', 'Pengguna')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')


@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $trashed = null;
    $columns = [
        [
            'label' => '',
            'slot'  => fn($user) => "<div class='rounded-circle' style='background: url(\"{$user->profile_avatar_path}\") center center no-repeat; background-size: cover; width:32px; height:32px;'></div>",
        ],
        [
            'label' => 'Nama',
            'slot'  => function($user) {
                if ($user->trashed() || !Auth::user()->can('show', $user)) {
                    return "<span class='text-muted'>{$user->profile->name}</span>";
                }
                $name = !empty($user->name) ? $user->name : $user->profile->name;
                $url  = route('core::system.users.show', ['user' => $user->id, 'page' => 'profile', 'next' => url()->current()]);
                return "<a class='text-dark' href='{$url}'>{$name}</a>";
            },
        ],
        [
            'label' => 'Username',
            'slot'  => fn($user) => $user->username,
        ],
        [
            'label' => 'Peran',
            'slot'  => fn($user) => $user->roles->isNotEmpty()
                ? $user->roles->map(fn($role) => "<span class='badge bg-dark fw-normal'>{$role->name}</span>")->implode(' ')
                : '-',
            'class' => 'text-center',
        ],
        [
            'label' => 'Aksi',
            'slot'  => function($user) {
                if ($user->isNot(Auth::user())) {
                    // User sudah dihapus
                    if ($user->trashed()) {
                        if (Auth::user()->can('restore', $user)) {
                            return view('components.partial-actions', [
                                'item' => $user,
                                'routes' => [
                                    'restore' => 'core::system.users.restore',
                                    'kill' => 'core::system.users.kill',
                                ],
                                'trashed' => true,
                                'useModal' => false,
                            ])->render();
                        }
                    } else {
                        return view('components.partial-actions', [
                            'item' => $user,
                            'routes' => [
                                'show' => 'core::system.users.show',
                                'destroy' => 'core::system.users.destroy',
                                'repass' => 'core::system.users.repass',
                                'cross-login' => 'core::system.users.cross-login',
                            ],
                            'trashed' => false,
                            'useModal' => false,
                        ])->render();
                    }
                }
                return ''; // Jika user sendiri atau tidak punya izin
            },
            'class' => 'text-end',
        ],
    ];
@endphp

@php
    $extraMenus = [
        [
            'label' => request('trash') ? 'Lihat pengguna aktif' : 'Lihat pengguna dihapus',
            'route' => route('core::system.users.index', ['trash' => !request('trash')]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => request('trash') ? 'text-primary font-weight-bold' : 'text-danger'
        ]
    ];
@endphp

@push('additional-content')
    <x-sidebar-card 
        title="Menu Lainnya" 
        icon="settings" 
        :items="$extraMenus" 
    />
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">

                <x-table
                    :isSearch="true"
                    type="material"
                    :data="$users"
                    :columns="$columns"
                    title="Kelola Pengguna"
                    searchRoute="{{ route('core::system.users.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$users_count"
                    countLabel="Jumlah Pengguna"
                />
            </div>

            <div class="col-md-4">
                @can('store', Modules\Account\Models\User::class)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Tambah pengguna baru</h6>
                        </div>

                        <div class="card-body">
                            <form class="form-block" action="{{ route('core::system.users.store', ['next' => url()->full()]) }}" method="post"> @csrf
                                <x-input-group :isRow="true" required>
                                    <x-label value="Nama lengkap" for="name" />
                                    <x-col size="12">
                                        <x-input
                                            type="text"
                                            name="name"
                                            :value="old('name')"
                                            required
                                            @class(['is-invalid' => $errors->has('name')])
                                        />
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </x-col>
                                </x-input-group>

                                <x-input-group :isRow="true" required>
                                    <x-label value="Username" for="username" />
                                    <x-col size="12">
                                        <x-input
                                            type="text"
                                            name="username"
                                            :value="old('username')"
                                            required
                                            @class(['is-invalid' => $errors->has('username')])
                                        />
                                        @error('username')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </x-col>
                                </x-input-group>

                                <x-input-group :isRow="false">
                                    <x-col size="12">
                                        <x-btn variant="dark">
                                            <i class="mdi mdi-check"></i> Simpan
                                        </x-btn>
                                    </x-col>
                                </x-input-group>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <div class="modal fade" id="modal-cross-login" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 p-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-cross-login-title">Silakan masukkan sandi Anda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="modal-cross-login-form" class="form-block form-confirm d-inline modal-body" method="post"> @csrf
                    <div class="mb-3">
                        <label class="form-label" for="modal-cross-login-password">Sandi Anda</label>
                        <input type="password" class="form-control" id="modal-cross-login-password" name="password" required>
                    </div>
                    <p>Dengan ini saya bertanggungjawab penuh dengan akun pengguna atas nama <strong id="modal-cross-login-name"></strong></p>
                    <button class="btn btn-soft-danger"><i class="mdi mdi-arrow-right"></i> Lanjutkan</button>
                    <button type="button" class="btn btn-soft-light text-dark" data-bs-dismiss="modal"><i class="mdi mdi-arrow-left"></i> Kembali</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.getElementById('modal-cross-login').addEventListener('show.bs.modal', (e) => {
                let user = JSON.parse(e.relatedTarget.dataset.user);
                document.getElementById('modal-cross-login-form').setAttribute('action', `{{ route('core::system.users.index') }}/${user.id}/login`);
                document.getElementById('modal-cross-login-name').innerHTML = user.name;
            });
        });
    </script>
@endpush
