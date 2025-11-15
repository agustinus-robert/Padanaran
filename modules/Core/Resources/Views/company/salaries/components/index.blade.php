@extends('core::layouts.default')

@section('title', 'Komponen gaji | ')
@section('navtitle', 'Komponen gaji')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar komponen gaji
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('core::company.salaries.components.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::company.salaries.components.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th nowrap>Kategori slip</th>
                                    <th nowrap>Nama komponen</th>
                                    <th class="text-center">Operasi</th>
                                    <th class="text-center">Satuan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($components as $component)
                                    <tr @if ($component->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $components->firstItem() - 1 }}</td>
                                        <td nowrap>
                                            <div>{{ $component->slip->name }}</div>
                                            <div class="small text-muted">{{ $component->category->name }}</div>
                                        </td>
                                        <td><strong>{{ $component->name }}</strong></td>
                                        <td class="text-center">{!! $component->operate->badge() !!}</td>
                                        <td class="text-muted text-center">{{ implode(' - ', array_filter([$component->unit->prefix(), $component->unit->suffix()])) }}</td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($component->trashed())
                                                @can('restore', $component)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('core::company.salaries.components.restore', ['component' => $component->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('update', $component)
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('core::company.salaries.components.show', ['component' => $component->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('kill', $component)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('core::company.salaries.components.destroy', ['component' => $component->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
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
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        {{ $components->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $components_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah komponen gaji</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            @can('store', Modules\Core\Models\CompanySalarySlipComponent::class)
                <div class="card border-0">
                    <div class="card-body"><i class="mdi mdi-plus"></i> Tambah komponen gaji baru</div>
                    <div class="card-body border-top">
                        <form class="form-block" action="{{ route('core::company.salaries.components.store', ['next' => url()->full()]) }}" method="post"> @csrf
                            <div class="mb-3">
                                <label class="form-label required" for="ctg_id">Kategori</label>
                                <select name="ctg_id" id="ctg_id" class="form-select @error('ctg_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($slips as $slip)
                                        <optgroup label="{{ $slip->name }}">
                                            @forelse($slip->categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @empty
                                                <option value="" disabled>-- Tidak memiliki kategori --</option>
                                            @endforelse
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('ctg_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label required" for="unit">Satuan</label>
                                <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->value }}">{{ $unit->label() }} ({{ implode(' ', array_filter([$unit->prefix(), $unit->suffix()])) }})</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="operate">Jenis operasi</label>
                                <select name="operate" id="operate" class="form-select @error('operate') is-invalid @enderror">
                                    @foreach ($operates as $operate)
                                        <option value="{{ $operate->value }}">{{ $operate->label() }}</option>
                                    @endforeach
                                </select>
                                @error('operate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label required" for="name">Nama komponen</label>
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
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.salaries.components.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat komponen gaji yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
