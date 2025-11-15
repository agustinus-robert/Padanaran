@extends('asset::layouts.default')

@section('title', 'Aset | Inventaris | Restock')

@section('navtitle', 'Restock')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar item
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('asset::inventories.controls.restocks.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="col-auto flex-grow-1">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('asset::inventories.controls.restocks.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Lokasi</th>
                                    <th>Pengguna</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr @if($item->trashed()) class="table-light text-muted" @endif>
                                    <td>{{ ($loop->iteration + $items->firstItem() - 1) }}</td>
                                    <td>{{ $item->kd }}</td>
                                    <td>
                                        {{ $item->category }} - {{ $item->name }} ({{ $item->brand }})
                                    </td>
                                    <td>{{ $item->placeable_name }}</td>
                                    <td>{{ $item->user->name ?: $item->pic->name }}</td>
                                    <td class="text-end" nowrap="">
                                        @can('update', $item)
                                        <a class="btn btn-soft-success px-2 py-1 rounded" href="{{ route('asset::inventories.controls.restocks.show', ['item' => $item->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Tambah">
                                            <i class="mdi mdi-plus"></i>
                                        </a>
                                        @endcan
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
            <div class="card card-body py-4 border-0 d-flex flex-row justify-content-between align-items-center">
                <div>
                    <div class="display-4">{{ $items->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah item</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
        </div>
    </div>
@endsection