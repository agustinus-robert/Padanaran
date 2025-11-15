@extends('finance::layouts.default')

@section('title', 'Kelola pinjaman karyawan | ')

@section('navtitle', 'Kelola pinjaman karyawan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::service.loans.show', ['loan' => $loan->id])) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Buat transaksi</h2>
            <div class="text-secondary">Menampilkan informasi pinjaman karyawan, tagihan dan transaksi.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-cash"></i> Buat transaksi</div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('finance::service.loans.transactions.store', ['installment' => $number, 'next' => route('finance::service.loans.show', ['loan' => $loan->id])]) }}" method="POST" enctype="multipart/form-data">@csrf
                        <div class="required mb-3">
                            <label class="form-label">Diterima dari</label>
                            <input type="hidden" class="form-control @error('payer_id') is-invalid @enderror" name="payer_id" value="{{ old('payer_id', $loan->employee->id) }}" required />
                            <input class="form-control @error('payer_name') is-invalid @enderror" name="payer_name" value="{{ old('payer_name', $loan->employee->user->name) }}" required />
                            @error('payer_id')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <label class="form-label">Diterima oleh</label>
                            <input type="hidden" class="form-control @error('recipient_id') is-invalid @enderror" name="recipient_id" value="{{ old('recipient_id', $user->employee->id) }}" required />
                            <input class="form-control @error('recipient_name') is-invalid @enderror" name="recipient_name" value="{{ old('recipient_name', $user->name) }}" required />
                            @error('recipient_id')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <label class="form-label">Metode pembayaran</label>
                            @foreach ($methods as $method)
                                <div class="form-check d-flex align-items-center @if (!$loop->last) mb-2 @endif">
                                    <input class="form-check-input me-2" type="radio" name="method" id="method{{ $method->value }}" data-default-paid-at="{{ $method->defaultPaidAt() }}" value="{{ $method->value }}" onchange="setPaidAtValue(event.currentTarget)">
                                    <label class="form-check-label" for="method{{ $method->value }}">
                                        <div>{{ $method->label() }}</div>
                                        <div class="mt-n1 text-muted small">{{ $method->description() }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="row mb-3">
                            <div class="col-7">
                                <div class="required">
                                    <label class="form-label">Diterima pada</label>
                                    <input type="datetime-local" class="form-control @error('paid_at') is-invalid @enderror" name="paid_at" value="{{ old('paid_at', date('Y-m-d\TH:i:s')) }}" step="1" required />
                                    @error('paid_at')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="required">
                                    <label class="form-label">Jumlah</label>
                                    <div class="input-group">
                                        <div class="input-group-text">Rp</div>
                                        <input class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $installment->amount) }}" required />
                                    </div>
                                    @error('name')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="required mb-3">
                            <label class="form-label">Alat pembayaran</label>
                            @foreach ($cashs as $cash)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_cash" id="cash{{ $cash->value }}" value="{{ $cash->value }}">
                                    <label class="form-check-label" for="cash{{ $cash->value }}">
                                        {{ $cash->label() }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="row mt-4 mb-3">
                            <div class="col-lg-9">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="agreement" type="checkbox" required>
                                    <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                                </div>
                                <button id="form-add-insurances-submit" class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::service.loans.show', ['loan' => $loan->id])) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            @php
                $list = [
                    'karyawan' => $loan->employee->user->name,
                    'Tanggal pengajuan' => $loan->submission_at->formatLocalized('%A, %d %B %Y'),
                    'Kategori pinjaman' => $loan->category->name,
                    'Nominal pinjaman' => 'Rp ' . Str::money($loan->amount_total),
                    'Tenor' => implode(' ', [$loan->tenor, strtolower($loan->tenor_by->label())]),
                    'Catatan' => $loan->description ?: '-',
                    'Status' => $loan->paid_at ? 'Lunas' : 'Belum lunas',
                ];
            @endphp
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <div><i class="mdi mdi-information-outline"></i> Detail pinjaman</div>
                </div>
                <div class="list-group list-group-flush mt-n2">
                    @foreach ($list as $key => $value)
                        <div class="list-group-item @if ($loop->last) mb-0 @endif">
                            <div class="text-muted">{{ $key }}</div>
                            <strong>{{ $value }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body">
                    <div><i class="mdi mdi-clipboard-list-outline"></i> Riwayat transaksi</div>
                </div>
                <div class="list-group list-group-flush mt-n2" style="overflow-y: auto; max-height:360px;">
                    @forelse($loan->transactions as $transaction)
                        <div class="list-group-item @if ($loop->last) mb-0 @endif">
                            <div class="row gy-2">
                                <div class="col-sm-8">
                                    <div>Angsuran ke {{ $loop->iteration }}</div>
                                    <strong>Rp {{ Str::money($transaction->amount) }}</strong>
                                </div>
                                <div class="col-sm-4 text-sm-end">
                                    <div class="text-muted">{{ $transaction->paid_at->formatLocalized('%A, %d %B %Y') }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item">
                            Belum ada pembayaran
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const setPaidAtValue = (el) => {
            document.querySelector('[name="paid_at"]').value = el.dataset.defaultPaidAt || (new Date()).now()
        }
    </script>
@endpush
