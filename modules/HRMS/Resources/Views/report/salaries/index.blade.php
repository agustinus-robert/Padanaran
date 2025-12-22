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


@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid">
        <div class="col-md-8">
            <section>
                <x-table
                    :data="$employees"
                    :columns="$columns"
                    title="Daftar gaji karyawan"
                />
            </section>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::report.salaries.index') }}" method="get">
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
                            <a class="btn btn-light" href="{{ route('hrms::report.salaries.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top">
                        <button class="list-group-item list-group-item-action @if (!$employees->count()) disabled @endif py-3" onclick="exportExcel()"><i class="mdi mdi-file-excel-outline me-3"></i> Unduh laporan rincian penggajian</button>
                    </div>
                    <div class="card-body border-top">
                        <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($end_at)) }}</small>
                    </div>
                </div>
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
