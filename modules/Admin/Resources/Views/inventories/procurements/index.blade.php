@extends('admin::layouts.default')

@section('title', 'Pengajuan Inventaris')

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
                        <form class="form-block row g-2" action="{{ route('admin::inventories.procurements.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('admin::inventories.procurements.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal pengajuan</th>
                                    <th>Pembelian</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($procurements as $procurement)
                                    <tr @if ($procurement->trashed()) class="table-light text-muted" @endif>
                                        <td class="text-center">{{ $loop->iteration + $procurements->firstItem() - 1 }}</td>
                                        <td>{{ $procurement->name }}</td>
                                        <td>{{ $procurement->description }}</td>
                                        <td>{{ $procurement->items->count() }}</td>
                                        <td>{{ $procurement->created_at->isoFormat('LLL') }}</td>
                                        <td class="text-center">
                                            @if ($procurement->user_id == Auth::user()->id && $procurement->isApproved())
                                                <a class="btn rounded px-2 py-1" href="{{ route('admin::inventories.controls.purchases.create', ['purcase' => $procurement->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Pembelian kolekting"><i class="mdi mdi-cart-outline"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-end" nowrap>
                                            @if ($procurement->trashed())
                                                @can('restore', $procurement)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('admin::inventories.procurements.restore', ['procurement' => $procurement->id, 'next' => route('admin::inventories.procurements.index')]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('update', $procurement)
                                                    <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('admin::inventories.procurements.show', ['procurement' => $procurement->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Lihat"><i class="mdi mdi-eye"></i></a>
                                                    @if ($procurement->user_id == Auth::user()->id)
                                                        <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('admin::inventories.procurements.edit', ['procurement' => $procurement->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                    @endif
                                                @endcan
                                                @can('kill', $procurement)
                                                    @if ($procurement->user_id == Auth::user()->id)
                                                        <form class="form-block form-confirm d-inline" action="{{ route('admin::inventories.procurements.destroy', ['procurement' => $procurement->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                            <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            @endif
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
                        {{ $procurements->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $procurements->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah pengajuan</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', Modules\Admin\Models\Procurement::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('admin::inventories.procurements.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus-circle-outline"></i> Buat pengajuan</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('admin::inventories.procurements.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat pengajuan yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
