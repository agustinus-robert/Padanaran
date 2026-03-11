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

                if (\Gate::allows('viewAny', Modules\HRMS\Models\EmployeeVacationQuota::class)) {
                    $routes['show'] = '#collapse-'.$employee->id;
                }

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

@push('additional-content')
    {{-- 1. KARTU FILTER --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3 mb-4">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">event_note</i> Filter Tahun
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.vacation.quotas.index') }}" method="get">
                <div class="input-group input-group-dynamic mb-4 is-filled">
                    <label class="form-label">Tahun Kuota</label>
                    <x-input 
                        type="number" 
                        min="1970" 
                        max="{{ date('Y', strtotime('+10 years')) }}" 
                        id="year" 
                        name="year" 
                        value="{{ request('year', date('Y')) }}" 
                        required 
                    />
                </div>

                <x-btn type="submit" variant="dark" class="btn-sm w-100 mb-0">
                    <i class="material-symbols-rounded text-sm me-1">search</i> Tampilkan
                </x-btn>
            </form>
        </div>
    </div>

    {{-- 2. AKSI KOLEKTIF (BATCH CREATE) --}}
    @can('store', Modules\HRMS\Models\EmployeeVacationQuota::class)
        <form action="{{ route('hrms::service.vacation.quotas.batch-create', ['year' => request('year', date('Y')), 'next' => url()->full()]) }}" method="POST" class="form-confirm">
            @csrf
            <button type="submit" class="btn btn-outline-primary w-100 d-flex align-items-center mb-3 bg-white py-3 text-start shadow-none" 
                style="border-style: dashed; text-transform: none; border-width: 2px;">
                <i class="material-symbols-rounded me-3 text-primary">Groups</i>
                <div style="line-height: 1.2;">
                    <span class="d-block font-weight-bold text-dark">Distribusi Kolektif {{ request('year', date('Y')) }}</span>
                    <small class="text-xxs text-muted" style="white-space: normal;">Terapkan kuota otomatis untuk semua karyawan tahun ini.</small>
                </div>
            </button>
        </form>
    @endcan

    {{-- 3. MENU LAINNYA (OPSIONAL) --}}
    @if (false) {{-- Sesuai logic original --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Menu Lainnya</h6>
        </div>
        <div class="card-body p-3">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm" 
                   href="{{ route('hrms::service.vacation.quotas.create', ['year' => request('year'), 'next' => url()->full()]) }}">
                    <i class="material-symbols-rounded me-2 text-success">add_circle</i> Tambah distribusi baru
                </a>
            </div>
        </div>
    </div>
    @endif
@endpush


@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-md-12">
            <x-table
                :isSearch="true"
                type="material"
                :data="$employees"
                :columns="$columns"
                title="Kelola Distribusi Quota"
                searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}"
                :trash="$trashed"
                :extracollapse="[
                    'row' => function($employee, $colspan) {
                        return view('hrms::service.vacation.quotas.extras-collapse', compact('employee','colspan'))->render();
                    }
                ]"
            />
        </div>
    </div>
@endsection
