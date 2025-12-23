@extends('layouts.horizontal-layout')

@section('title', 'Rekapitulasi kehadiran | ')
@section('navtitle', 'Rekapitulasi kehadiran')

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
            return '<div class="fw-bold text-truncate">'.$employee->user->name.'</div>'
                . '<div class="small text-muted text-truncate">'.($employee->contract->position?->position->name ?? '').'</div>';
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
        'width' => '1',
    ],
    [
        'label' => 'Beban Mengajar',
        'field' => function($employee) use ($summaries) {
            $summary = $summaries->where('empl_id', $employee->id);
            if ($summary->count()) {
                return $summary->first()->result->amount_total;
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
                    'create' => route('hrms::summary.teachings.create', [
                        'employee' => $employee->id,
                        'start_at' => $start_at->format('Y-m-d'),
                        'end_at' => $end_at->format('Y-m-d'),
                        'next' => url()->full()
                    ]),
                    'show' => $id ? route('hrms::summary.teachings.show', [
                        'teaching' => $id,
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

@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-xl-8">
            <x-table
                :isSearch="true"
                type="material"
                :data="$employees"
                :columns="$columns"
                title="Rekapitulasi Mengajar Guru"
                {{-- searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}" --}}
                :trash="$trashed"
            />
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::summary.teachings.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode pengajuan</label>
                            <x-date-range-select />
                        </div>

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
                            <a class="btn btn-light" href="{{ route('hrms::summary.teachings.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
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
