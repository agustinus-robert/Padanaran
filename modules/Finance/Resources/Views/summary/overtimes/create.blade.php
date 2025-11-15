@extends('finance::layouts.default')

@section('title', 'Rekapitulasi lembur | ')
@section('navtitle', 'Rekapitulasi lembur')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::summary.overtimes.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola lembur</h2>
            <div class="text-secondary">Menampilkan informasi kegitan lainnya karyawan.</div>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body">
            <i class="mdi mdi-format-list-bulleted"></i> Rekapitulasi lembur
        </div>
        <div class="card-body border-top border-light">
            <form class="form-block" action="{{ route('finance::summary.overtimes.store', ['next' => request('next', route('finance::summary.overtimes.index')), 'edit' => $recap?->id]) }}" method="post" enctype="multipart/form-data">@csrf
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
                    <label class="col-form-label required">Daftar lembur</label>
                    <div class="card mb-0">
                        <div class="table-responsive">
                            <table class="table-bordered mb-0 table">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kegiatan</th>
                                        <th>Aktivitas</th>
                                        <th style="min-width: 180px;">Tanggal/Waktu</th>
                                        <th class="text-center" nowrap style="min-width: 140px;">Durasi (jam)</th>
                                        <th class="pe-3 text-end" style="min-width: 140px;">Tarif</th>
                                        <th class="text-end" style="min-width: 100px;">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($overtimes as $i => $overtime)
                                        @php($recaped = in_array($overtime->id, array_column($recap?->result->overtimes ?? [], 'id')))
                                        @php($paid = !is_null($overtime->paid_off_at) && !$recaped)
                                        <tr @class([
                                            'bg-light' => $i % 2 == 1 && !$recaped,
                                            'table-success' => $recaped,
                                        ])>
                                            <td rowspan="{{ count($overtime->dates) + 1 }}" class="row-number border text-center" style="max-width: 5px;">{{ $loop->iteration }}</td>
                                            <td rowspan="{{ count($overtime->dates) + 1 }}" class="border" style="min-width: 180px;">
                                                <div class="fw-bold mb-1">{{ $overtime->name }}</div>
                                                <div class="text-muted small">{{ Str::words($overtime->description, 10) }}</div>
                                            </td>
                                            <td rowspan="{{ count($overtime->dates) + 1 }}">
                                                <div class="text-muted small"><i class="mdi mdi-calendar-outline"></i> {{ $overtime->created_at->isoFormat('lll') }}</div>
                                                @if ($overtime->approvables->count())
                                                    @php($approvable = $overtime->approvables->sortByDesc('updated_at')->first())
                                                    <div class="text-muted small"><i class="mdi mdi-check"></i> {{ $approvable->updated_at->isoFormat('lll') }} oleh {{ $approvable->userable->getApproverLabel() }}</div>
                                                @endif
                                            </td>
                                            <td colspan="5" class="p-0" style="border: none;">
                                                @if (!$paid)
                                                    <input type="hidden" name="overtimes[{{ $loop->index }}][id]" value="{{ $overtime->id }}">
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach ($overtime->dates as $date)
                                            @php($prc = $prices->where('start', '<=', $date['d'])->where('end', '>=', $date['d']))
                                            @php($p = $prc->first()['amount'] ?? 0)
                                            @php($price = $employee->getOvertimeSalaryViaActiveTemplate())
                                            <tr @class([
                                                'calc-row',
                                                'bg-light' => $i % 2 == 1 && !$recaped,
                                                'table-success' => $recaped,
                                            ])>
                                                <td class="border">
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
                                                            <input type="number" class="form-control calc-row-time text-center" name="overtimes[{{ $loop->parent->index }}][dates][{{ $loop->index }}][hour]" value="{{ $hour = round($date['minutes'] / 60, 2) }}" onkeyup="calculatePrice()">
                                                        </div>
                                                    </td>
                                                    <td class="align-middle" style="width: 160px;">
                                                        {{-- <input type="number" class="form-control calc-row-amount text-end" name="overtimes[{{ $loop->parent->index }}][dates][{{ $loop->index }}][amount]" value="{{ round($price, 2) }}" onkeyup="calculatePrice()"> --}}
                                                        <select class="form-control calc-row-amount" name="overtimes[{{ $loop->parent->index }}][dates][{{ $loop->index }}][amount]" onchange="calculatePrice()">
                                                            @foreach ($prices as $key => $value)
                                                                <option value="{{ $value['amount'] }}" @selected($p == $value['amount'])>{{ $value['amount'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="p-0 align-middle">
                                                        <input type="number" class="form-control calc-row-amount-total border-0 bg-transparent text-end shadow-none" name="overtimes[{{ $loop->parent->index }}][dates][{{ $loop->index }}][total]" value="{{ round($hour * $price, 2) }}" readonly>
                                                    </td>
                                                    <td class="border align-middle" width="1%"><button type="button" class="btn btn-soft-danger" onclick="deleteOvertime(this)"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td colspan="4">
                                            <div>Total</div>
                                            <div class="small text-muted"><cite>Terbilang: <span class="calc-row-total-inwords">nol</span> rupiah</cite></div>
                                        </td>
                                        <td colspan="2">
                                            <div class="input-group">
                                                <div class="input-group-text" data-bs-toggle="tooltip" title="Jam kerja"><i class="mdi mdi-timer-outline text-danger"></i></div>
                                                <input type="number" step="0.01" class="form-control total-time text-center" name="total_time">
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="mb-2">
                                                <input type="number" class="form-control fw-bold calc-row-total border-0 bg-transparent p-0 text-end" name="amount_total" value="0" readonly>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if (count($overtimes))
                    <div class="row">
                        <div class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10">
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="agreement" type="checkbox" name="validated" value="1" required>
                                <label class="form-check-label" for="agreement">Dengan ini saya selaku Keuangan (Finance) menyatakan data di atas adalah valid</label>
                            </div>
                            <div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::summary.overtimes.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
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
            [...document.querySelectorAll('.calc-row-amount-total')].forEach(el => {
                total += el.value = parseFloat(el.value)
            });

            let totaltime = [...document.querySelectorAll('.calc-row-time')].map(el => parseFloat(el.value || 0)).reduce((time, x) => time + x);

            document.querySelector('.calc-row-total').value = total;
            document.querySelector('.total-time').value = totaltime;
            document.querySelector('.calc-row-total-inwords').innerHTML = terbilang(Math.abs(total)).toLowerCase();
        }

        const deleteOvertime = (el) => {
            if (confirm('Apakah Anda yakin?')) {
                let tr = el.closest('tr');
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
