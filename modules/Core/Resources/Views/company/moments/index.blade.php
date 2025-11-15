@extends('core::layouts.default')

@section('title', 'Hari libur | ')
@section('navtitle', 'Hari libur')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar hari libur
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('core::company.moments.index') }}" method="get">
                            <div class="flex-grow-1 col-auto">
                                <div class="input-group">
                                    <div class="input-group-text">Tahun</div>
                                    <input type="number" class="form-control" name="year" placeholder="Tahun" value="{{ request('year', date('Y')) }}" />
                                </div>
                            </div>
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama hari atau tanggal ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::company.moments.index') }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Tipe</th>
                                    <th nowrap>Nama hari libur</th>
                                    <th nowrap class="text-center">Tanggal</th>
                                    <th class="text-center">Libur</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($moments as $moment)
                                    <tr>
                                        <td> {{ $loop->iteration + $moments->firstItem() - 1 }}</td>
                                        <td>{{ $moment->type->label() ?: '-' }}</td>
                                        <td class="fw-bold" style="max-width: 160px;">{{ $moment->name }}</td>
                                        <td nowrap class="text-center">{{ strftime('%d %B %Y', strtotime($moment->date)) }}</td>
                                        <td nowrap class="text-center">
                                            @if ($moment->is_holiday)
                                                <i class="mdi mdi-check text-success"></i>
                                            @else
                                                <i class="mdi mdi-close text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="py-2 text-end" nowrap>
                                            @can('update', $moment)
                                                <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('core::company.moments.show', ['moment' => $moment->id, 'next' => url()->current()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                            @endcan
                                            @can('destroy', $moment)
                                                <form class="form-block form-confirm d-inline" action="{{ route('core::company.moments.destroy', ['moment' => $moment->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                    <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            @include('components.notfound')
                                            @if (!request('trash'))
                                                @can('store', Modules\Core\Models\CompanyDepartment::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('core::company.moments.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat hari libur baru</a>
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
                        {{ $moments->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $moments_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah hari libur tahun {{ date('Y') }}</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', Modules\Core\Models\CompanyDepartment::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('core::company.moments.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat hari libur baru</a>
                    @endcan
                </div>
            </div>
            <a class="btn btn-outline-primary w-100 text-primary d-flex align-items-center bg-white py-3 text-start" style="cursor: pointer;" href="{{ route('core::company.moments.sync', ['next' => url()->current()]) }}">
                <i class="mdi mdi-progress-upload me-3"></i>
                <div>Ambil data hari libur <br> <small class="text-muted">Daftar hari libur diambil dari API</small></div>
            </a>
        </div>
    </div>
@endsection
