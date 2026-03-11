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

@push('additional-content')
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_alt</i> Filter Presensi
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.attendance.manage.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode</label>
                    <x-date-range-select />
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Departemen</label>
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
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Jabatan</label>
                    <x-select
                        id="select-positions"
                        name="position"
                        placeholder="Semua jabatan"
                    />
                </div>

                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Nama Karyawan</label>
                    <x-input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        onkeyup="searchTable()"
                    />
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" href="{{ route('hrms::service.attendance.manage.index') }}">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Lanjutan</h6>
        </div>
        <div class="card-body p-3">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm" 
                   href="{{ route('hrms::service.attendance.scanlogs.index') }}">
                    <i class="material-symbols-rounded me-2 text-warning">history</i> Lihat daftar scanlog
                </a>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Laporan</h6>
        </div>
        <div class="card-body p-3">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled text-muted" href="javascript:;">
                    <i class="material-symbols-rounded me-2">description</i> Rekapitulasi presensi
                </a>
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled text-muted" href="javascript:;">
                    <i class="material-symbols-rounded me-2">analytics</i> Data scanlog presensi
                </a>
            </div>

            <hr class="horizontal dark my-2">

            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    <i class="material-symbols-rounded text-xxs">info</i> 
                    Laporan per tanggal: <br>
                    <strong>{{ date('d M Y', strtotime($start_at)) }}</strong> s.d. <strong>{{ date('d M Y', strtotime($end_at)) }}</strong>
                </p>
            </div>
        </div>
    </div>
@endpush

@section('body-content')
    @include('components.navbar-admin')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
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
