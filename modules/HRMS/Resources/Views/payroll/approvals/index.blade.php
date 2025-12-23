@extends('layouts.horizontal-layout')

@section('title', 'Persetujuan gaji | ')
@section('navtitle', 'Persetujuan gaji')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => '',
        'slot' => function ($employee) {
            return '
                <div class="rounded-circle"
                    style="
                        background: url(\''.$employee->user->profile_avatar_path.'\') center center no-repeat;
                        background-size: cover;
                        width: 32px;
                        height: 32px;">
                </div>
            ';
        },
        'raw' => true,
        'attributes' => ['width' => 10],
    ],
    [
        'label' => 'Nama',
        'slot' => function ($employee) {

            $position = $employee->position?->position
                ? '<div class="small text-muted">'.$employee->position->position->name.'</div>'
                : '';

            return '
                <div class="fw-bold">'.$employee->user->name.'</div>
                '.$position.'
            ';
        },
        'raw' => true,
        'attributes' => ['nowrap' => true],
    ],
    [
        'label' => 'Periode',
        'class' => 'text-center',
        'slot' => function ($employee) use ($start_at, $end_at) {

            $html = '<div class="d-flex align-items-center justify-content-center">';

            if (!$start_at->isSameDay($end_at)) {
                $html .= '
                    <div>
                        <h6 class="mb-0">'.$start_at->format('d-M').'</h6>
                        <small class="text-muted">'.$start_at->format('Y').'</small>
                    </div>
                    <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                ';
            }

            $html .= '
                <div>
                    <h6 class="mb-0">'.$end_at->format('d-M').'</h6>
                    <small class="text-muted">'.$end_at->format('Y').'</small>
                </div>
            </div>';

            return $html;
        },
        'raw' => true,
    ],
    [
        'label' => 'THP (Rp)',
        'class' => 'text-center',
        'slot' => function ($employee) {

            $salary = optional($employee->salaries->first())->amount;

            return $salary
                ? number_format($salary, 0, ',', '.')
                : '-';
        },
    ],
    [
        'label' => 'Tgl terbit',
        'class' => 'text-center',
        'slot' => function ($employee) {

            $date = optional($employee->salaries->first()?->validated_at);

            return $date
                ? '<span class="badge bg-light fw-normal text-dark">'
                    .$date->isoFormat('DD MMM YYYY').
                  '</span>'
                : '-';
        },
        'raw' => true,
    ],
    [
        'label' => 'Tgl persetujuan',
        'class' => 'text-center',
        'slot' => function ($employee) {

            $date = optional($employee->salaries->first()?->approved_at);

            return $date
                ? '<span class="badge bg-soft-success fw-normal text-success">'
                    .$date->isoFormat('DD MMM YYYY').
                  '</span>'
                : '-';
        },
        'raw' => true,
    ],
    [
        'label' => '',
        'class' => 'text-end',
        'slot' => function ($employee) {

            $salary = $employee->salaries->first();

            if (!$salary || !$salary->amount || !$salary->validated_at) {
                return '-';
            }

            $routes = [];
            $params = [];

            // PRINT / SHOW
            if (\Gate::allows('show', $salary)) {
                $routes['show'] = 'hrms::payroll.approvals.show';
                $params['show'] = [
                    'salary' => $salary->id,
                ];
            }

            // DETAIL / EDIT
            if (\Gate::allows('update', $salary)) {
                $routes['edit'] = 'hrms::payroll.approvals.edit';
                $params['edit'] = [
                    'salary' => $salary->id,
                    'next'   => url()->full(),
                ];
            }

            return view('components.partial-actions', [
                'item'     => $salary,
                'routes'   => $routes,
                'params'   => $params,
                'trashed'  => false,
                'useModal' => false,
            ]);
        },
    ],
];
@endphp

@section('body-content')
     @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-md-8">
            <section>
                <x-table
                :isSearch="true"
                type="material"
                :data="$employees"
                :columns="$columns"
                title="Daftar karyawan"
                {{-- searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}" --}}
                :trash="$trashed"
            />
            </section>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>Filter</h6>
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::payroll.approvals.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode</label>
                            <x-date-range-select />
                        </div>

                        <x-input-group :isRow="false" :isInputGroup="true" label="Departement">
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
                         </x-input-group>

                         <x-input-group :isRow="false" :isInputGroup="true" label="Jabatan">
                            <x-select
                                id="select-positions"
                                name="position"
                                placeholder="Semua jabatan"
                            />
                        </x-input-group>


                        <x-input-group :isRow="false" :isInputGroup="true" label="Nama">
                            <x-input
                                class="mb-3"
                                name="search"
                                placeholder="Cari nama karyawan ..."
                                value="{{ request('search') }}"
                                onkeyup="searchTable()"
                            />
                        </x-input-group>

                        <div class="d-flex justify-content-between">
                            <x-btn type="submit" variant="dark">Terapkan</x-btn>
                            <a class="btn btn-light" href="{{ route('hrms::payroll.approvals.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            let listDepartmentId = () => {
                [].slice.call(document.querySelectorAll('[name="department"] option:checked')).map((select) => {
                    let c = '';
                    if (select.dataset.positions) {
                        let possition = JSON.parse(select.dataset.positions);
                        for (i in possition) {
                            c += '<option value="' + i + '" ' + (('{{ old('possition_id', -1) }}' == i) ? ' selected' : '') + '>' + possition[i] + '</option>';
                        }
                    }
                    document.querySelector('[name="position_id"]').innerHTML = c.length ? c : '<option value>Semua jabatan</option>'
                })
            }

            [].slice.call(document.querySelectorAll('[name="department"]')).map((el) => {
                el.addEventListener('change', listDepartmentId);
            });
            listDepartmentId();
        });
    </script>
@endpush
