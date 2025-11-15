@extends('finance::layouts.default')

@section('title', 'Rekapitulasi kegiatan lainnya | ')
@section('navtitle', 'Rekapitulasi kegiatan lainnya')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::summary.outworks.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola kegiatan lainnya</h2>
            <div class="text-secondary">Menampilkan informasi kegitan lainnya karyawan.</div>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body">
            <i class="mdi mdi-format-list-bulleted"></i> Rekapitulasi kegiatan lainnya
        </div>
        <div class="card-body border-top border-light">
            <form class="form-block" action="{{ route('finance::summary.outworks.store', ['next' => request('next', route('finance::summary.outworks.index')), 'edit' => $recap?->id]) }}" method="post" enctype="multipart/form-data">@csrf
                <div class="row align-items-center mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Nama karyawan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6 fw-bold">{{ $employee->user->name }}</div>
                    <input class="d-none" type="number" name="employee" value="{{ $employee->id }}" readonly>
                </div>
                <div class="row align-items-center mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Jabatan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6 fw-bold">{{ $employee->position->position->name }}</div>
                </div>
                <div class="row align-items-center mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Periode</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6 fw-bold">
                        <div class="align-items-center d-flex">
                            @if (!$start_at->isSameDay($end_at))
                                <div>{{ $start_at->isoFormat('LL') }}</div>
                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                            @endif
                            <div>{{ $end_at->isoFormat('LL') }}</div>
                            <input class="d-none" type="date" name="start_at" value="{{ $start_at->format('Y-m-d') }}">
                            <input class="d-none" type="date" name="end_at" value="{{ $end_at->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-form-label required">Daftar kegiatan</label>
                    <div class="card mb-0">
                        <div class="table-responsive">
                            <table class="table-bordered mb-0 table">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kegiatan</th>
                                        <th>Deskripsi</th>
                                        <th style="min-width: 180px;">Tanggal/Waktu</th>
                                        <th class="text-center" nowrap style="min-width: 140px;">Durasi (jam)</th>
                                        <th class="pe-3 text-end" style="min-width: 140px;">Tarif</th>
                                        <th class="text-end" style="min-width: 100px;">Subtotal</th>
                                        <th class="text-end" style="min-width: 100px;">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outworks as $i => $outwork)
                                        @php($recaped = in_array($outwork->id, array_column($recap?->result->outworks ?? [], 'id')))
                                        @php($paid = !is_null($outwork->paid_off_at) && !$recaped)
                                        <tr @class([
                                            'bg-light' => $i % 2 == 1 && !$recaped,
                                            'table-success' => $recaped,
                                        ])>
                                            <td rowspan="{{ count($outwork->dates) * 2 + 1 }}" class="row-number border text-center" style="max-width: 5px;">{{ $loop->iteration }}</td>
                                            <td rowspan="{{ count($outwork->dates) * 2 + 1 }}" class="border" style="min-width: 180px;">
                                                <div class="fw-bold">{{ $outwork->name }}</div>
                                                <div>
                                                    {{ $outwork->category->name }}
                                                    @isset($outwork->category->description)
                                                        - {{ $outwork->category->description }}
                                                    @endisset
                                                </div>
                                            </td>
                                            <td rowspan="{{ count($outwork->dates) * 2 + 1 }}">
                                                <div class="text-muted small">{{ Str::words($outwork->description, 10) }}</div>
                                                <div class="text-muted small"><i class="mdi mdi-calendar-outline"></i> {{ $outwork->created_at->isoFormat('lll') }}</div>
                                                @if ($outwork->approvables->count())
                                                    @php($approvable = $outwork->approvables->sortByDesc('updated_at')->first())
                                                    <div class="text-muted small"><i class="mdi mdi-check"></i> {{ $approvable->updated_at->isoFormat('lll') }} oleh {{ $approvable->userable->getApproverLabel() }}</div>
                                                @endif
                                            </td>
                                            <td colspan="5" class="p-0" style="border: none;">
                                                @if (!$paid)
                                                    <input type="hidden" name="outworks[{{ $loop->index }}][id]" value="{{ $outwork->id }}">
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach ($outwork->dates as $date)
                                            @php($price1 = isset($date['p']) && $date['p'] ? $employee->getOvertimeSalary() : $outwork->category->meta->in_working_hours_price ?? 0)
                                            @php($price2 = isset($date['p']) && $date['p'] ? $employee->getOvertimeSalary() : $outwork->category->price ?? 0)
                                            @php($prep = isset($outwork->category->meta->prepareable) && empty($date['p']))
                                            <tr @if ($prep) data-fixed="true" @endif @class([
                                                'calc-row',
                                                'bg-light' => $i % 2 == 1 && !$recaped,
                                                'table-success' => $recaped,
                                            ])>
                                                <td class="border" rowspan="2">
                                                    <div>
                                                        <i class="mdi mdi-calendar-outline"></i> {{ Carbon::parse($date['s'])->isoFormat('LL') }}
                                                    </div>
                                                    <div class="text-muted">
                                                        <i class="mdi mdi-clock-outline"></i> {{ $date['t_s'] . '-' . $date['t_e'] }}
                                                    </div>
                                                </td>
                                                @if ($paid)
                                                    <td colspan="6" class="">Sudah dibayar</td>
                                                @else
                                                    <td nowrap class="align-middle" style="width: 140px;">
                                                        <div class="input-group">
                                                            <div class="input-group-text" data-bs-toggle="tooltip" title="Jam kerja"><i class="mdi mdi-timer-outline text-danger"></i></div>
                                                            <input type="number" class="form-control calc-row-time text-center" name="outworks[{{ $loop->parent->index }}][dates][{{ $loop->index }}][inside][hour]" value="{{ round($date['inside'] / 60, 2) }}" onkeyup="calculatePrice()">
                                                        </div>
                                                    </td>
                                                    <td class="align-middle" style="width: 160px;">
                                                        <input type="number" class="form-control calc-row-amount text-end" name="outworks[{{ $loop->parent->index }}][dates][{{ $loop->index }}][inside][amount]" value="{{ round($price1, 2) }}" onkeyup="calculatePrice()">
                                                    </td>
                                                    <td class="p-0 align-middle">
                                                        <input type="number" class="form-control calc-row-amount-total border-0 bg-transparent text-end shadow-none" name="outworks[{{ $loop->parent->index }}][dates][{{ $loop->index }}][inside][total]" value="{{ round($date['inside'], 2) * $price1 }}" readonly>
                                                    </td>
                                                    <td rowspan="2" class="p-0 align-middle" style="width: 160px;">
                                                        <input type="number" class="form-control fw-bold calc-row-subtotal border-0 bg-transparent text-end shadow-none" name="outworks[{{ $loop->parent->index }}][dates][{{ $loop->index }}][total]" value="{{ round($date['inside'] / 60, 2) * $price1 + round($date['outside'] / 60, 2) * $price2 }}" readonly>
                                                    </td>
                                                    <td rowspan="2" class="border align-middle"><button type="button" class="btn btn-soft-danger" onclick="deleteOutwork(this)"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                @endif
                                            </tr>
                                            <tr @if ($prep) data-fixed="true" @endif @class([
                                                'calc-row',
                                                'bg-light' => $i % 2 == 1 && !$recaped,
                                                'table-success' => $recaped,
                                            ])>
                                                @if ($paid)
                                                    <td colspan="5" class="p-0"></td>
                                                @else
                                                    <td class="align-middle" style="width: 140px;">
                                                        <div class="input-group">
                                                            <div class="input-group-text" data-bs-toggle="tooltip" title="Luar jam kerja"><i class="mdi mdi-timer-off-outline text-primary"></i></div>
                                                            <input type="number" class="form-control calc-row-time text-center" name="outworks[{{ $loop->parent->index }}][dates][{{ $loop->index }}][outside][hour]" value="{{ round($date['outside'] / 60, 2) }}" onkeyup="calculatePrice()">
                                                        </div>
                                                    </td>
                                                    <td class="align-middle" style="width: 160px;">
                                                        <input type="number" class="form-control calc-row-amount text-end" name="outworks[{{ $loop->parent->index }}][dates][{{ $loop->index }}][outside][amount]" value="{{ round($price2, 2) }}" onkeyup="calculatePrice()">
                                                    </td>
                                                    <td class="p-0 align-middle" style="width: 160px;">
                                                        <input type="number" class="form-control calc-row-amount-total border-0 bg-transparent text-end shadow-none" name="outworks[{{ $loop->parent->index }}][dates][{{ $loop->index }}][outside][total]" value="{{ round($date['outside'] / 60, 2) * $price2 }}" readonly>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td colspan="7">
                                            <div>Total</div>
                                            <div class="small text-muted"><cite>Terbilang: <span class="calc-row-total-inwords">nol</span> rupiah</cite></div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="mb-2">
                                                <input type="number" class="form-control fw-bold calc-row-total border-0 bg-transparent p-0 text-end" name="amount_total" value="0" readonly>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if (count($outworks))
                    <div class="row">
                        <div class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10">
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="agreement" type="checkbox" name="validated" value="1" required>
                                <label class="form-check-label" for="agreement">Dengan ini saya selaku Keuangan (Finance) menyatakan data di atas adalah valid</label>
                            </div>
                            <div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::summary.outworks.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const calculatePrice = () => {
            let total = 0,
                amountTotal;
            [...document.querySelectorAll('.calc-row')].forEach(row => {
                let time_el = row.querySelector('.calc-row-time');
                if (time_el) {
                    let time = parseFloat(time_el.value || 0);
                    let amount = parseFloat(row.querySelector('.calc-row-amount').value || 0);
                    row.querySelector('.calc-row-amount-total').value = Math.round(row.dataset.fixed ? amount : (time * amount))
                }
            });
            [...document.querySelectorAll('.calc-row-subtotal')].forEach(el => {
                total += el.value = parseFloat(el.closest('tr').querySelector('.calc-row-amount-total').value) + parseFloat(el.closest('tr').nextElementSibling.querySelector('.calc-row-amount-total').value)
            });
            document.querySelector('.calc-row-total').value = total;
            document.querySelector('.calc-row-total-inwords').innerHTML = terbilang(Math.abs(total)).toLowerCase();
        }

        const deleteOutwork = (el) => {
            if (confirm('Apakah Anda yakin?')) {
                let tr = el.closest('tr');
                tr.previousElementSibling.remove();
                tr.nextElementSibling.remove();
                tr.remove();
                [...document.querySelectorAll('.row-number')].forEach((el, i) => el.innerHTML = i + 1)
                calculatePrice();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            calculatePrice();
        })
    </script>
@endpush
