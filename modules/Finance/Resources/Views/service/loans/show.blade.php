@extends('finance::layouts.default')

@section('title', 'Kelola pinjaman karyawan | ')

@section('navtitle', 'Kelola pinjaman karyawan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::service.loans.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
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
                        <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="{{ route('finance::service.loans.print', ['loan' => $loan->id]) }}" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
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
                        @if (isset($loan->meta->agreement) && Storage::exists($loan->meta->agreement))
                            <a href="{{ Storage::url($loan->meta->agreement) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i> Lihat lampiran</a>
                        @else
                            <div> Tidak diunggah </div>
                        @endif
                    </div>
                </div>
                <div class="card-body border-top">
                    <form class="form-block form-confirm" action="{{ route('finance::service.loans.paid', ['loan' => $loan->id]) }}" method="post"> @csrf @method('PATCH')
                        <button type="submit" class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Tandai sebagai {{ $loan->paid_at ? 'belum' : '' }} lunas</button>
                    </form>
                </div>
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
            'Atasan' => $parent,
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
