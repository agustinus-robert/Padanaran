@extends('portal::layouts.default')

@section('title', 'Kelola pinjaman karyawan | ')

@section('navtitle', 'Kelola pinjaman karyawan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::loan.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Lihat detail pinjaman</h2>
            <div class="text-secondary">Menampilkan informasi pinjaman karyawan, tagihan dan transaksi.</div>
        </div>
    </div>
    @if ($loan->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Data pinjaman ini telah dihapus, Anda tidak lagi dapat mengelola pinjaman ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail pinjaman</div>
                    @if (!$loan->trashed() && !$loan->parent)
                        <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="{{ route('portal::loan.print', ['loan' => $loan->id]) }}" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
                    @endif
                </div>
                <div class="card-body border-top">
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">Tanggal pengajuan</div>
                            <div class="fw-bold"> {{ $loan->submission_at->formatLocalized('%A, %d %B %Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Kategori pinjaman</div>
                            <div class="fw-bold"> {{ $loan->category->name }}</div>
                        </div>
                    </div>
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">Nominal pinjaman</div>
                            <div class="fw-bold">Rp {{ Str::money($loan->amount_total) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Tenor</div>
                            <div class="fw-bold"> {{ implode(' ', [$loan->tenor, strtolower($loan->tenor_by->label())]) }}</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Deskripsi/catatan/alasan</div>
                        <div class="fw-bold">{{ $loan->description ?: '-' }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Status</div>
                        <div><i class="mdi {{ $loan->paid_at ? 'mdi-check text-success' : 'mdi-clock-outline text-danger' }}" style="font-size: 11pt;"></i> <strong>{{ $loan->paid_at ? 'Lunas' : 'Belum lunas' }}</strong></div>
                    </div>
                    <div>
                        <div class="small text-muted mb-1">Lampiran</div>
                        @if (isset($loan->attachment) && Storage::exists($loan->attachment))
                            <a href="{{ Storage::url($loan->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i> Lihat lampiran</a>
                        @else
                            <div> Tidak diunggah </div>
                        @endif
                    </div>
                </div>
                @if (!isset($loan->parent))
                    <div class="card-body border-top">
                        @foreach ($loan->approvables as $approvable)
                            <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                                <div class="col-md-6">
                                    <div class="text-muted small mb-1">
                                        {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                    </div>
                                    <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                                </div>
                                <div class="col-md-6">
                                    @if ($approvable->userable->is($employee->position) && !$loan->trashed() && $loan->approvables->filter(fn($a) => $a->level < $approvable->level && $a->result != \Modules\Core\Enums\ApprovableResultEnum::APPROVE)->count() == 0)
                                        <form class="form-block" action="{{ route('portal::loan.manage.update', ['approvable' => $approvable->id, 'next' => request('next', route('portal::loan.manage.index'))]) }}" method="post"> @csrf @method('PUT')
                                            <div class="mb-3">
                                                <select class="form-select @error('result') is-invalid @enderror" name="result">
                                                    @foreach ($results as $result)
                                                        @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeLeave::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeLeave::$approvable_disable_result ?? []))
                                                            <option value="{{ $result->value }}" @selected($result->value == old('result', $approvable->result->value))>{{ $result->label() }}</option>
                                                        @endunless
                                                    @endforeach
                                                </select>
                                                @error('result')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <textarea class="form-control @error('reason') is-invalid @enderror" type="text" name="reason" placeholder="Alasan ...">{{ old('reason', $approvable->reason) }}</textarea>
                                                @error('reason')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                            <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('portal::loan.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
                                        </form>
                                    @else
                                        <div class="h-100 d-flex">
                                            <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if ($approvable->history)
                                <div class="row p-0">
                                    <div class="col-md-6 offset-md-6">
                                        <hr class="text-muted mt-0">
                                        <p class="small text-muted mb-1">Catatan riwayat sebelumnya</p>
                                        {{ $approvable->history->reason }}
                                    </div>
                                </div>
                            @endif
                            @if (!$loop->last)
                                <hr class="text-muted">
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-grid"></i> Tabel angsuran
                </div>
                <div class="table-responsive border-top" style="max-height: 360px; overflow-y: auto;">
                    <table class="table-hover table">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>#</th>
                                <th>Tgl tagihan</th>
                                <th>Nominal</th>
                                <th>Dibayar</th>
                                <th>Tgl pelunasan</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loan->installments as $installment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $installment->bill_at->isoFormat('LL') }}</td>
                                    <td>Rp {{ Str::money($installment->amount, 0) }}</td>
                                    <td>Rp {{ $installment->transactions->count() ? Str::money($installment->transactions->sum('amount'), 0) : '-' }}</td>
                                    <td>{{ $installment->paid_off_at?->isoFormat('lll') ?: '-' }}</td>
                                    <td>
                                        <i class="mdi {{ $installment->paid_off_at ? 'mdi-check text-success' : 'mdi-clock-outline text-danger' }}" style="font-size: 11pt;"></i> {{ $installment->status }}
                                    </td>
                                    <td class="py-2 text-end" nowrap>
                                        <a class="btn btn-soft-success rounded px-2 py-1" href="{{ route('finance::service.loans.transactions.create', ['loan' => $loan->id, 'installment' => $installment->id, 'next' => url()->current()]) }}" data-bs-toggle="tooltip" title="Buat transaksi"><i class="mdi mdi-cash"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">Tidak ada data angsuran</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-box-multiple-outline"></i> Detail karyawan
                </div>
                <div class="list-group list-group-flush border-top">
                    @foreach (array_filter([
            'Nama karyawan' => $loan->employee->user->name,
            'NIP' => $loan->employee->kd ?: '-',
            'Jabatan' => $loan->employee->position->position->name ?? '-',
            'Departemen' => $loan->employee->position->position->department->name ?? '-',
            'Atasan' => $loan->employee->position->position->parents->last()?->employees->first()->user->name ?? null,
        ]) as $label => $value)
                        <div class="list-group-item">
                            <div class="row d-flex align-items-center">
                                <div class="col-sm-6 col-xl-12">
                                    <div class="small text-muted">{{ $label }}</div>
                                </div>
                                <div class="col-sm-6 col-xl-12 fw-bold"> {{ $value }} </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if ($loan->trashed())
                <form class="form-block form-confirm" action="{{ route('finance::service.loans.restore', ['loan' => $loan->id]) }}" method="post"> @csrf @method('put')
                    <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                        <i class="mdi mdi-trash-can-outline me-3"></i>
                        <div>Pulihkan pinjaman <br> <small class="text-muted">Aktifkan kembali data pinjaman dari daftar</small></div>
                    </button>
                </form>
            @else
                <form class="form-block form-confirm" action="{{ route('finance::service.loans.destroy', ['loan' => $loan->id]) }}" method="post"> @csrf @method('delete')
                    <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                        <i class="mdi mdi-trash-can-outline me-3"></i>
                        <div>Hapus pinjaman <br> <small class="text-muted">Hapus data pinjaman dari daftar</small></div>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
