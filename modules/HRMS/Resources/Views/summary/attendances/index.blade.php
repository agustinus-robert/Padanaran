@extends('layouts.horizontal-layout')

@section('title', 'Rekapitulasi presensi | ')
@section('navtitle', 'Rekapitulasi presensi')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = null;
$columns = [
    [
        'label' => '',
        'field' => function($employee) {
            return '<div class="rounded-circle" style="background: url(\''.$employee->user->profile_avatar_path.'\') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>';
        },
        'raw' => true,
        'class' => 'text-center',
        'width' => '10',
    ],
    [
        'label' => 'Nama',
        'field' => function($employee) {
            return '<strong class="d-block">'.$employee->user->name.'</strong>'
                . '<small class="text-muted">'.($employee->contract->position?->position->name ?? '').'</small>';
        },
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => 'Periode',
        'field' => function($employee) use ($start_at, $end_at) {
            return '<div class="justify-content-center align-items-center d-flex">
                <div class="">
                    <h6 class="mb-0">'.$start_at->format('d-M').'</h6>
                    <small class="text-muted">'.$start_at->format('Y').'</small>
                </div>
                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                <div class="">
                    <h6 class="mb-0">'.$end_at->format('d-M').'</h6>
                    <small class="text-muted">'.$end_at->format('Y').'</small>
                </div>
            </div>';
        },
        'raw' => true,
        'class' => 'text-center',
        'nowrap' => true,
    ],
    [
        'label' => 'Hari kerja',
        'field' => function($employee) use ($summaries) {
            $sum = $summaries->where('empl_id', $employee->id)->sum('result.workdays');
            return $sum ? $sum : '<span class="text-muted">&dash;</span>';
        },
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => 'Kehadiran',
        'field' => function($employee) use ($summaries) {
            $sum = $summaries->where('empl_id', $employee->id)->sum('result.presence.wfo');
            return $sum ? $sum : '<span class="text-muted">&dash;</span>';
        },
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => '% Keterlambatan',
        'field' => function($employee) use ($summaries) {
            $summary = $summaries->where('empl_id', $employee->id);
            if($summary->count()) {
                $late = $summary->sum('result.late_total');
                $att = $summary->sum('result.attendance_total');
                return $att ? number_format(($late / $att) * 100, 2).'%' : '0%';
            }
            return '<span class="text-muted">&dash;</span>';
        },
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => '',
        'slot' => function($employee) use ($summaries, $start_at, $end_at) {
            $id = $summaries->where('empl_id', $employee->id)->pluck('id')->first();
            return view('components.partial-actions', [
                'item' => $employee,
                'routes' => [
                    'create' => route('hrms::summary.attendances.create', [
                        'employee' => $employee->id,
                        'start_at' => $start_at->format('Y-m-d'),
                        'end_at' => $end_at->format('Y-m-d'),
                        'next' => url()->full()
                    ]),
                    'show' => $id ? route('hrms::summary.attendances.show', [
                        'attendance' => $id,
                        'start_at' => $start_at->format('Y-m-d'),
                        'end_at' => $end_at->format('Y-m-d'),
                        'next' => url()->full()
                    ]) : null,
                ],
            ])->render();
        },
        'raw' => true,
        'class' => 'text-end py-1',
    ],
];
@endphp

@push('additional-content')
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_alt</i> Filter Summary
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::summary.attendances.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode Kehadiran</label>
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
                    <x-select id="select-positions" name="position" placeholder="Semua jabatan" />
                </div>

                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Cari Nama Karyawan</label>
                    <x-input type="text" name="search" value="{{ request('search') }}" onkeyup="searchTable()" />
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" href="{{ route('hrms::summary.attendances.index') }}" title="Reset">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Navigasi Lanjutan</h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm" 
                   href="{{ route('hrms::service.attendance.scanlogs.index') }}">
                    <i class="material-symbols-rounded me-2 text-primary">history</i> Lihat Daftar Scanlog
                </a>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">history_edu</i> Laporan Ekspor
            </h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled text-muted" href="javascript:;">
                    <i class="material-symbols-rounded me-2">file_download</i> Rekapitulasi presensi (Excel)
                </a>
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled text-muted" href="javascript:;">
                    <i class="material-symbols-rounded me-2">file_download</i> Data scanlog presensi (Excel)
                </a>
            </div>

            <hr class="horizontal dark my-2">

            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    Data rekap: <br>
                    <strong>{{ date('d M Y', strtotime($start_at)) }}</strong> s.d. <strong>{{ date('d M Y', strtotime($end_at)) }}</strong>
                </p>
            </div>
        </div>
    </div>
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <x-table
                    :isSearch="true"
                    type="material"
                    :data="$employees"
                    :columns="$columns"
                    title="Kelola izin karyawan"
                    {{-- searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}" --}}
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
