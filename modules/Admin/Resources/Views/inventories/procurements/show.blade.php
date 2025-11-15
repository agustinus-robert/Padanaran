@extends('asset::layouts.default')

@section('title', 'Kelola pengajuan | ')

@section('navtitle', 'Kelola pengajuan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.proposals.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola pengajuan</h2>
            <div class="text-muted">Kamu dapat menyetujui/menolak/merevisi pengajuan proposal yang diajukan karyawan!</div>
        </div>
    </div>
    @if ($proposal->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail pengajuan</div>
                    @if (!$proposal->trashed())
                        <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="javascript:;" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
                    @endif
                </div>
                <div class="card-body border-top">
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">Tanggal pengajuan</div>
                            <div class="fw-bold"> {{ $proposal->created_at->formatLocalized('%A, %d %B %Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Judul pengajuan</div>
                            <div class="fw-bold"> {{ $proposal->name }}</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Daftar barang</div>
                        <div>
                            <div style="display: none">
                                {{ $total = 0 }}
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th>Nama barang</th>
                                            <th width="15%" class="text-center">Jumlah</th>
                                            <th width="25%" class="text-center">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($proposal->items as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">Rp {{ Str::money($item->bought_price) }}</td>
                                            </tr>
                                            @php
                                                $total += $item->bought_price;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td class="text-start" colspan="2">Total pengajuan</td>
                                            <td class="text-end">Rp {{ Str::money($total) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Deskripsi/catatan</div>
                        <div class="fw-bold">{{ $proposal->description ?: '-' }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Status</div>
                        <div></div>
                    </div>
                </div>
                <div class="card-header border-top d-none d-md-block border-0">
                    <div class="row">
                        <div class="col-md-6 small text-muted"> Penanggungjawab </div>
                        <div class="col-md-6 small text-muted"> Status </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    @foreach ($proposal->approvables as $approvable)
                        <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                            <div class="col-md-6">
                                <div class="text-muted small mb-1">
                                    {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                </div>
                                <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                            </div>
                            <div class="col-md-6">
                                @if ($approvable->userable->is($employee->position) && !$proposal->trashed())
                                    <form class="form-block" action="{{ route('asset::inventories.controls.approval.update', ['approvable' => $approvable->id, 'next' => request('next', route('asset::inventories.proposals.index'))]) }}" method="post"> @csrf @method('PUT')
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
                                        <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('portal::leave.manage.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
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
                    'Karyawan' => $proposal->user->name,
                    'Posisi' => $proposal->user->employee->position->position->name ?: '-',
                    'Departemen' => $proposal->user->employee->position->position->department->name ?: '-',
                    'Tanggal pengajuan' => $proposal->created_at->formatLocalized('%A, %d %B %Y'),
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
        </div>
    </div>
@endsection
