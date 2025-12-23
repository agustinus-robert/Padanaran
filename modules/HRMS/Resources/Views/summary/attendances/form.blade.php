@extends('layouts.horizontal-layout')

@section('title', 'Rekapitulasi presensi | ')
@section('navtitle', 'Rekapitulasi presensi')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
@include('components.navbar-admin')

@php
    $isUpdate = isset($attendance);
    $result = $isUpdate ? $attendance->result : null;
    $actionUrl = $isUpdate
        ? route('hrms::summary.attendances.update', [
            'attendance' => $attendance->id,
            'employee' => $attendance->empl_id,
            'start_at' => $attendance->start_at->format('Y-m-d'),
            'end_at' => $attendance->end_at->format('Y-m-d'),
            'next' => request('next', route('hrms::summary.attendances.index'))
        ])
        : route('hrms::summary.attendances.store', [
            'employee' => $employee->id,
            'start_at' => $start_at->format('Y-m-d'),
            'end_at' => $end_at->format('Y-m-d'),
            'next' => request('next', route('hrms::summary.attendances.index'))
        ]);
@endphp

<div class="row container-fluid justify-content-center">
    <div class="col-xl-8">
        <div class="card shadow-sm">
            <x-card-header type="{{ config('theme.default') }}">
                Kelola Rekapitulasi
            </x-card-header>

            <div class="card-body border-top border-light">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <div class="text-muted">Nama karyawan</div>
                        <div class="fw-bold">{{ $employee->user->name ?? $attendance->employee->user->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted">
                            <span data-bs-toggle="tooltip" data-bs-placement="right" title="Tanggal pada periode ini akan digunakan untuk penghitungan gaji, jadi pastikan tanggal yang Kamu isi adalah benar">
                                <span>Periode</span>
                                <i class="mdi mdi-information-outline"></i>
                            </span>
                        </div>
                        <div class="align-items-center d-flex">
                            <div>{{ $start_at->format('d-M-Y') ?? $attendance?->start_at?->format('d-M-Y') }}</div>
                            <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                            <div>{{ $end_at->format('d-M-Y') ?? $attendance?->end_at?->format('d-M-Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body border-top border-light">
                <form action="{{ $actionUrl }}" method="post" class="form-block form-confirm">
                    @csrf
                    @if($isUpdate)
                        @method('PUT')
                    @endif

                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <h6 class="fw-bold mb-3">Rekapitulasi Keseluruhan</h6>

                            <x-input-group :isOutline="false" :isRow="true" label="Jumlah Hari" prepend="Hari">
                                <x-input type="number" name="summary[days]" min="0" step="0.1" value="{{ $result->days ?? ($start_at->diffInDays($end_at)+1) }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" :isRow="true" label="Hari Efektif" prepend="hari">
                                <x-input type="number" name="summary[workdays]" min="0" step="0.1" value="{{ $result->workdays ?? $workDays }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" :isRow="true" label="Hari Libur Nasional"  prepend="hari">
                                <x-input type="number" name="summary[holidays]" min="0" step="0.1" value="{{ $result->holidays ?? $moments->count() }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" label="Jumlah Presensi" :isRow="true" prepend="hari">
                                <x-input type="number" name="summary[attendance_total]" min="0" step="0.1" value="{{ $result->attendance_total ?? ($presences->count() + $adtDays) }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" label="Jumlah Tepat Waktu" :isRow="true" prepend="hari">
                                <x-input type="number" name="summary[ontime_total]" min="0" step="0.1" value="{{ $result->ontime_total ?? $entries->flatten()->filter(fn($e) => $e->ontime)->count() }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" label="Jumlah Terlambat" :isRow="true" prepend="hari">
                                <x-input type="number" name="summary[late_total]" min="0" step="0.1" value="{{ $result->late_total ?? $entries->flatten()->filter(fn($e) => !$e->ontime)->count() }}" />
                                <span class="input-group-text">hari</span>
                            </x-input-group>
                        </div>

                        <div class="col-lg-6">
                            <h6 class="fw-bold mb-3">Rekapitulasi Berdasar Lokasi</h6>

                            <x-input-group :isOutline="false" label="Presensi WFO" :isRow="true" prepend="hari">
                                <x-input type="number" name="summary[presence][wfo]" min="0" step="0.1" value="{{ $result->presence->wfo ?? $presences->filter(fn($e) => count($e->location)==1 && $e->location[0]==1)->count() }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" label="Presensi WFA"  :isRow="true" prepend="hari">
                                <x-input type="number" name="summary[presence][wfa]" min="0" step="0.1" value="{{ $result->presence->wfa ?? $presences->filter(fn($e) => count($e->location)==1 && $e->location[0]==2)->count() }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" label="Jumlah WFO ke WFA" :isRow="true" prepend="hari">
                                <x-input type="number" name="summary[presence][move]" min="0" step="0.1" value="{{ $result->presence->move ?? $presences->filter(fn($e) => count($e->location)==2)->count() }}" />
                                <span class="input-group-text">hari</span>
                            </x-input-group>
                        </div>

                        <div class="col-lg-6">
                            <h6 class="fw-bold mb-3">Rekapitulasi Perizinan</h6>

                            <x-input-group :isOutline="false" label="Jumlah Izin" :isRow="true" prepend="hari">
                                <x-input type="number" name="summary[unpresence][leave]" min="0" step="0.1" value="{{ $result->unpresence->leave ?? 0 }}" />
                                <span class="input-group-text">hari</span>
                            </x-input-group>

                            @foreach (Modules\Core\Enums\VacationTypeEnum::cases() as $type)
                                <x-input-group :isOutline="false" label="Jumlah {{ $type->label() }}" isRow="true" prepend="hari">
                                    <x-input type="number" name="summary[unpresence][vacation][{{ strtolower($type->name) }}]" min="0" step="0.1" value="{{ $result->unpresence->vacation->{strtolower($type->name)} ?? 0 }}" />
                                    <span class="input-group-text">hari</span>
                                </x-input-group>
                            @endforeach

                            <x-input-group :isOutline="false" label="Kompensasi Cuti" :isRow="true" prepend="hari">
                                <x-input type="number" name="summary[unpresence][cashable_vacation]" min="0" step="0.1" value="{{ $result->unpresence->cashable_vacation ?? 0 }}" />
                                <span class="input-group-text">hari</span>
                            </x-input-group>
                        </div>

                        <div class="col-lg-6">
                            <h6 class="fw-bold mb-3">Rekapitulasi Lembur</h6>

                            <x-input-group :isOutline="false" label="Jumlah Lembur Kelebihan Hari" :isRow="true" prepend="jam">
                                <x-input type="number" name="summary[overtime][overdays]" min="0" step="0.01" oninput="sumOvertimeTotal()" value="{{ $result->overtime->overdays ?? 0 }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" label="Jumlah Lembur Tanggal Merah" :isRow="true" prepend="jam">
                                <x-input type="number" name="summary[overtime][holidays]" min="0" step="0.01" oninput="sumOvertimeTotal()" value="{{ $result->overtime->holidays ?? 0 }}" />
                            </x-input-group>

                            <x-input-group :isOutline="false" label="Jumlah Lembur Keseluruhan" :isRow="true" prepend="jam">
                                <x-input type="number" name="summary[overtime][total]" readonly value="{{ $result->overtime->total ?? 0 }}" />
                            </x-input-group>
                        </div>
                    </div>

                    <hr>

                    <div class="card card-body border mb-3 justify checklist-item checklist-item-primary">
                        <div class="mb-3">
                            <div class="input-group w-100">
                                <div class="form-check is-filled">
                                    <input type="checkbox" id="as_template" name="as_template" value="1" class="form-check-input">
                                    <label for="as_template" class="ms-2">
                                        <strong>Dengan ini saya selaku HR menyatakan data di atas valid</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="d-flex gap-2">
                        <x-btn type="submit" color="danger" icon="mdi-check">Simpan</x-btn>
                        <x-btn variant="light" href="{{ request('next', route('hrms::service.attendance.schedules.index')) }}" icon="mdi-arrow-left">Kembali</x-btn>
                    </div>

                    <input type="hidden" name="original" value='{{ json_encode($result?->original ?? []) }}'>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const sumOvertimeTotal = () => {
        let overdays = parseFloat(document.querySelector('[name="summary[overtime][overdays]"]').value || 0)
        let holidays = parseFloat(document.querySelector('[name="summary[overtime][holidays]"]').value || 0)
        document.querySelector('[name="summary[overtime][total]"]').value = (overdays + holidays).toFixed(2);
    }

    document.querySelector('[name="summary[overtime][overdays]"]').addEventListener('input', sumOvertimeTotal)
    document.querySelector('[name="summary[overtime][holidays]"]').addEventListener('input', sumOvertimeTotal)
    sumOvertimeTotal()
</script>
@endpush
