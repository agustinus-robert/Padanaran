@extends('layouts.horizontal-layout')

@section('title', 'Rekapitulasi rekap mengajar | ')
@section('navtitle', 'Rekapitulasi rekap mengajar')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
@include('components.navbar-admin')

@push('style')
<style>
    .mt-65 {
        margin-top: 1.65rem !important;
    }
</style>
@endpush

@php
    $isUpdate = isset($attendance);

    $employeeModel = $isUpdate ? $attendance->employee : $employee;
    $startDate = $isUpdate ? $attendance->start_at : $start_at;
    $endDate   = $isUpdate ? $attendance->end_at   : $end_at;

    $result = $isUpdate ? $attendance->result : null;

    $actionUrl = $isUpdate
        ? route('hrms::summary.teachings.update', [
            'teaching' => $attendance->empl_id,
            'start_at' => $startDate->format('Y-m-d'),
            'end_at'   => $endDate->format('Y-m-d'),
            'next'     => request('next', route('hrms::summary.attendances.index'))
        ])
        : route('hrms::summary.teachings.store', [
            'employee' => $employeeModel->id,
            'start_at' => $startDate->format('Y-m-d'),
            'end_at'   => $endDate->format('Y-m-d'),
            'next'     => request('next', route('hrms::summary.attendances.index'))
        ]);
@endphp

