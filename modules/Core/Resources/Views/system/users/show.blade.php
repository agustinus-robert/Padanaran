@extends('core::layouts.default')

@section('title', 'Pengguna | ')
@section('navtitle', 'Pengguna')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('core::system.users.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat detail pengguna</h2>
            <div class="text-secondary">Menampilkan informasi pengguna, detail kontak, alamat, dan peran.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card border-0">
                <div class="card-body text-center">
                    <div class="py-4">
                        <img class="rounded-circle" src="{{ asset('img/default-avatar.svg') }}" alt="" width="128">
                    </div>
                    <h5 class="mb-1"><strong>{{ $user->name }}</strong></h5>
                    <p>{{ $user->username }}</p>
                    <h4 class="mb-0">
                        @if ($user->getMeta('phone_whatsapp'))
                            <a class="text-danger px-1" href="https://wa.me/{{ $user->getMeta('phone_code') . $user->getMeta('phone_number') }}" target="_blank"><i class="mdi mdi-whatsapp"></i></a>
                        @endif
                        @if ($user->email_verified_at)
                            <a class="text-danger px-1" href="mailto:{{ $user->email_address }}"><i class="mdi mdi-email-outline"></i></a>
                        @endif
                    </h4>
                </div>
                <div class="list-group list-group-flush border-top">
                    <form class="d-inline form-block form-confirm" action="{{ route('core::system.users.repass', ['user' => $user->id]) }}" method="POST" id="reset-password"> @csrf @method('PUT')
                        <a class="list-group-item list-group-item-action text-danger border-0" href="javascript:;" onclick="event.preventDefault(); $('#reset-password').submit();"><i class="mdi mdi-lock-open"></i> Atur ulang sandi</a>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <h4 class="mb-1">Info akun</h4>
                    <p class="text-muted mb-2">Informasi tentang akun {{ $user->display_name }}</p>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ([
            'Bergabung pada' => $user->created_at->diffForHumans(),
        ] as $k => $v)
                        <div class="list-group-item border-0">
                            {{ $k }} <br>
                            <span class="{{ $v ? 'fw-bold' : 'text-muted' }}">
                                {{ $v ?? 'Belum diisi' }}
                            </span>
                        </div>
                    @endforeach
                    <div class="list-group-item text-muted border-0">
                        <i class="mdi mdi-account-circle"></i> User ID : {{ $user->id }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card border-0">
                <div class="card-body text-center">
                    <ul class="nav nav-pills">
                        <li class="nav-item"> <a class="nav-link @if (request('page') == 'profile') active text-dark bg-light @endif" href="{{ route('core::system.users.show', ['user' => $user->id, 'page' => 'profile', 'next' => request('next')]) }}">Profil</a> </li>
                        <li class="nav-item"> <a class="nav-link @if (request('page') == 'username') active text-dark bg-light @endif" href="{{ route('core::system.users.show', ['user' => $user->id, 'page' => 'username', 'next' => request('next')]) }}">Username</a> </li>
                        <li class="nav-item"> <a class="nav-link @if (request('page') == 'email') active text-dark bg-light @endif" href="{{ route('core::system.users.show', ['user' => $user->id, 'page' => 'email', 'next' => request('next')]) }}">Alamat surel</a> </li>
                        <li class="nav-item"> <a class="nav-link @if (request('page') == 'phone') active text-dark bg-light @endif" href="{{ route('core::system.users.show', ['user' => $user->id, 'page' => 'phone', 'next' => request('next')]) }}">Nomor ponsel</a> </li>
                        <li class="nav-item"> <a class="nav-link @if (request('page') == 'role') active text-dark bg-light @endif" href="{{ route('core::system.users.show', ['user' => $user->id, 'page' => 'role', 'next' => request('next')]) }}">Peran</a> </li>
                    </ul>
                </div>
                <div class="card-body border-top">
                    @if (request('page') == 'profile')
                        <form class="form-block" action="{{ route('core::system.users.update.profile', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
                            @include('x-account::User.Profile.form', ['user' => $user])
                        </form>
                    @endif
                    @if (request('page') == 'username')
                        <form class="form-block" action="{{ route('core::system.users.update.username', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-10 col-lg-8">
                                    <div class="required mb-3">
                                        <label>Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required>
                                        </div>
                                        <small class="form-text text-muted">Nama unik pengguna (bukan nama lengkap), digunakan untuk login, terdiri dari huruf kecil, titik, dan angka, tanpa spasi.</small>
                                        @error('username')
                                            <small class="text-danger"> {{ $message }} </small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-0 mb-3">
                                <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
                            </div>
                        </form>
                    @endif
                    @if (request('page') == 'email')
                        <form class="form-block" action="{{ route('core::system.users.update.email', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-10 col-lg-8">
                                    @include('x-account::User.Email.form', ['user' => $user])
                                </div>
                            </div>
                        </form>
                    @endif
                    @if (request('page') == 'phone')
                        <form class="form-block" action="{{ route('core::system.users.update.phone', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-10 col-lg-8">
                                    @include('x-account::User.Phone.form', ['user' => $user])
                                </div>
                            </div>
                        </form>
                    @endif
                    @if (request('page') == 'role')
                        <form class="form-block" action="{{ route('core::system.users.update.role', ['user' => $user->id]) }}" method="POST"> @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-10 col-lg-8">
                                    @include('x-core::System.User.Role.form', ['user' => $user, 'roles' => $roles])
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
