@extends('core::layouts.default')

@section('title', 'Pengguna | ')
@section('navtitle', 'Pengguna')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar pengguna
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row gy-2 gx-2" action="{{ route('core::system.users.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama atau username ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::system.users.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th class="text-center">Peran</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr @if ($user->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                        <td width="10">
                                            <div class="rounded-circle" style="background: url('{{ $user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                        </td>
                                        <td class="fw-bold" nowrap>
                                            @if ($user->trashed() || !Auth::user()->can('show', $user))
                                                <span class="text-muted">{{ $user->profile->name }}</span>
                                            @else
                                                <a class="text-dark" href="{{ route('core::system.users.show', ['user' => $user->id, 'page' => 'profile', 'next' => url()->current()]) }}">{{ !empty($user->name) ? $user->name : $user->profile->name }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $user->username }}</td>
                                        <td class="text-center">
                                            @forelse($user->roles as $role)
                                                <span class="badge bg-dark fw-normal">{{ $role->name }}</span>
                                            @empty -
                                            @endforelse
                                        </td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($user->isnot(Auth::user()))
                                                @if ($user->trashed())
                                                    @can('restore', $user)
                                                        <form class="form-block form-confirm d-inline" action="{{ route('core::system.users.restore', ['user' => $user->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                            <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                        </form>
                                                        <form class="form-block form-confirm d-inline" action="{{ route('core::system.users.kill', ['user' => $user->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                            <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-trash-can-outline"></i></button>
                                                        </form>
                                                    @endcan
                                                @else
                                                    @can('show', $user)
                                                        <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('core::system.users.show', ['user' => $user->id, 'page' => 'profile', 'next' => url()->full()]) }}" method="post" data-bs-toggle="tooltip" title="Lihat detail"><i class="mdi mdi-eye-outline"></i></a>
                                                    @endcan
                                                    @can('destroy', $user)
                                                        <form class="form-block form-confirm d-inline" action="{{ route('core::system.users.destroy', ['user' => $user->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                            <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                        </form>
                                                    @endcan
                                                    @can(['cross-login', 'update'], $user)
                                                        <div class="dropstart d-inline">
                                                            <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                            <ul class="dropdown-menu border-0 shadow">
                                                                @can('cross-login', $user)
                                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-cross-login" data-user='{"id":"{{ $user->id }}","name":"{{ $user->name }}"}' style="cursor: pointer;"><i class="mdi mdi-login"></i> Login dengan akun ini</a></li>
                                                                @endcan
                                                                @can('update', $user)
                                                                    <li>
                                                                        <form class="dropdown-item form-block form-confirm" action="{{ route('core::system.users.repass', ['user' => $user->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                                            <button class="btn btn-link text-dark p-0"><i class="mdi mdi-lock-open-outline"></i> Setel ulang sandi</button>
                                                                        </form>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                        </div>
                                                    @endcan
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            @include('components.notfound')
                                            @if (!request('trash'))
                                                @can('store', Modules\Account\Models\User::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" onclick='document.querySelector(`[name="name"]`).focus()'><i class="mdi mdi-plus"></i> Tambah pengguna baru</a>
                                                    </div>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        {{ $users->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $users_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah pengguna</div>
                </div>
                <div><i class="mdi mdi-account-group-outline mdi-48px text-light"></i></div>
            </div>
            @can('store', Modules\Account\Models\User::class)
                <div class="card border-0">
                    <div class="card-body"><i class="mdi mdi-account-plus-outline"></i> Tambah pengguna baru</div>
                    <div class="card-body border-top">
                        <form class="form-block" action="{{ route('core::system.users.store', ['next' => url()->full()]) }}" method="post"> @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Nama lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::system.users.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat pengguna yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
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
