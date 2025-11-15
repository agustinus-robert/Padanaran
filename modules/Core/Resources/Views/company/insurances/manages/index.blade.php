@extends('core::layouts.default')

@section('title', 'Core | Insurances | Manage')

@section('navtitle', 'Manage')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar asuransi
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('core::company.insurances.manages.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::company.insurances.manages.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($insurances as $insurance)
                                    <tr @if ($insurance->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $insurances->firstItem() - 1 }}</td>
                                        <td>{{ $insurance->kd }}</td>
                                        <td><strong>{{ $insurance->name }}</strong></td>
                                        <td class="text-end">
                                            @if ($insurance->trashed())
                                                @can('restore', $insurance)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('core::company.insurances.manages.restore', ['insurance' => $insurance->id, 'next' => route('core::company.insurances.manages.index')]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info disabled rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('update', $insurance)
                                                    <a class="btn btn-soft-warning disabled rounded px-2 py-1" href="{{ route('core::company.insurances.manages.show', ['insurance' => $insurance->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('kill', $insurance)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('core::company.insurances.manages.destroy', ['insurance' => $insurance->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                        <button class="btn btn-soft-danger disabled rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
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
                        {{ $insurances->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-insurances-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $insurances->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah item</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', Modules\Core\Models\CompanyBuilding::class)
                        <a class="list-group-item list-group-item-action disabled" href="{{ route('core::company.insurances.manages.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Tambah baru</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger disabled" href="{{ route('core::company.insurances.manages.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat item yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
