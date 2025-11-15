@extends('admin::layouts.default')

@section('title', 'Aset | Inventaris | Item')

@section('navtitle', 'Item')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar item
                    </div>
                    @if (request('trash'))
                        <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                            Hanya menampilkan inventaris yang berstatus <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-timer-outline"></i> dihapus</div>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="font-weight-bold">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Lokasi</th>
                                    <th>Pengguna</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr @if ($item->trashed()) class="table-light text-muted" @endif>
                                        <td class="text-center">{{ $loop->iteration + $items->firstItem() - 1 }}</td>
                                        <td nowrap>
                                            <div class="fw-bold">{{ $item->meta?->inv_num ?? '' }}</div>
                                            <small><cite>{{ $item->meta->ctg_name ?? 'Tanpa kategori' }}</cite></small>
                                        </td>
                                        <td>
                                            <div>{{ $item->name }}</div>
                                            <small class="text-muted">{{ $item->brand ?: 'Tanpa merk' }}</small>
                                        </td>
                                        <td>{{ $item->placeable_name }}</td>
                                        <td>{{ $item->user->name ?: $item->pic->name }}</td>
                                        <td class="text-end" nowrap="">
                                            @if ($item->trashed())
                                                @can('restore', $item)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('asset::inventories.items.restore', ['item' => $item->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('asset::inventories.items.show', ['item' => $item->id, 'next' => url()->full()]) }}" method="post" data-bs-toggle="tooltip" title="lihat"><i class="mdi mdi-eye"></i></a>
                                                @can('update', $item)
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('asset::inventories.items.edit', ['item' => $item->id, 'next' => url()->full()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('kill', $item)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('asset::inventories.items.destroy', ['item' => $item->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                        <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                    </form>
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
                        {{ $items->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                    <div>
                        <div class="display-4">{{ $item_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Jumlah item</div>
                    </div>
                    <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
                </div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', Modules\Admin\Models\Inventory::class)
                        <a class="list-group-item list-group-item-action text-primary" href="{{ route('admin::inventories.items.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah baru</a>
                    @endcan
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('admin::inventories.items.index') }}" method="get">
                        <div class="row mb-3">
                            <label class="form-label">Kategori karyawan</label>
                            @forelse($locations as $location)
                                <label class="card-body border-secondary d-flex align-items-center py-1">
                                    <input class="form-check-input me-3" type="radio" value="{{ $location->value }}" name="location" @checked($location->value == old('location', request('location')))>
                                    <div>
                                        <div class="mb-0">{{ $location->label() }}</div>
                                    </div>
                                </label>
                            @empty
                                <div class="card-body text-muted">Tidak ada kategori inventaris</div>
                            @endforelse
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ old('category') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pencarian</label>
                            <input class="form-control" name="search" placeholder="Cari berdasar nama..." value="{{ request('search') }}" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cari nomor</label>
                            <input class="form-control" name="inv_num" placeholder="Cari berdasar no inventaris..." value="{{ request('search') }}" />
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trash" value="1" id="trash" @checked(request('trash') == 1)>
                                <label class="form-check-label" for="trash">
                                    Tampilkan data yang dihapus
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('admin::inventories.items.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action py-3" href="javascript:;" onclick="summaryExportExcel()"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi Inventaris</a>
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan.</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
    <style type="text/css">
        .ts-wrapper {
            padding: 0 !important;
        }

        .ts-control {
            border: 1px solid hsla(0, 0%, 82%, .2) !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
    <script>
        // new TomSelect('[name="category"]', {
        //     persist: false,
        //     createOnBlur: true,
        //     create: true,
        //     maxItems: 1,
        //     valueField: 'id',
        //     labelField: 'text',
        //     searchField: 'text',
        //     placeholder: 'Cari kategori di sini...',
        //     load: function(q, callback) {
        //         fetch('{{ route('api::admin.classification') }}?q=' + encodeURIComponent(q))
        //             .then(response => response.json())
        //             .then(json => {
        //                 callback(json.data);
        //             })
        //             .catch(() => {
        //                 callback();
        //             });
        //     }
        // });
    </script>
@endpush
