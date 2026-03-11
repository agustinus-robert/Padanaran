@extends('layouts.horizontal-layout')

@section('title', 'Jadwal kerja | ')
@section('container-type', 'container-fluid px-5')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => 'Nama',
        'slot'  => fn ($employee) =>
            '<strong>'.$employee->user->name.'</strong>',
    ],
    [
        'label' => 'Hari Kerja',
        'slot'  => fn ($employee) =>
            $employee->schedulesDutyTeacher->first()?->workdays_count ?? 0,
        'class' => 'text-center',
    ],
    [
        'label' => '',
        'slot'  => function ($employee) {

            $schedule = $employee->schedulesDutyTeacher->first();
            $routes   = [];
            $params   = [];

            // SHOW
            if ($schedule && \Gate::allows('show', $employee)) {
                $routes['show'] = 'hrms::service.teacher.duty.show';
                $params['show'] = [
                    'duty'     => $employee->id,
                    'start_at' => request('start_at'),
                    'end_at'   => request('end_at'),
                    'next'     => url()->full(),
                ];
            }

            // DESTROY
            if ($schedule && \Gate::allows('destroy', $employee)) {
                $routes['destroy'] = 'hrms::service.teacher.duty.destroy';
                $params['destroy'] = [
                    'duty'     => $employee->id,
                    'start_at' => request('start_at'),
                    'end_at'   => request('end_at'),
                    'next'     => url()->full(),
                ];
            }

            return view('components.partial-actions', [
                'item'     => $employee,
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
            <h6 class="mb-0">Filter</h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.teacher.duty.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode pengajuan</label>
                    <x-date-range-select />
                </div>

                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Cari Nama Karyawan</label>
                    <x-input size="sm" type="text" name="search" value="{{ request('search') }}" onkeyup="searchTable()"></x-input>
                </div>

                <div class="form-check p-0 mb-3">
                    <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" {{ request('trashed') ? 'checked' : '' }}>
                    <label class="form-check-label text-xs" for="trashed">Tampilkan juga data dihapus</label>
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" href="{{ route('hrms::service.teacher.duty.index') }}">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <a class="btn btn-outline-primary w-100 d-flex align-items-center mb-3 bg-white py-3 text-start shadow-none" 
       style="border-style: dashed; text-transform: none; border-width: 2px;" 
       href="{{ route('hrms::service.teacher.duty.create', ['start_at' => request('start_at'), 'end_at' => request('end_at')]) }}">
        <i class="material-symbols-rounded me-3">calendar_month</i>
        <div style="line-height: 1.2;">
            <span class="d-block font-weight-bold">Absensi Piket Kolektif</span>
            <small class="text-xxs opacity-7">Kelola jadwal piket grup</small>
        </div>
    </a>

    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Laporan</h6>
        </div>
        <div class="card-body p-3">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled" href="javascript:;">
                    <i class="material-symbols-rounded me-2 text-info">description</i> Rekapitulasi izin
                </a>
            </div>
            
            <hr class="horizontal dark my-2">
            
            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    <i class="material-symbols-rounded text-xxs me-1">info</i>
                    Berdasarkan filter: <br>
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
                    :data="$employees"
                    :columns="$columns"
                    title="Kelola Jadwal Piket"
                    searchRoute="{{ route('hrms::service.teacher.duty.index', ['search' => request('search')]) }}"
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
