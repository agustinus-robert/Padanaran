@extends('asset::layouts.default')

@section('title', 'Aset | Inventaris | Reparasi')

@section('navtitle', 'Reparasi')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar item
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('asset::inventories.controls.repairs.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('asset::inventories.controls.repairs.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Kondisi</th>
                                    <th>Lokasi/pengguna</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repairs as $repair)
                                    <tr @if ($repair->inventory->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $repairs->firstItem() - 1 }}</td>
                                        <td>{{ $repair->inventory->kd }}</td>
                                        <td>
                                            {{ $repair->inventory->name }} ({{ $repair->inventory->brand }})
                                        </td>
                                        <td>{{ $repair->inventory->condition->label() }}</td>
                                        <td>{{ \Modules\Core\Enums\PlaceableTypeEnum::forceTryFrom($repair->inventory->placeable_type)->label() . '/' . $repair->inventory->user->name ?: $repair->inventory->pic->name }}</td>
                                        <td class="text-end" nowrap="">
                                            @can('update', $repair->inventory)
                                                <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('asset::inventories.controls.repairs.show', ['item' => $repair->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="lihat">
                                                    <i class="mdi mdi-eye"></i>
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
                        {{ $repairs->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $repairs->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah perbaikan</div>
                </div>
                <div><i class="mdi mdi-tools mdi-48px text-light"></i></div>
            </div>
            <a class="btn btn-outline-primary w-100 text-primary d-flex align-items-center bg-white py-3 text-start" href="{{ route('asset::inventories.controls.repairs.create') }}">
                <i class="mdi mdi-plus-circle-outline me-3"></i>
                <div>Tambah perbaikan <br> <small class="text-muted">Tambah perbaikan perangkat di sini</small></div>
            </a>
        </div>
    </div>
@endsection
