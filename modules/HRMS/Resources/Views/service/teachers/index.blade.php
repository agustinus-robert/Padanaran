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
        'slot' => fn($employee) => '<div class="rounded-circle" style="background: url(\''.$employee->user->profile_avatar_path.'\') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>',
        'class' => 'text-center',
    ],
    [
        'label' => 'Nama',
        'slot' => fn($employee) => '<strong>'.($employee->user->profile->name ?? $employee->user->name).'</strong><br><small class="text-muted">'.($employee->contract->position?->position->name ?? '-').'</small>',
    ],
    [
        'label' => 'Periode',
        'slot' => function($employee) {
            return request('start_at') && request('end_at')
                ? \Carbon\Carbon::parse(request('start_at'))->locale('id')->translatedFormat('d F Y') . ' – ' .
                  \Carbon\Carbon::parse(request('end_at'))->locale('id')->translatedFormat('d F Y')
                : \Carbon\Carbon::createFromFormat('Y-m', request('month', date('Y-m')))
                    ->locale('id')
                    ->translatedFormat('F Y');
        },
        'class' => 'text-center',
    ],
    [
        'label' => 'Jumlah hari kerja',
        'slot' => fn($employee) => $employee->schedulesTeachers->first()?->workdays_count ?: '-',
        'class' => 'text-center',
    ],
    [
        'label' => '',
        'slot' => function($employee) {
            $schedule = $employee->schedulesTeachers->first();
            $routes = [];
            $params = [];

            if ($employee->contract) {
                if ($schedule && \Gate::allows('show', $schedule)) {
                    $routes['show'] = 'hrms::service.teacher.schedule.show';
                    $params['show'] = [
                        'schedule' => $employee->id,
                        'start_at' => request('start_at'),
                        'end_at'   => request('end_at'),
                        'next'     => url()->full(),
                    ];

                    if (\Gate::allows('destroy', $schedule)) {
                        $routes['destroy'] = 'hrms::service.teacher.schedule.destroy';
                        $params['destroy'] = ['schedule' => $schedule->id, 'next' => url()->full()];
                    }

                } elseif (\Gate::allows('store', Modules\HRMS\Models\EmployeeSchedule::class)) {
                    $routes['create'] = 'hrms::service.teacher.schedule.create';
                    $params['create'] = [
                        'employee' => $employee->id,
                        'month'    => request('month', date('Y-m')),
                        'next'     => url()->full(),
                    ];
                }
            }

            return view('components.partial-actions', [
                'item'     => $schedule ?? $employee,
                'routes'   => $routes,
                'params'   => $params, // partial bisa gunakan route + params
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
            title="Daftar Karyawan"
            searchRoute="{{ route('hrms::service.teacher.schedule.index', ['search' => request('search')]) }}"
            :trash="$trashed"
        />
    </div>

    <div class="col-xl-4">
        <div class="card mb-3">
            <div class="card-body">
                <i class="mdi mdi-filter-outline"></i> Filter
            </div>
            <div class="card-body border-top">
                <form class="form-block" action="{{ route('hrms::service.teacher.schedule.index') }}" method="get">
                    <div class="mb-3">
                        <label class="form-label required">Periode</label>
                        <x-input-group required>
                            <div class="d-flex">
                                <x-col size="4">
                                    <x-button color="light" size="sm" type="button" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                        <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                        <span class="d-none d-sm-inline">Rentang Waktu</span>
                                    </x-button>
                                </x-col>

                                <x-col size="4">
                                    <x-input
                                        size="sm"
                                        type="date"
                                        name="start_at"
                                    />
                                </x-col>

                                <x-col size="4">
                                    <x-input
                                        size="sm"
                                        type="date"
                                        name="end_at"
                                    />
                                </x-col>
                            </div>
                        </x-input-group>
                    </div>
                    <div class="mb-3">
                        <x-input-group>
                            <x-label for="select-positions" value="Nama" />
                            <x-input name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                        </x-input-group>
                    </div>
                    <div class="mb-3">
                        <div class="form-check p-0">
                            <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                            <label class="form-check-label" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                        <a class="btn btn-light" href="{{ route('hrms::service.vacation.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
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

        <div class="card mb-3">
            <div class="card-header">
                <i class="mdi mdi-file-upload-outline"></i> Upload Jadwal
            </div>

            <form action="{{ route('hrms::service.teacher.teacher.schedule.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="start_at" value="{{ request('start_at') ?? '' }}" />
                    <input type="hidden" name="end_at" value="{{ request('end_at') ?? '' }}" />
                    <input type="hidden" name="smt_id" value="75" />
                    <div class="list-group list-group-flush border-top">
                        <div class="container">
                            <input class="list-group-item list-group-item-action py-3 form-control mb-2" type="file" name="scheduleFile" />
                            <select class="form-select" name="empl_category_id">
                                <option value="1">Jadwal Umum</option>
                                <option value="2">Jadwal Khusus</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <input type="submit" class="btn btn-sm btn-soft-primary" value="unggah jadwal" />
                </div>
            </form>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <i class="mdi mdi-file-download-outline"></i> Download Jadwal
            </div>

            <div class="p-2" style="margin-left:10px;">
                <a href="{{route('hrms::service.teacher.teacher.export')}}" class="btn btn-sm btn-soft-success">Download</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
            </div>
            <div class="list-group list-group-flush border-top">
                <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi mengajar</a>
            </div>
            <div class="card-body border-top">
                <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($start_at)) }}</small>
            </div>
        </div>
    </div>
</div>
@endsection
