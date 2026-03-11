@extends('layouts.horizontal-layout')

@section('title', 'Jadwal kerja | ')
@section('navtitle', 'Jadwal kerja')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => '',
        'slot'  => fn ($employee) =>
            '<div class="rounded-circle"
                style="
                    background: url(\''.$employee->user->profile_avatar_path.'\')
                    center center no-repeat;
                    background-size: cover;
                    width: 32px;
                    height: 32px;
                ">
            </div>',
        'class' => 'text-center',
    ],
    [
        'label' => 'Nama',
        'slot'  => fn ($employee) =>
            '<strong>'.($employee->user->profile->name ?? $employee->user->name).'</strong><br>
             <small class="text-muted">'.($employee->contract->position?->position->name ?? '').'</small>',
    ],
    [
        'label' => 'Periode',
        'slot'  => fn () =>
            strftime('%B %Y', strtotime(request('month', date('Y-m')))),
    ],
    [
        'label' => 'Jumlah hari kerja',
        'slot'  => fn ($employee) =>
            $employee->schedules->first()?->workdays_count ?: '-',
        'class' => 'text-center',
    ],
    [
        'label' => '',
        'slot'  => function ($employee) {

            $schedule = $employee->schedules->first();
            $routes   = [];
            $params   = [];

            if ($employee->contract) {

                // EDIT / SHOW
                if ($schedule && \Gate::allows('show', $schedule)) {
                    $routes['show'] = 'hrms::service.attendance.schedules.show';
                    $params['show'] = [
                        'schedule' => $schedule->id,
                        'next'     => url()->full(),
                    ];
                }

                // CREATE
                if (!$schedule && \Gate::allows('store', Modules\HRMS\Models\EmployeeSchedule::class)) {
                    $routes['create'] = 'hrms::service.attendance.schedules.create';
                    $params['create'] = [
                        'employee' => $employee->id,
                        'month'    => request('month', date('Y-m')),
                        'next'     => url()->full(),
                    ];
                }

                // DELETE
                if ($schedule && \Gate::allows('destroy', $schedule)) {
                    $routes['destroy'] = 'hrms::service.attendance.schedules.destroy';
                    $params['destroy'] = [
                        'schedule' => $schedule->id,
                        'next'     => url()->full(),
                    ];
                }
            }

            return view('components.partial-actions', [
                'item'     => $schedule ?? $employee,
                'routes'   => $routes,
                'params'   => $params,
                'trashed'  => false,
                'useModal' => false,
            ])->render();
        },
        'class' => 'text-end',
    ],
];
@endphp

@push('additional-content')
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_alt</i> Filter
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.attendance.schedules.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode Pengajuan</label>
                    <x-date-range-select />
                </div>

                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Cari Nama Karyawan</label>
                    <x-input size="sm" type="text" name="search" value="{{ request('search') }}" onkeyup="searchTable()"></x-input>
                </div>

                <div class="form-check p-0 mb-3">
                    <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" {{ request('trashed') ? 'checked' : '' }}>
                    <label class="form-check-label text-xs" for="trashed">Tampilkan pengajuan dihapus</label>
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" href="{{ route('hrms::service.attendance.schedules.index') }}" title="Reset">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">summarize</i> Laporan
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="list-group list-group-flush border-radius-lg">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled" href="javascript:;">
                    <i class="material-symbols-rounded me-2 text-success">description</i> Rekapitulasi mengajar
                </a>
            </div>

            <hr class="horizontal dark my-2">

            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    Laporan berdasarkan filter: <br>
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
            <div class="col-md-12">
                <x-table
                    :isSearch="true"
                    type="material"
                    :data="$employees"
                    :columns="$columns"
                    title="Kelola Jadwal Kehadiran"
                    searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                />
            </div>
        </div>
    </div>
@endsection
