@extends('admin::layouts.default')

@section('title', 'Departemen | ')
@section('navtitle', 'Departemen')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Daftar departemen
                </div>
                <div class="card-body border-top border-light">
                    <form class="form-block row g-2" action="{{ route('admin::system.departments.index') }}" method="get">
                        <input name="trash" type="hidden" value="{{ request('trash') }}">
                        <div class="flex-grow-1 col-auto">
                            <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('admin::system.departments.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="d-block">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th class="text-center">Visibilitas</th>
                                    <th nowrap>Jumlah jabatan</th>
                                    <th nowrap>Dibuat pada</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $department)
                                    <tr @if ($department->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $departments->firstItem() - 1 }}</td>
                                        <td nowrap>
                                            <strong>{{ $department->name }}</strong>
                                            <div class="text-muted">{{ $department->kd }}</div>
                                        </td>
                                        <td class="text-center">
                                            @if ($department->is_visible)
                                                <i class="mdi mdi-eye-outline"></i>
                                            @else
                                                <i class="mdi mdi-eye-off-outline text-danger"></i>
                                            @endif
                                        </td>
                                        <td nowrap>{{ $department->positions_count }} jabatan</td>
                                        <td>{{ $department->created_at->diffForHumans() }}</td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($department->trashed())
                                                @can('restore', $department)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::system.departments.restore', ['department' => $department->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('update', $department)
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('admin::system.departments.show', ['department' => $department->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('kill', $department)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::system.departments.destroy', ['department' => $department->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                        <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            @include('components.notfound')
                                            @if (!request('trash'))
                                                @can('store', App\Models\Departement::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('admin::system.departments.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat departemen baru</a>
                                                    </div>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-body">
                    {{ $departments->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $departments_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah departemen</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', App\Models\Departement::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('admin::system.departments.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus-circle-outline"></i> Buat departemen baru</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('admin::system.departments.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat departemen yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
