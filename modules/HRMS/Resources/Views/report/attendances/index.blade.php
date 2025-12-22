@extends('layouts.horizontal-layout')

@section('title', 'Laporan kehadiran | ')
@section('navtitle', 'Laporan kehadiran')


@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => '',
        'field' => fn($employee) => '<div class="rounded-circle" style="background: url(\''.$employee->user->profile_avatar_path.'\') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>',
        'raw' => true,
        'class' => 'text-center',
        'width' => '10',
    ],
    [
        'label' => 'Nama',
        'field' => fn($employee) => '<div class="fw-bold">'.$employee->user->name.'</div><div class="small text-muted">'.(optional(optional(optional($employee->contract)->position)->position)->name ?? '-').'</div>',
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => 'Hari kerja efektif',
        'field' => function($employee) use ($start_at, $end_at) {
            $days = $employee->schedules
                ->map(function ($schedule) use ($start_at, $end_at) {
                    return collect($schedule->dates)
                        ->filter(fn($times, $date) => $start_at->lte($date) && $end_at->gte($date))
                        ->map(fn($date) => count(array_filter($date, fn($times) => count(array_filter($times)) == 2)))
                        ->sum();
                })->sum();
            return $days.' hari';
        },
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => 'Jumlah presensi',
        'field' => fn($employee) => $employee->schedules
            ->map(fn($schedule) => count(array_filter(Arr::flatten($schedule->entries, 1), fn($entry) => isset($entry->ontime) && !is_null($entry->ontime))))
            ->sum().' hari',
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => 'Tepat waktu',
        'field' => fn($employee) => $employee->schedules
            ->map(fn($schedule) => count(array_filter(Arr::flatten($schedule->entries, 1), fn($entry) => $entry->ontime === true)))
            ->sum().' hari',
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => 'Terlambat',
        'field' => fn($employee) => $employee->schedules
            ->map(fn($schedule) => count(array_filter(Arr::flatten($schedule->entries, 1), fn($entry) => $entry->ontime === false)))
            ->sum().' hari',
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => 'Persentase',
        'field' => function($employee) use ($start_at, $end_at) {
            $days = $employee->schedules
                ->map(function ($schedule) use ($start_at, $end_at) {
                    return collect($schedule->dates)
                        ->filter(fn($times, $date) => $start_at->lte($date) && $end_at->gte($date))
                        ->map(fn($date) => count(array_filter($date, fn($times) => count(array_filter($times)) == 2)))
                        ->sum();
                })->sum();

            $ontime = $employee->schedules
                ->map(fn($schedule) => count(array_filter(Arr::flatten($schedule->entries, 1), fn($entry) => $entry->ontime === true)))
                ->sum();

            $late = $employee->schedules
                ->map(fn($schedule) => count(array_filter(Arr::flatten($schedule->entries, 1), fn($entry) => $entry->ontime === false)))
                ->sum();

            $ontimePercent = $days > 0 ? ($ontime / $days) * 100 : 0;
            $latePercent = $days > 0 ? ($late / $days) * 100 : 0;

            return '<div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: '.$ontimePercent.'%" title="Tepat waktu '.$ontime.'x"></div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: '.$latePercent.'%" title="Terlambat '.$late.'x"></div>
                    </div>';
        },
        'raw' => true,
        'class' => 'text-center',
    ],
];
@endphp

@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Filter</h6>
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::report.attendances.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode</label>
                            <x-date-range-select />
                        </div>

                        <x-input-group :isRow="false" :isInputGroup="true" label="Departement">
                             <x-select
                                id="select-departments"
                                name="department"
                                placeholder="Semua departemen"
                                data-dependent="#select-positions"
                                data-source="positions"
                                :options="$departments->map(function($department) {
                                    return [
                                        'value' => $department->id,
                                        'label' => $department->name,
                                        'data-positions' => $department->positions->pluck('name', 'id'),
                                        'selected' => request('department') == $department->id
                                    ];
                                })->toArray()"
                            />
                         </x-input-group>

                         <x-input-group :isRow="false" :isInputGroup="true" label="Jabatan">
                            <x-select
                                id="select-positions"
                                name="position"
                                placeholder="Semua jabatan"
                            />
                        </x-input-group>


                        <x-input-group :isRow="false" :isInputGroup="true" label="Nama">
                            <x-input
                                class="mb-3"
                                name="search"
                                placeholder="Cari nama karyawan ..."
                                value="{{ request('search') }}"
                                onkeyup="searchTable()"
                            />
                        </x-input-group>

                        <div class="d-flex justify-content-between">
                            <x-btn type="submit" variant="dark">Terapkan</x-btn>
                            <a class="btn btn-light" href="{{ route('hrms::report.attendances.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Laporan</h6>
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top">
                        <button class="list-group-item list-group-item-action" onclick="summaryExportExcel()"><i class="mdi mdi-file-excel-outline me-3"></i> Unduh laporan data kehadiran</button>
                    </div>
                    <div class="card-body border-top">
                        <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($end_at)) }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 order-md-first">
            <section>
                 <x-table
                    :isSearch="true"
                    type="material"
                    :data="$employees"
                    :columns="$columns"
                    title="Daftar karyawan aktif"
                    :trash="$trashed"
                />
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
    @include('hrms::report.attendances.components.summary-excel-script')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            let listDepartmentId = () => {
                [].slice.call(document.querySelectorAll('[name="department"] option:checked')).map((select) => {
                    let c = '';
                    if (select.dataset.positions) {
                        let possition = JSON.parse(select.dataset.positions);
                        for (i in possition) {
                            c += '<option value="' + i + '" ' + (('{{ old('possition_id', -1) }}' == i) ? ' selected' : '') + '>' + possition[i] + '</option>';
                        }
                    }
                    document.querySelector('[name="position_id"]').innerHTML = c.length ? c : '<option value>Semua jabatan</option>'
                })
            }

            [].slice.call(document.querySelectorAll('[name="department"]')).map((el) => {
                el.addEventListener('change', listDepartmentId);
            });
            listDepartmentId();
        });
    </script>
@endpush
