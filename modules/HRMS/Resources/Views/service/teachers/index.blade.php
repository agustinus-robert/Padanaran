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

@push('additional-content')
    {{-- 1. KARTU FILTER --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_list</i> Filter
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.teacher.duty.index') }}" method="get">
                {{-- Periode Menggunakan Komponen Khusus --}}
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode pengajuan</label>
                    <x-date-range-select />
                </div>

                {{-- Search dengan Style Dynamic --}}
                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Cari Nama Karyawan</label>
                    <x-input size="sm" type="text" name="search" value="{{ request('search') }}" onkeyup="searchTable()"></x-input>
                </div>

                <div class="form-check p-0 mb-3">
                    <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" {{ request('trashed') ? 'checked' : '' }}>
                    <label class="form-check-label text-xs" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" href="{{ route('hrms::service.teacher.duty.index') }}" title="Reset">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. KARTU UPLOAD JADWAL (Tetap Dipertahankan) --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center text-sm">
                <i class="material-symbols-rounded me-2">upload_file</i> Upload Jadwal
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.teacher.teacher.schedule.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="smt_id" value="75">
                
                <div class="input-group input-group-static mb-2">
                    <input type="file" name="scheduleFile" class="form-control" required>
                </div>
                
                <div class="input-group input-group-static mb-3">
                    <select class="form-control" name="empl_category_id">
                        <option value="1">Jadwal Umum</option>
                        <option value="2">Jadwal Khusus</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-info btn-sm mb-0 w-100">Unggah</button>
            </form>
        </div>
    </div>

    {{-- 3. KARTU DOWNLOAD & LAPORAN --}}
    <div class="card">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center text-sm">
                <i class="material-symbols-rounded me-2">description</i> Dokumen
            </h6>
        </div>
        <div class="card-body p-3 pt-0">
            <a href="{{ route('hrms::service.teacher.teacher.export') }}" class="btn btn-link text-success btn-sm w-100 mb-0 d-flex align-items-center px-0">
                <i class="material-symbols-rounded me-2">download</i> Download Jadwal
            </a>
            <hr class="horizontal dark my-2">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 text-xs disabled" href="javascript:;">
                    <i class="material-symbols-rounded me-2">grid_on</i> Rekapitulasi mengajar
                </a>
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
                title="Daftar Karyawan"
                searchRoute="{{ route('hrms::service.teacher.schedule.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
    </div>
</div>
@endsection
