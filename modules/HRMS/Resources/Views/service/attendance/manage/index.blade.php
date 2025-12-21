@extends('layouts.horizontal-layout')

@section('title', 'Kelola presensi | ')
@section('navtitle', 'Kelola presensi')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush


@php
$daynames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];

$trashed = null;
$columns = [
    [
        'label' => '',
        'slot'  => fn ($schedule) =>
            '<div class="rounded-circle"
                style="
                    background: url(\''.$schedule->employee->user->profile_avatar_path.'\')
                    center center no-repeat;
                    background-size: cover;
                    width: 32px;
                    height: 32px;
                ">
            </div>',
    ],
    [
        'label' => 'Nama',
        'slot'  => fn ($schedule) =>
            '<strong class="d-block">'.$schedule->employee->user->name.'</strong>
             <small class="text-muted">'.($schedule->position->position->name ?? '-').'</small>',
    ],
    [
        'label' => 'Periode',
        'slot'  => fn ($schedule) =>
            strftime('%B %Y', strtotime($schedule->period)),
    ],
    [
        'label' => 'Hari kerja efektif',
        'class' => 'text-center',
        'slot'  => fn ($schedule) =>
            (
                collect($schedule->dates)
                    ->filter(fn ($v, $date) =>
                        strtotime($date) >= strtotime($start_at)
                        && strtotime($date) <= strtotime($end_at)
                    )
                    ->flatten()
                    ->filter()
                    ->count() / 2
            ) . ' hari',
    ],
    [
        'label' => 'Persentase (%)',
        'class' => 'text-center',
        'slot'  => function ($schedule) use ($scanlogs) {

            $logs = $scanlogs
                ->filter(fn ($log) =>
                    $log->empl_id == $schedule->empl_id
                    && $log->created_at->isSameMonth($schedule->period)
                )
                ->groupBy(fn ($log) => $log->created_at->format('Y-m-d'));

            $entries = collect($schedule->getEntryLogs($logs));
            $ontime  = $entries->flatten(1)->countBy('ontime');
            $total   = max($ontime->sum(), 1);

            return '
                <div class="progress" style="height:4px">
                    <div class="progress-bar bg-success"
                        style="width: '.(($ontime[1] ?? 0) / $total * 100).'%"
                        title="Tepat waktu '.($ontime[1] ?? 0).'x"></div>
                    <div class="progress-bar bg-danger"
                        style="width: '.(($ontime[0] ?? 0) / $total * 100).'%"
                        title="Terlambat '.($ontime[0] ?? 0).'x"></div>
                </div>';
        },
    ],
    [
        'label' => '',
        'class' => 'text-end',
        'slot'  => function ($schedule) {

            $routes = [];
            $params = [];

            if (Gate::allows('show', $schedule)) {
                $routes['show'] = 'hrms::service.attendance.schedules.show';
                $params['show'] = [
                    'schedule' => $schedule->id,
                    'next'     => url()->full(),
                ];
            }

            if (Gate::allows('destroy', $schedule)) {
                $routes['destroy'] = 'hrms::service.attendance.schedules.destroy';
                $params['destroy'] = [
                    'schedule' => $schedule->id,
                    'next'     => url()->full(),
                ];
            }

            return view('components.partial-actions', [
                'item'     => $schedule,
                'routes'   => $routes,
                'params'   => $params,
                'trashed'  => false,
                'useModal' => false,
            ])->render()
            . '
            <button class="btn btn-soft-primary btn-sm ms-1"
                data-bs-toggle="collapse"
                data-bs-target="#collapse-'.$schedule->id.'"
                title="Lihat kalender kerja">
                <i class="mdi mdi-calendar-outline"></i>
            </button>';
        },
    ],

];
@endphp


@section('body-content')
    @include('components.navbar-admin')
    <div class="container-fluid row">
        <div class="col-xl-8">
            <x-table
                :isSearch="true"
                type="material"
                :data="$schedules"
                :columns="$columns"
                title="Kelola presensi karyawan"
                searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-xl-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Filter</h6>
                </div>

                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.attendance.manage.index') }}" method="get">
                        <x-input-group :isRow="false" :isInputGroup="true" label="Periode">
                             <x-date-range-select />
                        </x-input-group>

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
                                class="form-control"
                                name="search"
                                placeholder="Cari nama karyawan ..."
                                value="{{ request('search') }}"
                                onkeyup="searchTable()"
                            />
                        </x-input-group>

                        <div class="d-flex justify-content-between">
                            <x-btn type="submit" varitant="dark">Terapkan</x-btn>
                            <a class="btn btn-light" href="{{ route('hrms::service.attendance.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Lanjutan</h6>
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top">
                        <a class="list-group-item list-group-item-action py-3" href="{{ route('hrms::service.attendance.scanlogs.index') }}"><i class="mdi mdi-calendar-alert"></i> Lihat daftar scanlog</a>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Laporan</h6>
                </div>

                <div class="card-body">

                    <div class="list-group list-group-flush border-top">
                        <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi presensi</a>
                        <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Data scanlog presensi</a>
                    </div>
                    <div class="card-body border-top">
                        <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($start_at)) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script>
        const renderPositions = () => {
            let department = document.querySelector('#select-departments option:checked');
            let option = '<option value>Semua jabatan</option>';
            let selected = '{{ request('position') }}';
            if (department.dataset.positions) {
                let pos = JSON.parse(department.dataset.positions);
                Object.keys(pos).forEach((id) => {
                    option += `<option value="${id}" ` + (selected == id ? 'selected="selected"' : '') + `)>${pos[id]}</option>`
                })
            }
            document.getElementById('select-positions').innerHTML = option;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('select-departments').addEventListener('change', renderPositions);
            renderPositions();
        });
    </script>
@endpush
