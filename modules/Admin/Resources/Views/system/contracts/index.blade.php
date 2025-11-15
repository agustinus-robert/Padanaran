@extends('admin::layouts.default')

@section('title', 'Perjanjian kerja | ')
@section('navtitle', 'Perjanjian kerja')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Daftar perjanjian kerja
                </div>
                <div class="card-body border-top border-light">
                    <form class="form-block row g-2" action="{{ route('admin::system.contracts.index') }}" method="get">
                        <input name="trash" type="hidden" value="{{ request('trash') }}">
                        <div class="flex-grow-1 col-auto">
                            <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('admin::system.contracts.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Deskripsi</th>
                                    <th nowrap>Dibuat pada</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                    <tr @if ($contract->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $contracts->firstItem() - 1 }}</td>
                                        <td nowrap>
                                            <strong>{{ $contract->name }}</strong>
                                            <div class="text-muted">{{ $contract->kd }}</div>
                                        </td>
                                        <td>{{ $contract->description ?? 'Tidak ada deksripsi' }}</td>
                                        <td>{{ $contract->created_at->diffForHumans() }}</td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($contract->trashed())
                                                @can('restore', $contract)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::system.contracts.restore', ['contract' => $contract->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('update', $contract)
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('admin::system.contracts.show', ['contract' => $contract->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('kill', $contract)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::system.contracts.destroy', ['contract' => $contract->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
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
                                                @can('store', App\Models\Contract::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('admin::system.contracts.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat departemen baru</a>
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
                    {{ $contracts->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $contracts_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah departemen</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', App\Models\Contract::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('admin::system.contracts.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus-circle-outline"></i> Buat perjanjian kerja baru</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('admin::system.contracts.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat perjanjian kerja yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
