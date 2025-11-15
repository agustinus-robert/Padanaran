@extends('asset::layouts.default')

@section('title', 'Kelola pengajuan | ')

@section('navtitle', 'Kelola pengajuan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.lease.manages.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola pengajuan</h2>
            <div class="text-muted">Kamu dapat menyetujui/menolak/merevisi pengajuan peminjaman perangkat yang diajukan karyawan!</div>
        </div>
    </div>
    @if ($borrow->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail pengajuan</div>
                    @if (!$borrow->trashed())
                        <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="{{ route('asset::inventories.lease.manages.print', ['manage' => $borrow->id]) }}" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
                    @endif
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
                    @foreach ($borrow->items->groupBy(['modelable_type', 'modelable_id']) as $key => $_borrow)
                        <div class="mb-4">
                            <div class="small text-muted mb-1">{{ \Modules\Core\Enums\BorrowableTypeEnum::tryFromInstance($key)->label() }}</div>
                            <div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center"></th>
                                                <th>Nama item</th>
                                                <th>Tanggal diterima</th>
                                                <th>Tanggal dikembalikan</th>
                                                <th>Penanggungjawab</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($_borrow as $k => $value)
                                                @php
                                                    $start = $value->first();
                                                    $end = $value->last();
                                                @endphp
                                                <tr>
                                                    <td width="5%" valign="top">{{ $loop->iteration }}</td>
                                                    <td style="width: 240px;" valign="top">
                                                        {{ $start->modelable->name }}
                                                    </td>
                                                    <td style="width: 180px;" valign="top">
                                                        @foreach ($value->take(3) as $v)
                                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none">
                                                                {{ !is_null($v->received_at) ? strftime('%d %B %Y %R', strtotime($v->received_at)) : '-' }}
                                                            </span>
                                                        @endforeach
                                                        @if ($value->count() > 3)
                                                            <div class="small text-muted">
                                                                + {{ $value->count() - 3 }} lainnya
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td valign="top">
                                                        <span class="badge bg-soft-secondary text-dark fw-normal user-select-none">
                                                            {{ !is_null($end->returned_at) ? strftime('%d %B %Y %R', strtotime($end->returned_at)) : '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="pe-2 py-2" valign="top" nowrap>
                                                        {{ $end->giver->name }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Deskripsi</div>
                        <div class="fw-bold">{{ $borrow->meta->for ?? '-' }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Catatan</div>
                        <div>{!! $borrow->meta->clause ?? '-' !!}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Catatan dari user</div>
                        <div>{!! $borrow->meta->description ?? '-' !!}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Status Peminjaman</div>
                        <div class="fw-bold">{{ $borrow->status() }}</div>
                    </div>
                </div>
                <div class="card-header border-top d-none d-md-block border-0">
                    <div class="row">
                        <div class="col-md-6 small text-muted"> Penanggungjawab </div>
                        <div class="col-md-6 small text-muted"> Status </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    @foreach ($borrow->approvables as $approvable)
                        <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                            <div class="col-md-6">
                                <div class="text-muted small mb-1">
                                    {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                </div>
                                <strong>{{ $approvable->userable->empl_id ? $approvable->userable->getApproverLabel() : $approvable->userable->name }}</strong>
                            </div>
                            <div class="col-md-6">
                                @if ($approvable->userable->id == Auth::user()->id && !$borrow->trashed())
                                    <form class="form-block" action="{{ route('asset::inventories.lease.manages.update', ['approvable' => $approvable->id, 'next' => request('next', route('asset::inventories.lease.manages.index'))]) }}" method="post"> @csrf @method('PUT')
                                        <div class="mb-3">
                                            <select class="form-select @error('result') is-invalid @enderror" name="result">
                                                @foreach ($results as $result)
                                                    <option value="{{ $result->value }}" @selected($result->value == old('result', $approvable->result->value))>{{ $result->label() }}</option>
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
                                        <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('asset::inventories.lease.manages.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
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
            @if ($borrow->can('deleted'))
                <form method="POST" class="form-block form-confirm" enctype="multipart/form-data" action="{{ route('asset::inventories.lease.manages.destroy', ['manage' => $borrow->id, 'next' => request('next', route('asset::inventories.lease.manages.index'))]) }}"> @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start" style="cursor: pointer;">
                        <i class="mdi mdi-progress-upload me-3"></i>
                        <div>Batalkan peminjaman <br> <small class="text-muted">klik untuk menghapus pengajuan yang telah disetujui</small></div>
                    </button>
                </form>
            @endif
            @if ($borrow->can('canceled'))
                <form method="POST" class="form-block form-confirm" enctype="multipart/form-data" action="{{ route('asset::inventories.lease.manages.destroy', ['manage' => $borrow->id, 'next' => request('next', route('asset::inventories.lease.manages.index'))]) }}"> @csrf @method('DELETE')
                    <button class="btn btn-outline-warning w-100 text-warning d-flex align-items-center bg-white py-3 text-start" style="cursor: pointer;">
                        <i class="mdi mdi-progress-upload me-3"></i>
                        <div>Batalkan peminjaman <br> <small class="text-muted">klik untuk membatalkan pengajuan yang telah disetujui</small></div>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
