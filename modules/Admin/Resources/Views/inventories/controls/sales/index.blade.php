@extends('asset::layouts.default')

@section('title', 'Aset | Inventaris | Penjualan')

@section('navtitle', 'Penjualan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar penjualan barang
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('asset::inventories.controls.sales.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('asset::inventories.controls.sales.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Penanggung jawab</th>
                                    <th class="text-center">Jumlah barang</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr @if ($item->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $items->firstItem() - 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->seller->name }}</td>
                                        <td class="text-center">{{ count($item->meta->items) }}</td>
                                        <td class="text-end" nowrap="">
                                            <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('asset::inventories.controls.sales.show', ['sale' => $item->id, 'next' => url()->full()]) }}" method="post" data-bs-toggle="tooltip" title="lihat"><i class="mdi mdi-eye"></i></a>
                                            <form class="form-block form-confirm d-inline" action="{{ route('asset::inventories.controls.sales.show', ['sale' => $item->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
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
                    <div class="display-4">{{ $items->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah penjualan</div>
                </div>
                <div><i class="mdi mdi-basket-unfill mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', Modules\Core\Models\CompanyInventory::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('asset::inventories.controls.sales.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Tambah penjualan</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('asset::inventories.controls.sales.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat penjualan yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
