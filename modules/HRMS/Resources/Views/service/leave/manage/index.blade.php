@extends('layouts.horizontal-layout')

@section('title', 'Kelola izin | ')
@section('navtitle', 'Kelola izin')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = null;
$columns = [
    [
        'label' => '',
        'field' => fn($leave) => '<div class="rounded-circle" style="background: url(\''.$leave->employee->user->profile_avatar_path.'\') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>',
        'raw' => true,
        'class' => 'text-center',
        'width' => '10',
    ],
    [
        'label' => 'Nama',
        'field' => fn($leave) => '<strong class="d-block">'.$leave->employee->user->name.'</strong>'
            . '<small class="text-muted">'.($leave->employee->contract->position->position->name ?? 'Belum ada perjanjian kerja').'</small>',
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => 'Kategori',
        'field' => fn($leave) => '<div>'.$leave->category->name.'</div>'
            . '<small class="text-muted">'.$leave->description.'</small>',
        'raw' => true,
        'style' => 'min-width:200px;',
        'class' => 'py-3',
    ],
    [
        'label' => 'Tgl pengajuan',
        'field' => fn($leave) => $leave->created_at->formatLocalized('%d %B %Y'),
        'class' => 'small',
    ],
    [
        'label' => 'Waktu izin',
        'field' => function($leave) {
            $dates = collect($leave->dates)->take(3)->map(function($date) {
                $c = isset($date['c']) ? ' text-decoration-line-through' : '';
                $icon = isset($date['f']) ? '<i class="mdi mdi-account-network-outline text-danger"></i> ' : '';
                $time = isset($date['t_s']) ? ' pukul '.$date['t_s'] : '';
                $time .= isset($date['t_e']) ? ' s.d. '.$date['t_e'] : '';
                return '<span class="badge bg-soft-secondary text-dark fw-normal user-select-none'.$c.'">'.$icon.strftime('%d %B %Y', strtotime($date['d'])).$time.'</span>';
            })->implode(' ');
            $remain = collect($leave->dates)->count() - 3;
            if ($remain > 0) $dates .= ' <span class="badge text-dark fw-normal user-select-none">+'.$remain.' lainnya</span>';
            return $dates;
        },
        'raw' => true,
        'style' => 'min-width:200px;',
    ],
    [
        'label' => 'Lampiran',
        'field' => fn($leave) => (isset($leave->attachment) && Storage::exists($leave->attachment))
            ? '<a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="'.Storage::url($leave->attachment).'" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>'
            : '',
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => 'Status',
        'field' => fn($leave) => view('portal::leave.components.status', ['leave' => $leave])->render(),
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => '',
        'field' => fn($leave) => view('components.partial-actions', [
            'model' => $leave,
            'viewRoute' => route('hrms::service.leave.manage.show', ['leave'=>$leave->id, 'next'=>url()->current()]),
            'printRoute' => route('hrms::service.leave.manage.print', ['leave'=>$leave->id]),
            'showApproveButton' => $leave->hasApprovables(),
        ])->render(),
        'raw' => true,
        'nowrap' => true,
        'class' => 'text-end py-1',
    ],
];
@endphp


@section('body-content')
    @include('components.navbar-admin')
    <div class="container-fluid row">
        <div class="col-xl-8">
            <x-table
                :isSearch="true"
                type="material"
                :data="$leaves"
                :columns="$columns"
                title="Kelola izin karyawan"
                {{-- searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}" --}}
                :trash="$trashed"
            />
        </div>
        <div class="col-xl-4">
            <div class="card mb-3">
                <div class="card-header">
                    Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.leave.manage.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode pengajuan</label>
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

                        <div class="card card-body border mb-3 justify checklist-item checklist-item-primary">
                            <div class="form-check">
                               <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                                <label class="form-check-label" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <x-btn variant="dark">Terapkan</x-btn>
                            <a class="btn btn-light" href="{{ route('hrms::service.leave.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
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
