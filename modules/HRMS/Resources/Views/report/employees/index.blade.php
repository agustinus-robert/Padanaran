@extends('layouts.horizontal-layout')

@section('title', 'Laporan karyawan | ')
@section('navtitle', 'Laporan karyawan')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => '',
        'field' => fn($employee) => '<div class="rounded-circle" style="background: url(\''.$employee->user->profile_avatar_path.'\') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>',
        'raw' => true,
        'class' => 'text-center',
        'width' => '10',
    ],
    [
        'label' => 'Nama',
        'field' => fn($employee) => '<div class="fw-bold">'.$employee->user->name.'</div>'
            . '<div class="small text-muted">bergabung '.($employee->joined_at ? $employee->joined_at->diffForHumans() : '-').'</div>',
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => 'Kontrak',
        'field' => function($employee) {
            $contract = $employee->contracts->first();
            if (!$contract) return '-';
            return '<div><i class="mdi mdi-circle '.($contract->is_active ? 'text-success' : 'text-danger').'" style="font-size: 11pt;"></i> &nbsp; '.$contract->kd.'</div>'
                . '<small class="text-muted"><strong>'.$contract->start_at?->isoFormat('ll').'</strong> s.d. <strong>'.($contract->end_at?->isoFormat('ll') ?: 'tidak ditentukan').'</strong></small>';
        },
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => 'Jabatan',
        'field' => function($employee) {
            $contract = $employee->contracts->first();
            $position = $contract?->positions->last();
            return '<div>'.($position && $position->position ? $position->position->name : '-').'</div>'
                . '<div class="small text-muted">'.(optional(optional(optional($position)->position)->department)->name ?? '-').'</div>';
        },
        'raw' => true,
        'nowrap' => true,
    ],
];
@endphp

@push('additional-content')
    {{-- 1. KARTU FILTER --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_alt</i> Filter Laporan
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::report.employees.index') }}" method="get">
                {{-- Periode --}}
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode Bergabung</label>
                    <x-date-range-select />
                </div>

                {{-- Departemen --}}
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

                {{-- Jabatan --}}
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Jabatan</label>
                    <x-select id="select-positions" name="position" placeholder="Semua jabatan" />
                </div>

                {{-- Nama Karyawan --}}
                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Cari Nama Karyawan</label>
                    <x-input type="text" name="search" value="{{ request('search') }}" onkeyup="searchTable()" />
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" 
                       href="{{ route('hrms::report.employees.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}" 
                       title="Reset">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. KARTU EKSPOR LAPORAN --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">description</i> Ekspor Laporan
            </h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <button type="button" 
                        class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm {{ !$employees->count() ? 'disabled text-muted' : '' }}" 
                        onclick="summaryExportExcel()" {{ !$employees->count() ? 'disabled' : '' }}>
                    <i class="material-symbols-rounded me-2 text-success">file_download</i> Laporan Data Karyawan
                </button>
                
                <button type="button" 
                        class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm {{ !$employees->count() ? 'disabled text-muted' : '' }}" 
                        onclick="worktimeExportExcel()" {{ !$employees->count() ? 'disabled' : '' }}>
                    <i class="material-symbols-rounded me-2 text-info">history</i> Laporan Masa Kerja
                </button>
            </div>

            <hr class="horizontal dark my-2">

            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
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
            <div class="col-md-12 order-md-first">
                <section>
                    <x-table
                        :isSearch="true"
                        type="material"
                        :data="$employees"
                        :columns="$columns"
                        title="Daftar karyawan aktif"
                        :trash="$trashed"
                    />
                </section>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
    @include('hrms::report.employees.components.summary-excel-script')
    @include('hrms::report.employees.components.worktime-excel-script')
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
