@extends('portal::layouts.default')

@section('title', 'Ajukan pinjaman | ')
@section('navtitle', 'Ajukan pinjaman')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::loan.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Tambah pinjaman baru</h2>
            <div class="text-secondary">Anda dapat mengajukan pinjaman karyawan dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('portal::loan.submission.store', ['next' => request('next')]) }}" method="POST" enctype="multipart/form-data"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Jenis pinjaman</label>
                            <div class="col-xl-8">
                                <div class="card @error('ctg_id') border-danger mb-1 @enderror">
                                    <div class="overflow-auto rounded" style="max-height: 300px;">
                                        @forelse($categories as $category)
                                            <label class="card-body border-secondary d-flex align-items-center @if (!$loop->last) border-bottom @endif py-2">
                                                <input class="form-check-input me-3" type="radio" value="{{ $category->id }}" name="ctg_id" data-has-interest="{{ $category->interest_id }}" data-interest="{{ $category->meta?->interest ?? '' }}" data-max="{{ $category->meta?->tenor ?? '' }}" data-divider="{{ $category->meta?->divider ?? '' }}" data-file="{{ $category->meta?->file ?? '' }}" required>
                                                <div>
                                                    <div class="fw-bold mb-0">{{ $category->name }}</div>
                                                    <div class="small text-muted">{{ $category->description ?? '-' }} </div>
                                                    <div class="small text-muted">Jangka waktu: {{ $category->meta?->tenor . ' Bulan' ?? '-' }}</div>
                                                </div>
                                            </label>
                                        @empty
                                            <div class="card-body text-muted">Tidak ada kategori inventaris</div>
                                        @endforelse
                                    </div>
                                </div>
                                @error('ctg_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Deskripsi</label>
                            <div class="col-xl-8">
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Silakan tulis keterangan/alasan/catatan di sini ...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-4">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nominal</label>
                            <div class="col-xl-8 col-xxl-4">
                                <div class="input-group">
                                    <div class="input-group-text">Rp</div>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" oninput="validateAmount(event); calculateSimulationTable()" required />
                                </div>
                                @error('amount')
                                    <small class="text-danger d-block"> {{ $errors->first('amount') }} </small>
                                @enderror
                            </div>
                            <div class="offset-lg-3 offset-md-0 col-md-8 small max_loan text-muted"></div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-lg-4 col-xl-3 col-form-label">Bunga</label>
                            <div class="col-xl-4 col-xxl-2">
                                <div class="input-group">
                                    <div class="input-group-text">%</div>
                                    <input class="form-check-input me-1 mt-0" type="hidden" id="has_interest" value="1" name="has_interest" readonly>
                                    <input type="number" class="form-control @error('interest_value') is-invalid @enderror" step="0.1" name="interest_value" value="{{ old('interest_value') }}" oninput="calculateSimulationTable()" readonly />
                                </div>
                                @if ($errors->has('has_interest', 'interest_value'))
                                    <small class="text-danger d-block"> {{ $errors->first('has_interest') ?: $errors->first('interest_value') }} </small>
                                @endif
                            </div>
                        </div>
                        <div class="row required mb-4">
                            <label class="col-lg-4 col-xl-3 col-form-label">Diajukan pada</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="datetime-local" class="form-control @error('submission_at') is-invalid @enderror" name="submission_at" value="{{ old('submission_at', date('Y-m-d\TH:i')) }}" onchange="calculateStartDate(event)" required />
                                @error('submission_at')
                                    <small class="text-danger d-block"> {{ $errors->first('submission_at') }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-4">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tgl penagihan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="date" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ old('start_at', $start_at) }}" onchange="calculateSimulationTable()" required readonly />
                                @error('start_at')
                                    <small class="text-danger d-block"> {{ $errors->first('start_at') }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-4">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tenor</label>
                            <div class="col-xl-4 col-xxl-4">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('tenor') is-invalid @enderror" name="tenor" onkeyup="validateTenor(event);calculateStartDate(event);calculateSimulationTable();" required />
                                    <select class="form-select @error('tenor_by') is-invalid @enderror w-auto" name="tenor_by" style="max-width: 140px;" onchange="calculateStartDate(event);calculateSimulationTable();" required>
                                        @foreach ($tenor_types as $type)
                                            <option value="{{ $type->value }}" data-operator="{{ $type->operator() }}" @selected($type->value == old('tenor_by', 3))>{{ strtolower($type->label()) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('tenor', 'tenor_by'))
                                    <small class="text-danger d-block"> {{ $errors->first('tenor') ?: $errors->first('tenor_by') }} </small>
                                @endif
                            </div>
                        </div>
                        <div class="row form-file d-none mb-4">
                            <label class="col-lg-4 col-xl-3 col-form-label">Lampiran</label>
                            <div class="col-md-8">
                                <input class="form-control @error('file') is-invalid @enderror" name="file" type="file" id="upload-input" accept="application/pdf">
                                <small class="text-muted">Berkas berupa *.pdf maksimal berukuran 2mb, silakan unduh template <a href="{{ asset('docs/template/Pernyataan Kesanggupan Pembayaran Pinjaman.docx') }}"><i class="mdi mdi-download"></i> di sini</a></small>
                                @error('file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-8 offset-xl-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="agreement" type="checkbox" required>
                                    <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                                </div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::service.loans.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-table"></i> Tabel simulasi angsuran
                </div>
                <div class="card-body border-top blockui p-2" id="simulation-table" style="overflow-y: auto; max-height:480px;">
                    <table class="borderless table table">
                        <thead class="text-muted">
                            <tr>
                                <th class="fw-bold">#</th>
                                <th class="fw-bold">Tanggal</th>
                                <th class="fw-bold">Nominal</th>
                                <th class="fw-bold">Bunga</th>
                            </tr>
                        </thead>
                        <tbody id="simulation-data" class="d-none">
                        </tbody>
                        <tbody id="simulation-notfound">
                            <tr>
                                <td colspan="4" class="py-4 text-center">
                                    <div class="d-flex justify-content-center mb-4">
                                        <div style="background: url('{{ asset('img/manypixels/Question_Flatline.svg') }}') center center no-repeat; background-size: contain; min-height: 180px; width: 100%;"></div>
                                    </div>
                                    <div>Silakan isi beberapa data mandatori terlebih dahulu.</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
            text-align: right;
            padding-right: 20px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script>
        let start_at = {!! json_encode($start_at) !!};
        let main_salary = {!! json_encode($main_salary) !!};

        const calculateStartDate = (e = false) => {
            if (submission_at = document.querySelector('[name="submission_at"]').value) {
                if (operator = document.querySelector('[name="tenor_by"] :checked').dataset.operator) {
                    document.querySelector('[name="start_at"]').value = start_at
                }
            }
        }

        const rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }

        const toggleInterestInput = (e) => {
            let divider = e.target.dataset.divider ?? 1;
            let interest = e.target.dataset.interest ?? 0;
            let maxtenor = e.target.dataset.max ?? 0;
            let maxloan = maxtenor * (main_salary / divider) ?? 0;
            document.querySelector('[name="tenor"]').value = '';
            if (e.target.dataset.hasInterest) {
                document.querySelector('[name="has_interest"]').value = e.target.dataset.hasInterest;
                document.querySelector('[name="interest_value"]').removeAttribute('disabled');
                document.querySelector('[name="interest_value"]').value = interest;
                document.querySelector('[name="amount"]').value = '';
            } else {
                document.querySelector('[name="amount"]').value = '';
                document.querySelector('[name="has_interest"]').value = '';
                document.querySelector('[name="interest_value"]').value = '';
                document.querySelector('[name="interest_value"]').setAttribute('disabled', 'true');
            }
            if (maxtenor > 0) {
                document.querySelector('[name="tenor"]').setAttribute("max", maxtenor);
                document.querySelector('[name="amount"]').setAttribute("max", Math.round(maxloan));
                document.querySelector('.max_loan').innerHTML = 'Jumlah maksimal pinjaman kamu untuk kategori ini ' + rupiah(Math.round(maxloan)) + ' (<cite>' + terbilang(Math.round(maxloan)).toLowerCase() + ' rupiah</cite>)';
            };
            document.querySelector('.form-file').classList.toggle('d-none', !e.target.dataset.file);
            document.querySelector('[name="file"]').toggleAttribute('required', e.target.dataset.file);
        }

        const validateTenor = (e) => {
            let value = parseInt(e.target.value);
            let max = parseInt(e.target.getAttribute("max"));
            document.querySelector('[name="tenor"]').value = value >= max ? max : value;
        }

        const validateAmount = (e) => {
            let value = parseInt(e.target.value);
            let max = parseInt(e.target.getAttribute("max"));
            document.querySelector('[name="amount"]').value = value >= max ? max : value;
        }

        const debounce = (callback) => {
            let t;
            return (a) => {
                clearTimeout(t);
                t = setTimeout(() => callback(a), 600);
            };
        };

        const calculateSimulationTable = debounce(() => {
            const div = document.getElementById('simulation-table');
            let amount = parseFloat(document.querySelector('[name="amount"]').value);
            let tenor = parseFloat(document.querySelector('[name="tenor"]').value);
            let interest = parseFloat(document.querySelector('[name="interest_value"]').value);
            let operator = document.querySelector('[name="tenor_by"] :checked').dataset.operator;
            let start_at = moment(document.querySelector('[name="start_at"]').value);
            UIBlock.block(div);
            let tr = '';
            if (amount && tenor && operator && start_at) {
                for (let i = 1; i <= tenor; i++) {
                    tr += `<tr>`;
                    tr += `<td>${i}</td>`;
                    tr += `<td>${start_at.add((i == 1 ? 0 : 1), operator).format('LL')}</td>`;
                    tr += `<td>${(parseInt(amount/tenor)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>`;
                    tr += `<td>${(parseInt(amount*interest/100) || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>`;
                    tr += `</tr>`;
                }
            }
            document.getElementById('simulation-data').innerHTML = tr;
            document.getElementById('simulation-data').classList.toggle('d-none', !tr.length);
            document.getElementById('simulation-notfound').classList.toggle('d-none', tr.length);
            UIBlock.unblock();
        })

        document.addEventListener("DOMContentLoaded", () => {
            [].slice.call(document.querySelectorAll('[name="ctg_id"]')).map((e) => {
                e.addEventListener('click', toggleInterestInput);
            });
            calculateStartDate();
        });
    </script>
@endpush
