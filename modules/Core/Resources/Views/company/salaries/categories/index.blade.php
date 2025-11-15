@extends('core::layouts.default')

@section('title', 'Kategori gaji | ')
@section('navtitle', 'Kategori gaji')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar kategori gaji
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('core::company.salaries.categories.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::company.salaries.categories.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                        <table class="table">
                            <thead class="font-weight-bold">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Index</th>
                                    <th>Kategori</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr @if ($category->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $categories->firstItem() - 1 }}</td>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td class="text-muted">#{{ $category->az }}</td>
                                        <td>{{ $category->slip->name }}</td>
                                        <td class="text-end">
                                            @if ($category->trashed())
                                                @can('restore', $category)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('core::company.salaries.categories.restore', ['category' => $category->id, 'next' => route('core::company.salaries.categories.index')]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('update', $category)
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('core::company.salaries.categories.show', ['category' => $category->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('kill', $category)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('core::company.salaries.categories.destroy', ['category' => $category->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
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
                        {{ $categories->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $categories->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah kategori gaji</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            @can('store', Modules\Core\Models\CompanySalarySlipCategory::class)
                <div class="card border-0">
                    <div class="card-body"><i class="mdi mdi-plus"></i> Tambah kategori gaji baru</div>
                    <div class="card-body border-top">
                        <form class="form-block" action="{{ route('core::company.salaries.categories.store', ['next' => url()->full()]) }}" method="post"> @csrf
                            <div class="mb-3">
                                <label class="form-label required" for="az">Index urutan</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('az') is-invalid @enderror" name="az" value="{{ old('az') }}" required>
                                    <div class="input-group-text">#</div>
                                </div>
                                @error('az')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label required" for="slip_id">Pilih slip</label>
                                <div class="input-group">
                                    <select name="slip_id" id="slip_id" class="form-select @error('slip_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih --</option>
                                        @forelse($slips as $slip)
                                            <option value="{{ $slip->id }}">{{ $slip->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                @error('slip_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label required" for="name">Nama kategori</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.salaries.categories.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat slip gaji yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
