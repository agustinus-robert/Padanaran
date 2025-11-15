@extends('finance::layouts.default')

@section('title', 'Penerbitan gaji | ')
@section('navtitle', 'Penerbitan gaji')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::payroll.validations.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Penerbitan gaji</h2>
            <div class="text-secondary">Formulir dibawah untuk divalidasi oleh Departemen Keuangan</div>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body">
            <i class="mdi mdi-file-plus-outline"></i> Formulir penghitungan gaji
        </div>
        <div class="card-body border-top border-light">
            <form class="form-block form-confirm" action="{{ route('finance::payroll.validations.update', ['salary' => $salary->id, 'next' => request('next', route('finance::payroll.validations.index'))]) }}" method="POST"> @csrf @method('PUT')
                <div class="row align-items-center mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Nama karyawan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6 fw-bold">{{ $employee->user->name }}</div>
                </div>
                <div class="row align-items-center mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Jabatan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6 fw-bold">{{ $employee->position->position->name }}</div>
                </div>
                <div class="row align-items-center mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Periode</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6 fw-bold">
                        <div class="align-items-center d-flex">
                            @if (!$salary->start_at->isSameDay($salary->end_at))
                                <div>{{ $salary->start_at->isoFormat('LL') }}</div>
                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                            @endif
                            <div>{{ $salary->end_at->isoFormat('LL') }}</div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Penggajian</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <div class="fw-bold mb-0">{{ $salary->name }}</div>
                        <div class="small text-muted">Dibuat {{ $salary->created_at->diffForHumans() }} dengan total {{ count($salary->components->pluck('ctgs.*.i.*')->flatten(1)) }} komponen</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label required">Komponen</label>
                    <div class="col-lg-9 col-xl-10 col-xxl-10">
                        @foreach ($salary->components as $slip)
                            @php($sliptotal[$loop->index] = 0)
                            <div class="card @if ($loop->last) mb-0 @endif">
                                <div class="card-header border-bottom-0 fw-bold">{{ $slip['slip'] }}</div>
                                <div class="card-body">
                                    <div class="row gy-4">
                                        @foreach ($slip['ctgs'] as $category)
                                            <div class="col-xl-4">
                                                <div class="fw-bold mb-2">{{ $loop->iteration . '. ' . $category['ctg'] }}</div>
                                                <table class="table-sm mb-0 table">
                                                    <tbody>
                                                        @foreach ($category['i'] as $item)
                                                            @php($unit = $units[$item['u'] - 1])
                                                            <tr>
                                                                <td class="ps-0">{{ $item['name'] }}</td>
                                                                <td style="width: 40px;">{{ $unit->prefix() }}</td>
                                                                <td class="font-monospace text-end">{{ number_format($item['amount'] * $item['n'], 0, ',', '.') }}</td>
                                                                <td class="pe-0" style="width: 40px;">{{ $unit->suffix() }}</td>
                                                            </tr>
                                                        @endforeach
                                                        @php($ctgtotal = array_sum(array_map(fn($item) => $item['amount'] * $item['n'], $category['i'])))
                                                        @if (!$unit->disabledState())
                                                            <tr>
                                                                <td class="fw-bold ps-0">Jumlah {{ $category['ctg'] }}</td>
                                                                <td style="width: 40px;">{{ $unit->prefix() }}</td>
                                                                <td class="fw-bold font-monospace text-end">{{ number_format($ctgtotal, 0, ',', '.') }}</td>
                                                                <td class="pe-0" style="width: 40px;">{{ $unit->suffix() }}</td>
                                                            </tr>
                                                            @php($sliptotal[$loop->parent->index] += $ctgtotal)
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-body border-top">
                                    <div><strong>Total {{ $slip['slip'] }} Rp. {{ number_format($sliptotal[$loop->index], 0, ',', '.') }}</strong></div>
                                    <div class="text-muted"><cite>Terbilang {{ inwords($sliptotal[$loop->index]) }}</cite></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Take Home Pay (THP)</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <div class="card card-body mb-0">
                            <h4>Rp {{ number_format($salary->amount, 0, ',', '.') }}</h4>
                            <div class="small text-muted"><cite>Terbilang: <span class="thp-inwords">{{ inwords($salary->amount) }}</span> rupiah</cite></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Catatan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <div class="card card-body mb-0">
                            <div class="text-muted"><cite>{{ $salary->description ?? '' }}</cite></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10">
                        <hr>
                        <div class="form-check mb-3">
                            <input class="form-check-input" id="agreement" type="checkbox" name="validated" value="1">
                            <label class="form-check-label" for="agreement">Dengan ini saya selaku Keuangan (Finance) menyatakan data di atas adalah valid</label>
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::payroll.validations.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .readonly {
            background: transparent !important;
        }
    </style>
@endpush
