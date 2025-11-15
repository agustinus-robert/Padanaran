@extends('core::layouts.default')

@section('title', 'Kategori Insentif | ')
@section('navtitle', 'Kategori Insentif')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar kategori Insentif
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('core::company.services.outwork-categories.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama kategori ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::company.services.outwork-categories.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 p-2">
                        <div class="container">
                            @if (Session::has('success'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert alert-success">
                                        {!! Session::get('success') !!}
                                    </div>
                                </div>
                            @endif 

                            @if (Session::has('danger'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert-danger alert">
                                        {!! Session::get('danger') !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama kategori</th>
                                    <th>Keterangan</th>
                                    <th class="text-center" colspan="2">Tarif</th>
                                    <th nowrap>Persiapan</th>
                                    <th nowrap>Tarif tetap</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr @if ($category->trashed()) class="table-light text-muted" @endif>
                                        <td> {{ $loop->iteration + $categories->firstItem() - 1 }}</td>
                                        <td class="fw-bold" style="max-width: 160px;">{{ $category->name }}</td>
                                        <td>{{ $category->description }}</td>
                                        <td nowrap class="text-center">
                                            <a class="text-success">Rp{{ Str::money($category->price, 0, 'IDR') }}</a>
                                        </td>
                                        <td nowrap class="text-center">
                                            @if (!empty($category->meta?->in_working_hours_price))
                                                <a class="text-danger">Rp{{ Str::money($category->meta?->in_working_hours_price ?? 0, 0, 'IDR') }}</a>
                                            @endif
                                        </td>
                                        <td nowrap class="text-center">
                                            @if (!empty($category->meta?->prepareable))
                                                <code><i class="text-success mdi mdi-check-all"></i></code>
                                            @endif
                                        </td>
                                        <td nowrap class="text-center">
                                            @if (!empty($category->meta?->fixed))
                                                <code><i class="text-success mdi mdi-check-all"></i></code>
                                            @endif
                                        </td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($category->trashed())
                                                @can('restore', $category)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('core::company.services.outwork-categories.restore', ['category' => $category->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('update', $category)
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('core::company.services.outwork-categories.show', ['category' => $category->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('destroy', $category)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('core::company.services.outwork-categories.destroy', ['category' => $category->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
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
                                                @can('store', Modules\Core\Models\CompanyOutworkCategory::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('core::company.services.outwork-categories.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat kategori baru</a>
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
                        {{ $categories->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $categories_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah kategori</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', Modules\Core\Models\CompanyOutworkCategory::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('core::company.services.outwork-categories.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat kategori baru</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.services.outwork-categories.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat kategori yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
