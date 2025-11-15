@extends('hrms::layouts.default')

@section('title', 'Data perjanjian kerja | ')
@section('navtitle', 'Data perjanjian kerja')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar perjanjian kerja
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row gy-2 gx-2" action="{{ route('hrms::employment.contracts.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('hrms::employment.contracts.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor perjanjian kerja</th>
                                    <th>Karyawan</th>
                                    <th>Berakhir pada</th>
                                    <th class="text-center">Dokumen</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                    <tr @if ($contract->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $contracts->firstItem() - 1 }}</td>
                                        <td nowrap>
                                            <i class="mdi mdi-circle {{ $contract->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 11pt;"></i> &nbsp; <strong>{{ $contract->kd }}</strong> <br>
                                            <small class="text-muted">{{ $contract->contract->name }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $contract->employee->user->name }}</div>
                                            @if ($contract->employee->trashed())
                                                <small class="text-danger">Karyawan ini sudah dihapus</small>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $contract->end_at?->isoFormat('LLL') ?: '&infin;' !!}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-link rounded-pill {{ $contract->document ? '' : 'disabled' }} p-0" @isset($contract->document) href="{{ $contract->document->url() ?: 'javascript:;' }}" download="{{ $contract->document->label }}" @endisset><small><i class="mdi mdi-file-download-outline"></i> Unduh</small></a>
                                        </td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($contract->trashed())
                                                @can('restore', $contract)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('hrms::employment.contracts.restore', ['contract' => $contract->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                        <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('show', $contract)
                                                    <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('hrms::employment.contracts.show', ['contract' => $contract->id, 'next' => url()->full()]) }}" method="post" data-bs-toggle="tooltip" title="Lihat detail"><i class="mdi mdi-eye-outline"></i></a>
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('hrms::employment.contracts.edit', ['contract' => $contract->id, 'employee' => $contract->employee->id, 'next' => url()->full()]) }}" method="post" data-bs-toggle="tooltip" title="Ubah perjanjian kerja"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                                @can('destroy', $contract)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('hrms::employment.contracts.destroy', ['contract' => $contract->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
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
                                                @can('store', Modules\HRMS\Models\EmployeeContract::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('hrms::employment.contracts.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Buat perjanjian kerja baru</a>
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
                        {{ $contracts->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $contracts_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah perjanjian kerja aktif</div>
                </div>
                <div><i class="mdi mdi-account-group-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    @can('store', Modules\HRMS\Models\EmployeeContract::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('hrms::employment.contracts.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah perjanjian kerja baru</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('hrms::employment.contracts.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat perjanjian kerja yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
