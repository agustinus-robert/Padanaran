@extends('admin::layouts.default')

@section('title', 'Karyawan | ')
@section('navtitle', 'Karyawan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar karyawan
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row gy-2 gx-2" action="{{ route('admin::employment.employees.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('admin::employment.employees.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Perjanjian kerja</th>
                                    <th>Jabatan saat ini</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    <tr @if ($employee->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                        <td width="10">
                                            <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                        </td>
                                        <td nowrap>
                                            <strong>{{ $employee->user->name }}</strong> <br>
                                            <small class="text-muted">bergabung {{ $employee->joined_at?->diffForHumans() ?: '-' }}</small>
                                        </td>
                                        <td>
                                            @if ($contract = $employee->contract)
                                                <i class="mdi mdi-circle {{ $employee->contract->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 11pt;"></i> &nbsp; {{ $employee->contract?->kd }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @isset($contract)
                                                @forelse($contract->positions as $position)
                                                    <span class="badge bg-dark fw-normal">{{ $position->position?->name }}</span>
                                                @empty
                                                    @if (!$employee->trashed() && $contract)
                                                        <a class="btn btn-link p-0" href="{{ route('admin::employment.contracts.positions.create', ['contract' => $contract->id, 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah jabatan</a>
                                                    @else
                                                        -
                                                    @endif
                                                @endforelse
                                            @else
                                                -
                                            @endisset
                                        </td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($employee->trashed())
                                                @can('restore', $employee)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::employment.employees.restore', ['employee' => $employee->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('show', $employee)
                                                    <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('admin::employment.employees.show', ['employee' => $employee->id, 'page' => 'main', 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Lihat detail"><i class="mdi mdi-eye-outline"></i></a>
                                                @endcan
                                                @can('destroy', $employee)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::employment.employees.destroy', ['employee' => $employee->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                        <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                    </form>
                                                @endcan
                                                @can('show', $employee)
                                                    <div class="dropstart d-inline">
                                                        <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                        <ul class="dropdown-menu border-0 shadow">
                                                            <li><a class="dropdown-item" href="{{ route('admin::employment.employees.show', ['employee' => $employee->id, 'page' => 'contract', 'next' => url()->current()]) }}"><i class="mdi mdi-file-account-outline"></i> Data perjanjian kerja</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin::employment.employees.show', ['employee' => $employee->id, 'page' => 'position', 'next' => url()->current()]) }}"><i class="mdi mdi-tag-outline"></i> Jabatan</a></li>
                                                        </ul>
                                                    </div>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                @include('components.notfound')
                                                @if (!request('trash'))
                                                    @can('store', Modules\HRMS\Models\Employee::class)
                                                        <div class="mb-lg-5 mb-4 text-center">
                                                            <a class="btn btn-soft-danger" href="{{ route('admin::employment.employees.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah karyawan baru</a>
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
                            {{ $employees->appends(request()->all())->links() }}
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-4">
                <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                    <div>
                        <div class="display-4">{{ $employees_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Jumlah karyawan</div>
                    </div>
                    <div><i class="mdi mdi-account-group-outline mdi-48px text-light"></i></div>
                </div>
                <div class="card border-0">
                    <div class="card-body">Menu lainnya</div>
                    <div class="list-group list-group-flush border-top border-light">
                        @can('store', Modules\HRMS\Models\Employee::class)
                            <a class="list-group-item list-group-item-action" href="{{ route('admin::employment.employees.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah karyawan baru</a>
                        @endcan
                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('admin::employment.employees.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat karyawan yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
