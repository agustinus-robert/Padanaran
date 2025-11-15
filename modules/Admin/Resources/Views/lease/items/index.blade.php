@extends('asset::layouts.default')

@section('title', 'Aset | Inventaris | Dalam peminjaman')

@section('navtitle', 'Pengajuan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar inventaris keluar
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('asset::inventories.lease.items.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('asset::inventories.lease.items.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="font-weight-bold">
                                <tr>
                                    <th class="text-center" width="10%">No</th>
                                    <th width="25%">Nama</th>
                                    <th width="35%">Kategori</th>
                                    <th width="35%">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $key => $item)
                                    <tr @if ($item->trashed()) class="table-light text-muted" @endif>
                                        <td class="text-center">{{ $loop->iteration + $items->firstItem() - 1 }}</td>
                                        <td>{{ $item->modelable->name }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $item->modelable->meta?->ctg_name ?? '' }}</div>
                                            <small class="text-muted">
                                                No: {{ $item->modelable->meta?->inv_num ?? '' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-secondary text-dark">{{ $item->meta?->onBorrow == 1 ? 'Dalam peminjaman' : 'Sudah dikembalikan' }}</span>
                                        </td>
                                        <td class="text-end" nowrap>
                                            <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('asset::inventories.lease.items.show', ['borrow' => $item->borrow_id, 'item' => $item->modelable_id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Lihat"><i class="mdi mdi-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            @include('components.notfound')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        {{ $items->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $item_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah peminjaman</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action" href="{{ route('asset::inventories.lease.manages.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Buat pengajuan</a>
                </div>
            </div>
        </div>
    </div>
@endsection