<div class="row container-fluid justify-content-center">
    <div class="col-xl-12">
        <div class="card shadow-sm">
            <x-card-header type="{{ config('theme.default') }}">
                {{ $isUpdate ? 'Ubah Rekap Mengajar' : 'Buat Rekap Mengajar' }}
            </x-card-header>

            <div class="row">
                <div class="col-xl-4">
                    <div class="card border-0">
                        <div class="card-body text-center mt-65">
                            <b>Riwayat Rekap Mengajar</b>
                        </div>
                        <div class="table-responsive border-top" style="overflow: auto; max-height: 960px;">
                            <table class="mb-0 table">
                                <tbody>
                                    @forelse ($entries as $date => $shifts)
                                        @foreach ($shifts as $entry)
                                            <tr>
                                                <td>{{ $loop->parent->iteration }}</td>
                                                <td>
                                                    <span @if ($moment = $moments->firstWhere('date', $date)) data-bs-toggle="tooltip" title="" data-bs-placement="right" data-bs-original-title="{{ $moment->name }}" @endif @class(['fw-bold', 'text-danger' => $moment])>
                                                        @php
                                                            $modifier = $entry->modifier ?? null;
                                                            $adjustment = 0;

                                                            if ($modifier !== null) {
                                                                if (str_starts_with($modifier, '+')) {
                                                                    $adjustment = floatval($modifier);
                                                                } elseif (str_starts_with($modifier, '-')) {
                                                                    $adjustment = floatval($modifier);
                                                                }
                                                            }

                                                            $baseHour = 2 + $adjustment;
                                                        @endphp

                                                        {{ strftime('%A, %d %b %Y', strtotime($date)) }}
                                                        @if ($moment)
                                                            <i class="mdi mdi-information-outline"></i>
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @php($original['times'][$date] = $entry->in?->format('H:i:s') ?? null)
                                                    @php($currentday = $scanlogs[$date] ?? [])
                                                    {{-- {{ implode(', ', array_map(fn($location) => $locations[$location], $entry->location)) }} --}}
                                                    @foreach ($entry->location ?? [1] as $k => $v)
                                                        @if ($loop->first && $loop->last)
                                                        @elseif($loop->last && !$loop->first)
                                                            @php($current = $currentday->where('location', $v))
                                                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $current->first()->created_at->format('H:i:s') }}">
                                                                <span class="text-dark">{{ $locations[$v] }}</span>
                                                                <small>
                                                                    <i class="mdi mdi-information-outline text-muted"></i>
                                                                </small>
                                                            </span>
                                                        @else
                                                            {{ $locations[$v] }},
                                                        @endif
                                                    @endforeach

                                                    @php($dateWeekEnd = date('w', strtotime($entry->date)))

                                                    @if ($dateWeekEnd == 0 || $dateWeekEnd == 6)
                                                        {{ $entry->shift->label() }} <sup><b>Extra</b></sup>
                                                    @else
                                                        @if ($entry->shift->value == 5)
                                                            {{ $entry->shift->label() }} <sup><b>Extra</b></sup>
                                                        @else
                                                            {{ $entry->shift->label() }}
                                                        @endif
                                                    @endif
                                                </td>
                                                <td @class(['text-center'])>
                                                    @if ($baseHour < 2)
                                                        <span class="badge bg-danger">
                                                            {{ $baseHour }} jam</span>
                                                    @elseif($baseHour == 2)
                                                        <span class="badge bg-primary">
                                                            {{ $baseHour }} jam</span>
                                                    @elseif($baseHour > 2)
                                                        <span class="badge bg-success">
                                                            {{ $baseHour }} jam</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td>
                                                <div class="container" style="width:423px;">
                                                    @include('components.notfound-vertical')
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card-body border-top">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="text-muted">Nama karyawan</div>
                                <div class="fw-bold">{{ $employeeModel->user->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted">Periode</div>
                                <div class="d-flex align-items-center">
                                    <div>{{ $startDate->format('d-M-Y') }}</div>
                                    <div class="mx-2 text-muted">&mdash;</div>
                                    <div>{{ $endDate->format('d-M-Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body border-top">
                        <form method="POST" action="{{ $actionUrl }}" class="form-confirm">
                            @csrf
                            @if($isUpdate)
                                @method('PUT')
                            @endif

                            <div class="row gy-4">
                                {{-- UMUM --}}
                                <div class="col-lg-6">
                                    <h6 class="fw-bold mb-3">Rekapitulasi Umum</h6>

                                    <x-input-group :isOutline="false" :isRow="true" label="Jumlah Hari" prepend="hari">
                                        <x-input type="number" step="0.1"
                                            name="summary[days]"
                                            value="{{ $result->days ?? ($startDate->diffInDays($endDate)+1) }}" />
                                    </x-input-group>

                                    <x-input-group :isOutline="false" :isRow="true" label="Hari Efektif" prepend="hari">
                                        <x-input type="number" step="0.1"
                                            name="summary[workdays]"
                                            value="{{ $result->workdays ?? 0 }}" />
                                    </x-input-group>

                                    <x-input-group :isOutline="false" :isRow="true" label="Libur Nasional" prepend="hari">
                                        <x-input type="number" step="0.1"
                                            name="summary[holidays]"
                                            value="{{ $result->holidays ?? 0 }}" />
                                    </x-input-group>
                                </div>

                                {{-- IZIN --}}
                                <div class="col-lg-6">
                                    <h6 class="fw-bold mb-3">Perizinan</h6>

                                    <x-input-group :isOutline="false" :isRow="true" label="Izin" prepend="hari">
                                        <x-input type="number" step="0.1"
                                            name="summary[unpresence][leave]"
                                            value="{{ $result->unpresence->leave ?? count($employeeLeaves ?? []) }}" />
                                    </x-input-group>

                                    @foreach (Modules\Core\Enums\VacationTypeEnum::cases() as $type)
                                        <x-input-group :isOutline="false" :isRow="true"
                                            label="{{ $type->label() }}" prepend="hari">
                                            <x-input type="number" step="0.1"
                                                name="summary[unpresence][vacation][{{ strtolower($type->name) }}]"
                                                value="{{ $result->unpresence->vacation->{strtolower($type->name)} ?? ($employeeVacationsSums[strtolower($type->name)] ?? 0) }}"
                                                onclick="modalVacation('vacation{{ $type->value }}')" />
                                        </x-input-group>
                                    @endforeach
                                </div>

                                {{-- KEHADIRAN --}}
                                <div class="col-lg-6">
                                    <h6 class="fw-bold mb-3">Kehadiran Mengajar</h6>

                                    <x-input-group :isOutline="false" :isRow="true" label="Reguler" prepend="hari">
                                        <x-input type="number" step="0.1"
                                            name="summary[attendance_work]"
                                            value="{{ $result->attendance_work ?? 0 }}" />
                                    </x-input-group>

                                    <x-input-group :isOutline="false" :isRow="true" label="Ekstra" prepend="hari">
                                        <x-input type="number" step="1"
                                            name="summary[additional_workdays]"
                                            value="{{ $result->additional_workdays ?? 0 }}" />
                                    </x-input-group>

                                    <x-input-group :isOutline="false" :isRow="true" label="Total" prepend="hari">
                                        <x-input type="number" step="0.1"
                                            name="summary[attendance_total]"
                                            value="{{ $result->attendance_total ?? ($presenced->count() ?? 0) }}" />
                                    </x-input-group>
                                </div>

                                {{-- JAM --}}
                                <div class="col-lg-6">
                                    <h6 class="fw-bold mb-3">Jam Mengajar</h6>

                                    <x-input-group :isOutline="false" :isRow="true" label="Total Jam" prepend="jam">
                                        <x-input type="number" step="0.1"
                                            name="teach[amount_total]"
                                            value="{{ round($hourTotal ?? 0, 1) }}" />
                                    </x-input-group>

                                    <x-input-group :isOutline="false" :isRow="true" label="Over" prepend="jam">
                                        <x-input type="number" step="0.1"
                                            name="teach[overhour]"
                                            value="{{ round($extraOver ?? 0, 1) }}" />
                                    </x-input-group>

                                    <x-input-group :isOutline="false" :isRow="true" label="Extra" prepend="jam">
                                        <x-input type="number" step="0.1"
                                            name="teach[extrahour]"
                                            value="{{ round($hourExtra ?? 0, 1) }}" />
                                    </x-input-group>
                                </div>
                            </div>

                            <hr>

                            @if ($userNow !== 3)
                                <div class="card card-body border mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" required>
                                        <label class="form-check-label">
                                            Dengan ini saya menyatakan data di atas valid
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <x-btn type="submit" color="danger" icon="mdi-check">
                                        {{ $isUpdate ? 'Update Rekap' : 'Simpan Rekap' }}
                                    </x-btn>

                                    <x-btn variant="light"
                                        href="{{ request('next', route('hrms::summary.attendances.index')) }}"
                                        icon="mdi-arrow-left">
                                        Kembali
                                    </x-btn>
                                </div>

                                <input type="hidden" name="original"
                                    value='@json($result?->original ?? $original ?? [])'>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="leaveDatesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rincian Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i=1)
                        @foreach($employeeLeaves as $leave)
                            @foreach(json_decode($leave->dates) as $d)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->d)->translatedFormat('l, d F Y') }}</td>
                                    <td>
                                        <span class="badge bg-danger">Sakit</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach (Modules\Core\Enums\VacationTypeEnum::cases() as $type)
<div class="modal fade" id="vacation{{ $type->value }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tanggal {{ $type->label() }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i=1)
                        @foreach ($employeeVacations->where(fn($v)=>$v->quota?->category?->type->value === $type->value) as $vac)
                            @foreach (json_decode($vac->dates,true) as $dt)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($dt['d'])->translatedFormat('l, d F Y') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    function modalVacation(id){
        const el = document.getElementById(id)
        if(!el) return
        new bootstrap.Modal(el).show()
    }
</script>
@endpush
