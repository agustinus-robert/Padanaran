@extends('layouts.horizontal-layout')

@section('title', 'Kelola cuti | ')
@section('navtitle', 'Kelola cuti')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;

$columns = [
    [
        'label' => '#',
        'field' => fn($vacation, $loop) => $loop->iteration + $vacations->firstItem() - 1,
    ],
    [
        'label' => '',
        'field' => fn($vacation) => '<div class="rounded-circle" style="background: url(\''.$vacation->quota->employee->user->profile_avatar_path.'\') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>',
        'raw' => true,
        'class' => 'text-center',
        'width' => '10',
    ],
    [
        'label' => 'Nama',
        'field' => fn($vacation) => '<strong class="d-block">'.$vacation->quota->employee->user->name.'</strong>'
            . '<small class="text-muted">'.$vacation->quota->employee->contract->position->position->name ?? 'Belum ada perjanjian kerja'.'</small>',
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => 'Kategori',
        'field' => fn($vacation) => '<div>'.$vacation->quota->category->name.'</div>'
            . '<small class="text-muted">'.$vacation->description.'</small>',
        'raw' => true,
        'style' => 'min-width:200px;',
        'class' => 'py-3',
    ],
    [
        'label' => 'Tgl pengajuan',
        'field' => fn($vacation) => $vacation->created_at->formatLocalized('%d %B %Y'),
        'class' => 'small',
    ],
    [
        'label' => 'Tgl cuti/libur hari raya',
        'field' => function($vacation) {
            $cashable = isset(collect($vacation->dates)->first()['cashable']);
            if ($cashable) {
                return '<span class="badge bg-dark fw-normal user-select-none text-white">'.collect($vacation->dates)->count().' dikompensasikan</span>';
            }
            $dates = collect($vacation->dates)->take(3)->map(function($date) {
                $f = isset($date['f']) ? ' data-bs-toggle="tooltip" title="Sebagai freelancer"' : '';
                $c = isset($date['c']) ? ' text-decoration-line-through' : '';
                $icon = isset($date['f']) ? '<i class="mdi mdi-account-network-outline text-danger"></i> ' : '';
                return '<span class="badge bg-soft-secondary text-dark fw-normal user-select-none'.$c.'"'.$f.'>'.$icon.strftime('%d %B %Y', strtotime($date['d'])).'</span>';
            })->implode(' ');
            $remain = collect($vacation->dates)->count() - 3;
            if ($remain > 0) $dates .= ' <span class="badge text-dark fw-normal user-select-none">+'.$remain.' lainnya</span>';
            return $dates;
        },
        'raw' => true,
        'style' => 'min-width:200px;',
    ],
    [
        'label' => 'Status',
        'field' => fn($vacation) => view('portal::vacation.components.status', ['vacation' => $vacation])->render(),
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => '',
        'field' => function($vacation) {
            if ($vacation->trashed()) return '';
            $buttons = '';
            if ($vacation->hasApprovables()) {
                $buttons .= '<span data-bs-toggle="collapse" data-bs-target="#collapse-'.$vacation->id.'">';
                $buttons .= '<button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>';
                $buttons .= '</span> ';
            }
            $buttons .= '<a class="btn btn-soft-info btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat detail" href="'.route('hrms::service.vacation.manage.show', ['vacation'=>$vacation->id, 'next'=>url()->current()]).'"><i class="mdi mdi-eye-outline me-1"></i></a> ';
            $buttons .= '<a class="btn btn-soft-success btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Cetak dokumen (.pdf)" href="'.route('hrms::service.vacation.manage.print', ['vacation'=>$vacation->id]).'" target="_blank"><i class="mdi mdi-link me-1"></i></a>';
            return $buttons;
        },
        'raw' => true,
        'nowrap' => true,
        'class' => 'text-end py-1',
    ],
];
@endphp

@push('additonal-content')
<div class="card mb-3">
    <div class="card-header">
        <h6>Filter</h6>
    </div>

    <div class="card-body border-top">
        <form class="form-block" action="{{ route('hrms::service.vacation.manage.index') }}" method="get">
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
                <a class="btn btn-light" href="{{ route('hrms::service.vacation.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h6>Laporan</h6>
    </div>

    <div class="card-body">
        <div class="list-group list-group-flush border-top">
            <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi cuti</a>
        </div>
        <div class="card-body border-top">
            <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($start_at)) }}</small>
        </div>
    </div>
</div>
@endpush

@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-xl-12">
            <x-table
                :isSearch="true"
                type="material"
                :data="$vacations"
                :columns="$columns"
                title="Kelola Cuti Karyawan"
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
