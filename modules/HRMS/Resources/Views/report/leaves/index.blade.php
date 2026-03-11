@extends('layouts.horizontal-layout')

@section('title', 'Laporan izin | ')
@section('navtitle', 'Laporan izin')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
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
            . '<div class="small text-muted">'.optional(optional($employee->contracts->last())->position)->position->name ?? '-'.'</div>',
        'raw' => true,
        'nowrap' => true,
    ],
];

foreach ($categories as $category) {
    $columns[] = [
        'label' => $category->name,
        'field' => function($employee) use ($category, $end_at) {
            $count = $employee->leaves
                ->filter(fn($l) => $l->hasAllApprovableResultIn('APPROVE') && ($l->ctg_id ?? null) == $category->id)
                ->flatMap(fn($l) => collect($l->dates)->pluck('d'))
                ->unique()
                ->filter(fn($date) => $end_at->gte(\Carbon\Carbon::parse($date)))
                ->count();
            return $count;
        },
        'class' => 'text-center',
    ];
}

$extra = [
    'row' => function($employee = null, $colspan = 1) {
        $name = $employee?->user?->name ?? 'Karyawan';
        return '<tr class="table-secondary"><td colspan="'.$colspan.'">Detail tambahan untuk '.$name.'</td></tr>';
    }
];
@endphp

@push('additional-content')
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_alt</i> Filter Laporan Izin
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::report.leaves.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode Izin</label>
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
                    <x-select id="select-positions" name="position_id" placeholder="Semua jabatan" />
                </div>

                <div class="input-group input-group-dynamic mb-3 {{ request('search') ? 'is-filled' : '' }}">
                    <label class="form-label">Cari Nama Karyawan</label>
                    <x-input type="text" name="search" value="{{ request('search') }}" onkeyup="searchTable()" />
                </div>

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" 
                       href="{{ route('hrms::report.leaves.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}" 
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
                <i class="material-symbols-rounded me-2">assignment_late</i> Ekspor Laporan
            </h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <button type="button" 
                        class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-3 text-sm" 
                        onclick="summaryExportExcel()">
                    <i class="material-symbols-rounded me-2 text-success">file_download</i> Unduh Rekap Data Izin (Excel)
                </button>
            </div>

            <hr class="horizontal dark my-2">

            <div class="bg-gray-100 border-radius-lg p-2">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    Laporan disaring berdasarkan tanggal: <br>
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
                        :data="$employees"
                        :columns="$columns"
                        title="Daftar karyawan aktif"
                        :extra="$extra"
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
    @include('hrms::report.leaves.components.summary-excel-script')
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
