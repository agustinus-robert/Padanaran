@extends('finance::layouts.default')

@section('title', 'Tambah PPh 21 | ')
@section('navtitle', 'Tambah PPh 21')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-10 col-xl-12">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('finance::tax.income-taxs.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah PPh 21</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk menambah PPh 21</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Form PPh 21
                </div>
                <div class="card-body mb-4">
                    <form class="form-block" action="{{ route('finance::tax.income-taxs.store', ['empl_id' => $employee->id, 'next' => request('next', route('finance::tax.income-taxs.index'))]) }}" method="POST" enctype="multipart/form-data"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-2 col-xl-2 col-form-label">Nama karyawan</label>
                            <div class="col-xl-8 col-xxl-8">
                                <input class="form-control text-muted" value="{{ $employee->user->name }}">
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-2 col-xl-2 col-form-label">Tipe</label>
                            <div class="col-xl-8 col-xxl-8">
                                <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->value }}" @selected($type == Modules\HRMS\Enums\TaxTypeEnum::YEARLY)>{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-2 col-xl-2 col-form-label">Periode</label>
                            <div class="col-xl-12 col-xxl-8">
                                <div class="input-group form-calculate mb-2">
                                    <input type="datetime-local" class="form-control" name="start_at" value="{{ old('start_at', $start_at) }}">
                                    <div class="input-group-text">s.d.</div>
                                    <input type="datetime-local" class="form-control" name="end_at" value="{{ old('end_at', $end_at) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-xl-2 col-form-label required">Penghitungan PPh </label>
                            <div class="col-lg-9 col-xl-10 col-xxl-10">
                                <div class="card mb-0">
                                    @include('finance::tax.pph.components.pph')
                                </div>
                            </div>
                            @error('components')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-3 col-xl-2 col-form-label">Pajak penghasilan (PPh21)</label>
                            <div class="col-lg-8 col-xl-7 col-xxl-6">
                                <div class="card card-body mb-0">
                                    <input class="d-none" type="number" name="pphtotal" value="0">
                                    <h4>Rp <span class="pph-input"></span></h4>
                                    <div class="small text-muted"><cite>Terbilang: <span class="pph-inwords"></span> rupiah</cite></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10">
                                <div class="card card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" id="recap" type="checkbox" name="as_recap" value="1">
                                        <label class="form-check-label" for="recap">Masukan kedalam rekap penggajian sesuai periode terpilih.</label>
                                    </div>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="agreement" type="checkbox" name="validated" value="1" required>
                                    <label class="form-check-label" for="agreement">Dengan ini saya selaku Keuangan (Finance) menyatakan data di atas adalah valid.</label>
                                </div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::tax.income-taxs.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
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
        }
    </style>
@endpush

@push('scripts')
    <script>
        const calculatePph = (e) => {
            const ters = @json($ters);
            let bruto = parseFloat(e?.target?.value) || parseFloat(document.querySelector('.calc-bruto-month-subtotal-input')?.value) || 0;
            document.querySelector('.bruto-month-inword').innerHTML = terbilang(Math.floor(bruto));

            let result = ters.rate.find(
                entry =>
                bruto >= parseFloat(entry.lower) &&
                (entry.upper === null || bruto < parseFloat(entry.upper))
            );

            console.log(result)
            let pph = (parseFloat(result.percentage) * parseFloat(bruto)) / 100;

            document.querySelector('.calc-ptkp-category-input').value = ters.status;
            document.querySelector('.calc-ter-category-input').value = ters.ter;
            document.querySelector('.calc-ter-value-input').value = result.percentage;
            document.querySelector('.calc-ter-value-inword').innerHTML = terbilang(Math.floor(result.percentage)).toLowerCase();

            const calcTerAmountInput = document.querySelector('.calc-ter-amount-input');
            calcTerAmountInput.value = Math.floor(pph);

            @if (true)
                calcTerAmountInput.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
                calcTerAmountInput.dispatchEvent(new KeyboardEvent('keyup', {
                    bubbles: true
                }));
            @endif
        }

        const generatePph = (e) => {
            let amount = parseFloat(e?.target?.value) || 0;
            document.querySelector('[name="pphtotal"]').value = Math.floor(amount);
            document.querySelector('.calc-ter-amount-inword').innerHTML = terbilang(Math.floor(amount)).toLowerCase();
            document.querySelector('.pph-input').innerHTML = Math.floor(amount);
            document.querySelector('.pph-inwords').innerHTML = terbilang(Math.floor(amount)).toLowerCase();
        }

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const brutoInput = document.querySelector('.calc-bruto-month-subtotal-input');
                if (brutoInput) {
                    calculatePph({
                        target: brutoInput
                    });
                } else {
                    console.warn("Bruto input element not found.");
                }
            }, 100)
            // updateInword();
        })
    </script>
@endpush
