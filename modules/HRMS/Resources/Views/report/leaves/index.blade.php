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
    'row' => function($employee, $colspan) {
        return '<tr class="table-secondary"><td colspan="'.$colspan.'">Detail tambahan untuk '.$employee->user->name.'</td></tr>';
    }
];
@endphp


@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Filter</h6>
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::report.leaves.index') }}" method="get">
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
                                name="position_id"
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
                            <a class="btn btn-light" href="{{ route('hrms::report.leaves.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}"><i class="mdi mdi-refresh"></i> Reset</a>
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
                        <button class="list-group-item list-group-item-action" onclick="summaryExportExcel()"><i class="mdi mdi-file-excel-outline me-3"></i> Unduh laporan data izin</button>
                    </div>
                    <div class="card-body border-top">
                        <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($end_at)) }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 order-md-first">
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
