@extends('layouts.horizontal-layout')

@section('title', 'Penerbitan gaji | ')
@section('navtitle', 'Penerbitan gaji')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$columns = [
    [
        'label' => '#',
        'field' => function($employee, $loopIndex, $firstItem) {
            return $loopIndex + $firstItem - 1;
        },
        'class' => 'text-center',
        'width' => '10',
    ],
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
            . (isset($employee->position?->position)
                ? '<div class="small text-muted">'.$employee->position->position->name.'</div>'
                : ''
            ),
        'raw' => true,
        'nowrap' => true,
    ],
    [
        'label' => 'Periode',
        'field' => function($employee) use ($start_at, $end_at) {
            $html = '<div class="justify-content-center align-items-center d-flex">';
            if (!$start_at->isSameDay($end_at)) {
                $html .= '<div class="">
                            <h6 class="mb-0">'.$start_at->format('d-M').'</h6>
                            <small class="text-muted">'.$start_at->format('Y').'</small>
                          </div>
                          <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>';
            }
            $html .= '<div class="">
                        <h6 class="mb-0">'.$end_at->format('d-M').'</h6>
                        <small class="text-muted">'.$end_at->format('Y').'</small>
                      </div>';
            $html .= '</div>';
            return $html;
        },
        'raw' => true,
        'class' => 'text-center',
        'nowrap' => true,
    ],
    [
        'label' => 'Jumlah Gaji',
        'field' => fn($employee) => isset($employee->salaries->first()->amount)
            ? number_format($employee->salaries->first()->amount, 0, ',', '.')
            : '-',
        'class' => 'text-center',
    ],
    [
        'label' => 'Tervalidasi',
        'field' => fn($employee) => isset($employee->salaries->first()->validated_at)
            ? '<span class="badge bg-light fw-normal text-dark">'.$employee->salaries->first()->validated_at->isoFormat('DD MMM YYYY').'</span>'
            : '-',
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => 'Disetujui',
        'field' => fn($employee) => isset($employee->salaries->first()->approved_at)
            ? '<span class="badge bg-soft-info fw-normal text-info">'.$employee->salaries->first()->approved_at->isoFormat('DD MMM YYYY').'</span>'
            : '-',
        'raw' => true,
        'class' => 'text-center',
    ],
    [
        'label' => 'Dilepas',
        'field' => fn($employee) => isset($employee->salaries->first()->released_at)
            ? '<span class="badge bg-soft-success fw-normal text-success">'.$employee->salaries->first()->released_at->isoFormat('DD MMM YYYY').'</span>'
            : '-',
        'raw' => true,
        'class' => 'text-center',
    ],
];
@endphp

@push('additional-content')
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_alt</i> Filter Laporan Gaji
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::report.salaries.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold text-uppercase">Periode Penggajian</label>
                    <x-date-range-select />
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold text-uppercase">Departemen</label>
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
                    <label class="form-label text-xs font-weight-bold text-uppercase">Jabatan</label>
                    <x-select id="select-positions" name="position" placeholder="Semua jabatan" />
                </div>

                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Cari Nama Karyawan</label>
                    <x-input type="text" name="search" value="{{ request('search') }}" onkeyup="searchTable()" />
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" 
                       href="{{ route('hrms::report.salaries.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}" 
                       title="Reset">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">payments</i> Ekspor Laporan
            </h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <button type="button" 
                        class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-3 text-sm {{ !$employees->count() ? 'disabled text-muted' : '' }}" 
                        onclick="exportExcel()"
                        {{ !$employees->count() ? 'disabled' : '' }}>
                    <i class="material-symbols-rounded me-2 text-success">account_balance_wallet</i> Rincian Penggajian (Excel)
                </button>
            </div>

            <hr class="horizontal dark my-2">

            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    Laporan mencakup periode: <br>
                    <strong>{{ $start_at->format('d M Y') }}</strong> s.d. <strong>{{ $end_at->format('d M Y') }}</strong>
                </p>
            </div>
        </div>
    </div>
@endpush

@section('body-content')
    @include('components.navbar-admin')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <section>
                    <x-table
                        :data="$employees"
                        :columns="$columns"
                        title="Daftar gaji karyawan"
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
    @include('hrms::report.salaries.components.excel-script')
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
