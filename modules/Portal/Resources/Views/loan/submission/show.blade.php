@extends('portal::layouts.default')

@section('title', 'Pinjaman | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::loan.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Pinjaman</h2>
            <div class="text-muted">Berikut adalah informasi detail pinjaman karyawan!</div>
        </div>
    </div>
    @if ($loan->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pinjaman ini.
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
                            <div class="small text-muted">Kategori</div>
                            <div class="fw-bold"> {{ $loan->category->name }}</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Deskripsi/catatan/alasan</div>
                        <div class="fw-bold">{{ $loan->description ?: '-' }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Status pengajuan</div>
                        <div>@include('portal::loan.components.status', ['loan' => $loan])</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Status pinjaman</div>
                        <div class="fw-bold"><i class="mdi {{ $loan->paid_at ? 'mdi-check text-success' : 'mdi-clock-outline text-danger' }}" style="font-size: 11pt;"></i> {{ $loan->paid_at ? 'Lunas' : 'Berjalan' }}</div>
                    </div>
                    @if (!isset($loan->parent))
                        <div>
                            <div class="small text-muted mb-1">Lampiran</div>
                            @if (isset($loan->meta?->agreement) && Storage::exists($loan->meta?->agreement))
                                <a href="{{ Storage::url($loan->meta?->agreement) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i> Lihat lampiran</a>
                            @else
                                <div> Tidak diunggah </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-body border-top">
                    <i class="mdi mdi-grid"></i> Tabel angsuran
                </div>
                <div class="table-responsive border-top" style="max-height: 360px; overflow-y: auto;">
                    <table class="table">
                        <thead class="bg-dark text-light">
                            <tr>
                                <td></td>
                                <td>Tgl tagihan</td>
                                <td>Nominal</td>
                                <td>Dibayar</td>
                                <td>Tgl pelunasan</td>
                                <td class="text-center">Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loan->installments ?? [] as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $value->bill_at->formatLocalized('%A, %d %B %Y') }}</td>
                                    <td>Rp. {{ Str::money($value->amount, 0) }}</td>
                                    <td>Rp. {{ $value->transactions->count() ? Str::money($value->transactions->sum('amount'), 0) : '-' }}</td>
                                    <td>{{ $value->paid_off_at?->formatLocalized('%A, %d %B %Y') ?: '-' }}</td>
                                    <td class="text-center"><i class="mdi {{ $value->paid_off_at ? 'mdi-check text-success' : 'mdi-clock-outline text-danger' }}" style="font-size: 11pt;"></i> {{ $value->status }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($loan->approvables->count())
                    <div class="card-header border-top d-none d-md-block border-0">
                        <div class="row">
                            <div class="col-md-6 small text-muted"> Penanggungjawab </div>
                            <div class="col-md-6 small text-muted"> Persetujuan </div>
                        </div>
                    </div>
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
                                    <div class="h-100 d-sm-flex align-items-center">
                                        <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                        <div class="ms-sm-3 mt-sm-0 mt-2">{{ $approvable->reason }}</div>
                                    </div>
                                </div>
                            </div>
                            @if ($approvable->history)
                                <div class="row">
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
        </div>
        <div class="col-xl-4">
            @include('portal::components.employee-detail-card', ['employee' => $loan->employee])
            @if (!isset($loan->parent))
                @unless ($loan->hasAnyApprovableResultIn('REJECT') || !$loan->hasApprovables() || $loan->trashed())
                    @if ($loan->hasAllApprovableResultIn('PENDING') || $loan->hasAnyApprovableResultIn('REVISION') || !$loan->hasApprovables())
                        <form class="form-block form-confirm" action="{{ route('portal::loan.submission.destroy', ['loan' => $loan->id]) }}" method="post"> @csrf @method('delete')
                            <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start">
                                <i class="mdi mdi-trash-can-outline me-3"></i>
                                <div>Batalkan pengajuan <br> <small class="text-muted">Hapus data pengajuan {{ $loan->hasApprovables() ? 'sebelum disetujui oleh atasan' : '' }}</small></div>
                            </button>
                        </form>
                    @endif
                @endunless
            @endif
        </div>
    </div>
@endsection
