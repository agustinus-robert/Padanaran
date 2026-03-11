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

@push('additional-content')
    {{-- 1. KARTU FILTER --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_alt</i> Filter Rekap Mengajar
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::summary.teachings.index') }}" method="get">
                {{-- Periode --}}
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode Laporan</label>
                    <x-date-range-select />
                </div>

                {{-- Nama Karyawan --}}
                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Cari Nama Pengajar</label>
                    <x-input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        onkeyup="searchTable()" 
                    />
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" href="{{ route('hrms::summary.teachings.index') }}" title="Reset">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. KARTU LAPORAN & INFO --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">history_edu</i> Ekspor Laporan
            </h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled text-muted" href="javascript:;">
                    <i class="material-symbols-rounded me-2">file_download</i> Rekap Jam Mengajar (Excel)
                </a>
            </div>

            <hr class="horizontal dark my-2">

            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    Menampilkan data dari: <br>
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
                    title="Rekapitulasi Mengajar Guru"
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
