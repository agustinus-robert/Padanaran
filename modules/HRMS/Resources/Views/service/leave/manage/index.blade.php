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

@push('additional-content')
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_alt</i> Filter Pengajuan Izin
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.leave.manage.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode Pengajuan</label>
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

                <div class="form-check p-0 mb-3">
                    <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" {{ request('trashed') ? 'checked' : '' }}>
                    <label class="form-check-label text-xs mb-0" for="trashed">
                        Tampilkan pengajuan dihapus
                    </label>
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" href="{{ route('hrms::service.leave.manage.index') }}" title="Reset">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">summarize</i> Laporan
            </h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled text-muted" href="javascript:;">
                    <i class="material-symbols-rounded me-2">file_download</i> Rekapitulasi izin
                </a>
            </div>

            <hr class="horizontal dark my-2">

            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    Laporan per tanggal: <br>
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
                    :data="$leaves"
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
