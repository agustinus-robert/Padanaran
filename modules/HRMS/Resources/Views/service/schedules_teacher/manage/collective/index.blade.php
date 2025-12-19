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

@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-xl-8">
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
        <div class="col-xl-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Filter</h6>
                </div>

                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.teacher.duty.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode pengajuan</label>
                            <x-date-range-select />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="select-positions">Nama</label>
                            <input class="form-control" name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                        </div>
                        <div class="mb-3">
                            <div class="form-check p-0">
                                <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                                <label class="form-check-label" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('hrms::service.teacher.duty.create') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <a class="btn btn-outline-primary w-100 d-flex text-primary mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('hrms::service.teacher.duty.create', [
                'start_at' => request('start_at'),
                'end_at' => request('end_at'),
            ]) }}">
                <i class="mdi mdi-calendar-multiple-check me-3"></i>
                <div>Absensi jadwal piket kolektif <br> <small style="opacity: 0.6;"></small></div>
            </a>

            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi izin</a>
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni mulai tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($start_at)) }}</small>
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
