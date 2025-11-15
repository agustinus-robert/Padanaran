@extends('finance::layouts.default')

@section('title', 'Daftar transaksi | ')

@section('navtitle', 'Daftar transaksi')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::service.loans.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Daftar pinjaman</h2>
            <div class="text-secondary">Menampilkan informasi pinjaman karyawan, tagihan dan transaksi.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar transaksi pembayaran
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row gy-2 gx-2" action="{{ route('finance::service.loan-transaction-lists.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('finance::service.loans.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th></th>
                                    <th>Nama</th>
                                    <th>Pinjaman</th>
                                    <th>Pembayaran</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr @if ($transaction->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $transactions->firstItem() - 1 }}</td>
                                        <td width="10">
                                            <div class="rounded-circle" style="background: url('{{ $transaction->installment->loan->employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                        </td>
                                        <td nowrap>
                                            <strong>{{ $transaction->installment->loan->employee->user->name }}</strong> <br>
                                        </td>
                                        <td>
                                            <div class="text-muted"><small>{{ $transaction->installment->loan->category->name }}</small></div>
                                            Rp {{ Str::money($transaction->installment->loan->amount_total) }}
                                        </td>
                                        <td> Rp {{ Str::money($transaction->amount) }}</td>
                                        <td>{{ $transaction->paid_at->formatLocalized('%A, %d %B %Y') }}</td>
                                        <td>{{ $transaction->installment->status }}</td>
                                        <td class="text-end py-2" nowrap>
                                            @if ($transaction->trashed())
                                                <form class="form-block form-confirm d-inline" action="{{ route('finance::service.loan-transaction-lists.restore', ['transaction' => $transaction->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                    <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                </form>
                                            @else
                                                <form class="form-block form-confirm d-inline" action="{{ route('finance::service.loan-transaction-lists.destroy', ['transaction' => $transaction->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                    <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            @include('components.notfound')
                                            @if (!request('trash'))
                                                @can('store', Modules\HRMS\Models\EmployeeLoan::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('finance::service.loans.index', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah pinjaman baru</a>
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
                        {{ $transactions->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xl-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $transactions->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah transaksi</div>
                </div>
                <div><i class="mdi mdi-account-cash-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('finance::service.loan-transaction-lists.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat transaksi yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
