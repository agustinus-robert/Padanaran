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

@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-md-8">
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

        <div class="col-xl-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Filter</h6>
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.attendance.schedules.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode Pengajuan</label>
                             <x-date-range-select />
                        </div>
                        <div class="input-group input-group-dynamic mb-3">
                            <label class="form-label">Cari Nama Karyawan</label>
                            <x-input size="sm" type="text" name="search" value="{{ request('search') }}" onkeyup="searchTable()"></x-input>
                        </div>
                        <div class="mb-3">
                            <div class="form-check p-0">
                                <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                                <label class="form-check-label" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <x-btn type="submit" variant="dark">Terapkan</x-btn>
                            <a class="btn btn-light" href="{{ route('hrms::service.attendance.schedules.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- @can('store', Modules\HRMS\Models\EmployeeSchedule::class)
                <a class="btn btn-outline-secondary w-100 d-flex text-dark mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('hrms::service.attendance.schedules.collective.create', ['month' => request('month', date('Y-m')), 'next' => url()->full()]) }}">
                    <i class="mdi mdi-calendar-multiple-check me-3"></i>
                    <div>Input jadwal kerja kolektif <br> <small class="text-muted">Jika Kamu ingin meregistrasikan 1 jadwal ke banyak karyawan</small></div>
                </a>
            @endcan --}}

            <div class="card">
                <div class="card-header">
                    <h6>Laporan</h6>
                </div>

                <div class="card-body border-top">
                    <div class="list-group list-group-flush border-top">
                        <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi mengajar</a>
                    </div>

                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($start_at)) }}</small>
                </div>
            </div>
        </div>
    </div>
@endsection
