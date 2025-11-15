@extends('asset::layouts.default')

@section('title', 'Kelola pengajuan | ')

@section('navtitle', 'Kelola pengajuan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.lease.items.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola peminjaman inventaris</h2>
            <div class="text-muted">Kamu dapat menyetujui/menolak/merevisi pengajuan peminjaman perangkat yang diajukan karyawan!</div>
        </div>
    </div>
    @if ($borrow->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola peminjaman inventaris ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail peminjaman inventaris</div>
                </div>
                <div class="card-body border-top">
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">Tanggal pengajuan</div>
                            <div class="fw-bold"> {{ $borrow->created_at->formatLocalized('%A, %d %B %Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Keperluan</div>
                            <div class="fw-bold"> {{ $borrow->meta->for }}</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Nama barang</div>
                        <div class="fw-bold">{{ $items->pluck('modelable.name')->unique()->first() }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Tanggal peminjaman</div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $key => $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->received_at->isoFormat('LLL') . ' ' . ($value->returned_at ? ' - ' . $value->returned_at->Format('H:i') : ' - 16:00') }}</td>
                                            <td>{!! $value->meta?->onBorrow == 1 ? '<span class="badge bg-secondary text-dark">Dalam peminjaman</span>' : '<span class="badge bg-soft-success text-success">Sudah dikembalikan</span>' !!}</td>
                                            <td class="text-end">
                                                @if ($value->meta->onBorrow == 1)
                                                    <form method="POST" class="form-block form-confirm" enctype="multipart/form-data" action="{{ route('asset::inventories.lease.manages.revert', ['manage' => $borrow->id, 'item' => $value->id, 'next' => request('next', route('asset::inventories.lease.manages.index'))]) }}"> @csrf @method('PUT')
                                                        <button class="btn btn-soft-primary rounded px-2 py-1"><i class="mdi mdi-keyboard-return"></i></button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Status peminjaman</div>
                        <div class="fw-bold">{{ $items->count() == $returned->count() ? 'Sudah dikembalikan' : 'Dalam peminjaman' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            @php
                $list = [
                    'Karyawan' => $borrow->receiver->name,
                    'Posisi' => $borrow->receiver->employee->position->position->name ?: '-',
                    'Departemen' => $borrow->receiver->employee->position->position->department->name ?: '-',
                    'Tanggal pengajuan' => $borrow->created_at->formatLocalized('%A, %d %B %Y'),
                ];
            @endphp
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-box-multiple-outline"></i> Detail karyawan
                </div>
                <div class="list-group list-group-flush border-top">
                    @foreach ($list as $key => $value)
                        <div class="list-group-item">
                            <div class="row d-flex align-items-center">
                                <div class="col-sm-6 col-xl-12">
                                    <div class="small text-muted">
                                        {{ $key }}
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-12 fw-bold">
                                    {{ $value }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if ($borrow->can('return'))
                <form method="POST" class="form-block form-confirm" enctype="multipart/form-data" action="{{ route('asset::inventories.lease.manages.revert-all', ['manage' => $borrow->id, 'next' => request('next', route('asset::inventories.lease.manages.index'))]) }}"> @csrf @method('PUT')
                    <button class="btn btn-outline-primary w-100 text-primary d-flex align-items-center mb-4 bg-white py-3 text-start" style="cursor: pointer;">
                        <i class="mdi mdi-refresh me-3"></i>
                        <div>Pengembalian <br> <small class="text-muted">klik untuk mengembalikan barang</small></div>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
