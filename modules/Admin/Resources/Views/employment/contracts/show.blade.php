@extends('admin::layouts.default')

@section('title', 'Detail perjanjian kerja | ')
@section('navtitle', 'Detail perjanjian kerja')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('admin::employment.employees.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat detail perjanjian kerja</h2>
            <div class="text-secondary">Menampilkan informasi perjanjian kerja, detail kontak, alamat, dan peran.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card border-0">
                <div class="card-body">
                    <h4 class="mb-1">Info perjanjian kerja</h4>
                    <p class="text-muted mb-2">Informasi detail perjanjian kerja</p>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0">
                        Nama karyawan <br> <strong>{{ $contract->employee->user->name }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        Dibuat pada <br> <strong> {{ $contract->created_at->diffForHumans() }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        Masa berlaku <br> <i class="mdi mdi-circle {{ $contract->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 11pt;"></i> &nbsp; <strong>{{ $contract->start_at->isoFormat('LL') }}</strong> s.d. <strong>{{ $contract->end_at?->isoFormat('LLL') ?: 'tidak ditentukan' }}</strong>
                    </div>
                    <div class="list-group-item border-0">
                        Lokasi kerja <br> <strong>{{ $contract->work_location->label() }}</strong>
                    </div>
                    <div class="list-group-item text-muted border-0">
                        <i class="mdi mdi-account-circle"></i> ID perjanjian kerja : {{ $contract->id }}
                    </div>
                    <a class="list-group-item border-top {{ $contract->document ? 'text-danger' : 'text-muted' }} py-3" @isset($contract->document) href="{{ $contract->document->url() ?: 'javascript:;' }}" download="{{ $contract->document->label }}" @endisset>
                        <i class="mdi mdi-file-download-outline"></i> Unduh dokumen perjanjian kerja
                    </a>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-tag-outline"></i> Jabatan
                </div>
                <div class="list-group list-group-flush border-top">
                    @forelse($contract->positions as $position)
                        <div class="list-group-item @if ($position->trashed()) text-muted @endif">
                            <div class="d-flex w-100 gap-4">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $position->position->name }}</div>
                                    <div class="text-muted">{{ $position->position->departement->name }}</div>
                                    @if ($position->trashed())
                                        <i class="mdi mdi-circle text-secondary" style="font-size: 11pt;"></i> &nbsp; Dihapus</strong>
                                    @else
                                        <i class="mdi mdi-circle {{ $position->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 11pt;"></i> &nbsp; <strong>{{ $position->start_at->isoFormat('LL') }}</strong> s.d. <strong>{{ $position->end_at?->isoFormat('LLL') ?: 'tidak ditentukan' }}</strong>
                                    @endif
                                </div>
                                @if (!$position->trashed())
                                    <form class="d-inline form-block form-confirm" action="{{ route('admin::employment.contract-positions.delete', ['position' => $position->id]) }}" method="post"> @csrf @method('DELETE')
                                        <a class="btn btn-soft-warning btn-sm mb-1 rounded" href="{{ route('admin::employment.contract-positions.edit', ['position' => $position->id, 'next' => url()->current()]) }}"><i class="mdi mdi-pencil-outline"></i></a>
                                        <button class="btn btn-soft-danger btn-sm mb-1 rounded" href=""><i class="mdi mdi-trash-can-outline"></i></button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item">
                            <div class="text-muted">Belum ada jabatan yang dibuat, klik tombol di bawah untuk menambahkan.</div>
                        </div>
                    @endforelse
                </div>
                <div class="card-body border-top">
                    <a class="btn btn-soft-danger" href="{{ route('admin::employment.contracts.positions.create', ['contract' => $contract->id, 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambahkan jabatan baru</a>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-edit-outline"></i> Adendum perjanjian
                </div>
                <div class="table-responsive">
                    <table class="table-hover mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Jenis adendum</th>
                                <th>No adendum</th>
                                <th>Masa berlaku</th>
                                <th>Dokumen</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($contract->getMeta('addendum') ?? []) as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ \Modules\Core\Models\CompanyContract::find($value['contract_id'])->name ?? '' }}</td>
                                    <td>{{ $value['addendum_kd'] }}</td>
                                    <td>{{ Carbon::parse($value['start_at'])->isoFormat('LL') }} s.d. {{ isset($value['end_at']) ? Carbon::parse($value['end_at'])->isoFormat('LL') : '' }}</td>
                                    <td></td>
                                    <td>
                                        @can('destroy', $contract)
                                            <form class="form-block form-confirm d-inline" action="{{ route('admin::employment.contracts.addendums.destroy', ['contract' => $contract->id, 'addendum' => $key, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted py-4">Belum ada adendum perjanjian kerja yang ditambahkan, klik tombol di bawah untuk menambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <a class="btn btn-soft-danger" href="{{ route('admin::employment.contracts.addendums.create', ['contract' => $contract->id, 'employee' => $contract->employee->id, 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Buat adendum</a>
                </div>
            </div>
        </div>
    </div>
@endsection
