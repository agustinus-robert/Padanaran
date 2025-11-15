@extends('admin::layouts.default')

@section('title', 'Jabatan | ')
@section('navtitle', 'Jabatan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar jabatan
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('admin::system.positions.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <select class="form-select" name="department">
                                    <option value="">Semua departemen</option>
                                    @foreach ($departments as $_department)
                                        <option value="{{ $_department->id }}" @if (request('department') == $_department->id) selected @endif>{{ $_department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('admin::system.positions.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Nama</th>
                                    <th class="text-center">Visibilitas</th>
                                    <th class="text-center">Tingkat</th>
                                    <th nowrap>Diterapkan kepada</th>
                                    <th nowrap>Dibuat pada</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($positions as $position)
                                    <tr @if ($position->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $positions->firstItem() - 1 }}</td>
                                        <td nowrap>
                                            <div class="fw-bold">
                                                {{ $position->name }}
                                            </div>
                                            <small class="text-muted">{{ $position->departement->name }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if ($position->is_visible)
                                                <i class="mdi mdi-eye-outline"></i>
                                            @else
                                                <i class="mdi mdi-eye-off-outline text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-muted text-center">#{{ $position->level->value }}</td>
                                        <td nowrap>{{ $position->employee_positions_count }} pengguna</td>
                                        <td>{{ $position->created_at->diffForHumans() }}</td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($position->trashed())
                                                @can('restore', $position)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::system.positions.restore', ['position' => $position->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('update', $position)
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('admin::system.positions.show', ['position' => $position->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('destroy', $position)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::system.positions.destroy', ['position' => $position->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
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
                                                @can('store', App\Models\Position::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('admin::system.positions.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat jabatan baru</a>
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
                        {{ $positions->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $positions_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah jabatan</div>
                </div>
                <div><i class="mdi mdi-tag-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', App\Models\Position::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('admin::system.positions.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus-circle-outline"></i> Buat jabatan baru</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('admin::system.positions.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat jabatan yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
