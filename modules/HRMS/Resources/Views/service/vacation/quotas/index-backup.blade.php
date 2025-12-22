@extends('layouts.horizontal-layout')

@section('title', 'Distribusi cuti | ')
@section('navtitle', 'Distribusi cuti')

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
        'slot'  => function ($employee) {

            $name = $employee->user->profile->name ?? $employee->user->name;

            if ($employee->contract) {
                $status = $employee->contract->is_active
                    ? 'text-success'
                    : 'text-danger';

                $contract = '
                    <small class="text-muted">
                        <i class="mdi mdi-circle '.$status.'" style="font-size:9pt"></i>
                        '.$employee->contract->kd.'
                    </small>';
            } else {
                $contract = '
                    <small class="text-muted">
                        <i class="mdi mdi-circle text-secondary" style="font-size:9pt"></i>
                        Tidak ada kontrak aktif
                    </small>';
            }

            return "<strong>{$name}</strong><br>{$contract}";
        },
    ],

    [
        'label' => 'Jabatan',
        'slot'  => fn ($employee) =>
            $employee->contract && optional($employee->contract->position)->position
                ? '<span class="badge bg-dark small">'
                    .optional($employee->contract->position->position)->name.
                  '</span>'
                : '',
    ],

    [
        'label' => 'Jumlah distribusi cuti',
        'slot'  => fn ($employee) =>
            $employee->vacationQuotas->count(),
        'class' => 'text-center',
    ],

    [
        'label' => '',
        'class' => 'text-end',
        'slot'  => function ($employee) use ($year) {

            $routes = [];
            $params = [];

            if ($employee->contract) {

                // SHOW (collapse handled di view)
                if (\Gate::allows('viewAny', Modules\HRMS\Models\EmployeeVacationQuota::class)) {
                    $routes['show'] = '#collapse-'.$employee->id;
                }

                // CREATE
                if (\Gate::allows('store', Modules\HRMS\Models\EmployeeVacationQuota::class)) {
                    $routes['create'] = 'hrms::service.vacation.quotas.create';
                    $params['create'] = [
                        'employee' => $employee->id,
                        'year'     => $year,
                        'next'     => url()->full(),
                    ];
                }
            }

            return view('components.partial-actions', [
                'item'     => $employee,
                'routes'   => $routes,
                'params'   => $params,
                'trashed'  => false,
                'useModal' => false,
            ])->render();
        },
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
                title="Kelola Distribusi Quota"
                searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-header">
                    <h6>Filter</h6>
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.vacation.quotas.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required" for="year">Tahun</label>
                            <input type="number" min="1970" max="{{ date('Y', strtotime('+10 years')) }}" step="1" class="form-control" id="year" name="year" value="{{ request('year', date('Y')) }}" required>
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-magnify"></i> Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            @if (false)
                <div class="card mb-3 border-0">
                    <div class="card-header">
                        <h6>Menu lainnya</h6>
                    </div>

                    <div class="card-body">
                        <div class="list-group list-group-flush border-top border-light">
                            <a class="list-group-item list-group-item-action" href="{{ route('hrms::service.vacation.quotas.create', ['year' => request('year'), 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah distribusi cuti baru</a>
                        </div>
                    </div>
                </div>
            @endif
            @can('store', Modules\HRMS\Models\EmployeeVacationQuota::class)
                <form action="{{ route('hrms::service.vacation.quotas.batch-create', ['year' => request('year', date('Y')), 'next' => url()->full()]) }}" method="POST" class="form-block form-confirm">@csrf
                    <button class="btn btn-outline-secondary w-100 d-flex text-dark mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;">
                        <i class="mdi mdi-calendar-multiple-check me-3"></i>
                        <div>Distribusi cuti kolektif <br> <small class="text-muted">Terapkan distribusi kuota cuti otomatis untuk semua karyawan tahun {{ request('year', date('Y')) }}</small></div>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection
