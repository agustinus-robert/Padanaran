@extends('asset::layouts.default')

@section('title', 'Aset | Inventaris | Pengajuan')

@section('navtitle', 'Pengajuan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar pengajuan
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('asset::inventories.lease.manages.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('asset::inventories.lease.manages.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th width="35%">Keterangan</th>
                                    <th width="35%">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($borrows as $borrow)
                                    <tr @if ($borrow->trashed()) class="table-light text-muted" @endif>
                                        <td class="text-center">{{ $loop->iteration + $borrows->firstItem() - 1 }}</td>
                                        <td>{{ $borrow->receiver->name }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $borrow->meta->for }}</div>
                                            <small class="text-muted">
                                                diajukan pada: {{ $borrow->created_at->isoFormat('LL') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-secondary text-dark">{{ $borrow->status() }}</span>
                                        </td>
                                        <td class="text-end" nowrap>
                                            @if ($borrow->trashed())
                                                <form class="form-block form-confirm d-inline" action="{{ route('asset::inventories.lease.manages.restore', ['manage' => $borrow->id, 'next' => route('asset::inventories.lease.manages.index')]) }}" method="post"> @csrf @method('put')
                                                    <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                </form>
                                                <form class="form-block form-confirm d-inline" action="{{ route('asset::inventories.lease.manages.kill', ['manage' => $borrow->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                    <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus permanen"><i class="mdi mdi-trash-can-outline"></i></button>
                                                </form>
                                            @else
                                                <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $borrow->id }}">
                                                    <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat daftar"><i class="mdi mdi-file-tree-outline"></i></button>
                                                </span>
                                                <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('asset::inventories.lease.manages.show', ['manage' => $borrow->id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Lihat"><i class="mdi mdi-eye"></i></a>
                                                <form class="form-block form-confirm d-inline" action="{{ route('asset::inventories.lease.manages.destroy', ['manage' => $borrow->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                    <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 p-0" colspan="100%">
                                            <div class="collapse" id="collapse-{{ $borrow->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th class="border-bottom fw-normal" width="5%"></th>
                                                            <th class="border-bottom fw-normal">Kategori</th>
                                                            <th class="border-bottom fw-normal">Peminjaman</th>
                                                            <th class="border-bottom fw-normal">Pengembalian</th>
                                                            <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($borrow->items->groupBy(['modelable_type', 'modelable_id']) as $key => $_borrow)
                                                            <tr>
                                                                <td></td>
                                                                <td colspan="4" class="fw-bold">{{ \Modules\Core\Enums\BorrowableTypeEnum::tryFromInstance($key)->label() }}</td>
                                                            </tr>
                                                            @foreach ($_borrow as $k => $item)
                                                                @php($start = $item->first())
                                                                @php($end = $item->last())
                                                                <tr>
                                                                    <td width="5%" valign="top"></td>
                                                                    <td style="width: 240px;" valign="top">
                                                                        {{ $start->modelable->name }}
                                                                    </td>
                                                                    <td style="width: 180px;" valign="top">
                                                                        @foreach ($item->take(3) as $v)
                                                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none">
                                                                                {{ !is_null($v->received_at) ? strftime('%d %B %Y %R', strtotime($v->received_at)) : '-' }}
                                                                            </span>
                                                                        @endforeach
                                                                        @if ($item->count() > 3)
                                                                            <div class="small text-muted">
                                                                                + {{ $item->count() - 3 }} lainnya
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                    <td valign="top">
                                                                        <span class="badge bg-soft-secondary text-dark fw-normal user-select-none">
                                                                            {{ !is_null($end->returned_at) ? strftime('%d %B %Y %R', strtotime($end->returned_at)) : '-' }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="pe-2 py-2" valign="top" nowrap>
                                                                        {{ $end->giver->name }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @empty
                                                            <tr>
                                                                <td colspan="100%" class="text-muted">Belum ada data peminjaman inventaris.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
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
                        {{ $borrows->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $borrow_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah peminjaman</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action" href="{{ route('asset::inventories.lease.manages.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Buat pengajuan</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('asset::inventories.lease.manages.index', ['trashed' => !request('trashed')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat pengajuan yang {{ request('trashed') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
