@extends('layouts.horizontal-layout')

@section('title', 'Daftar scanlog | ')
@section('navtitle', 'Daftar scanlog')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => '#',
        'slot'  => fn($scanlog, $loop) => $loop->iteration + $scanlogs->firstItem() - 1,
    ],
    [
        'label' => '',
        'slot'  => fn($scanlog) =>
            '<div class="rounded-circle" style="
                background: url(\'' . ($scanlog->employee?->user?->profile_avatar_path ?? '') . '\') center center no-repeat;
                background-size: cover;
                width: 32px;
                height: 32px;
            "></div>',
        'class' => 'text-center',
    ],
    [
        'label' => 'Nama',
        'slot'  => fn($scanlog) =>
            '<strong class="d-block">' . ($scanlog->employee?->user?->name ?? '-') . '</strong>
            <small class="text-muted">' . ($scanlog->employee->contract->position?->position->name ?? '') . '</small>',
    ],
    [
        'label' => 'Waktu scan',
        'slot'  => fn($scanlog) =>
            '<div class="text-center">' . $scanlog->created_at->format('H:i:s') . '</div>
            <small class="text-muted">' . $scanlog->created_at->isoFormat('LL') . '</small>',
        'class' => 'text-center',
    ],
    [
        'label' => 'IP',
        'slot'  => fn($scanlog) => $scanlog->ip,
        'class' => 'text-center',
    ],
    [
        'label' => 'Lokasi',
        'slot'  => fn($scanlog) => $locations[$scanlog->location] ?? '-',
        'class' => 'text-center small text-muted',
    ],
    [
        'label' => 'Lokasi presensi',
        'slot'  => function($scanlog) {
            if (!count($scanlog->latlong ?? [])) return '';
            $lat = $scanlog->latlong[0];
            $long = $scanlog->latlong[1];
            $coords = implode(', ', $scanlog->latlong);
            return '<a href="https://www.google.com/maps/@' . $lat . ',' . $long . ',20z" target="_blank" data-bs-toggle="tooltip" title="' . $coords . '">
                        <i class="mdi mdi-google-maps"></i>
                        <span class="text-dark">' . $coords . '</span>
                    </a>';
        },
        'class' => 'text-center small text-muted',
    ],
    [
        'label' => 'Agent',
        'slot'  => function($scanlog) {
            $icon = $scanlog->user_agent->is_desktop ? 'mdi-monitor' : 'mdi-cellphone';
            return '<div class="d-flex align-items-center">
                        <i class="mdi ' . $icon . ' text-muted me-3"></i>
                        <div>
                            <div>' . $scanlog->user_agent->browser . ' ' . $scanlog->user_agent->browser_version . '</div>
                            <small class="text-muted">' . $scanlog->user_agent->platform . ' ' . $scanlog->user_agent->platform_version . '</small>
                        </div>
                    </div>';
        },
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
                :data="$scanlogs"
                :columns="$columns"
                title="Kelola presensi karyawan"
                searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-xl-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Filter</h6>
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.attendance.scanlogs.index') }}" method="get">
                        <x-input-group :isRow="false" :isInputGroup="true" label="Periode">
                             <x-date-range-select />
                        </x-input-group>

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
                                class="form-control"
                                name="search"
                                placeholder="Cari nama karyawan ..."
                                value="{{ request('search') }}"
                                onkeyup="searchTable()"
                            />
                        </x-input-group>

                        <div class="d-flex justify-content-between">
                            <x-btn type="submit" varitant="dark">Terapkan</x-btn>
                            <a class="btn btn-light" href="{{ route('hrms::service.attendance.scanlogs.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            @can('store', \Modules\HRMS\Models\EmployeeScanLog::class)
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Input presensi manual</h6>
                    </div>
                    <div class="card-body">
                        <form class="form-block" action="{{ route('hrms::service.attendance.scanlogs.store') }}" method="post"> @csrf
                             <x-input-group :isRow="false" :isInputGroup="true" label="Nama Karyawan">
                                <x-select
                                    name="employee"
                                    :options="isset($employee) ? [
                                        [
                                            'value' => $employee->id,
                                            'label' => $employee->user->name,
                                            'selected' => true
                                        ]
                                    ] : []"
                                    required
                                />
                             </x-input-group>

                            <x-input-group :isRow="false" :isInputGroup="true" label="Tanggal & Waktu">
                                <x-input
                                    type="datetime-local"
                                    name="datetime"
                                    value="{{ old('datetime', now()->format('Y-m-d\TH:i')) }}"
                                    required
                                />
                            </x-input-group>

                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="btn-group">
                                        @foreach (Modules\Core\Enums\WorkLocationEnum::cases() as $v)
                                            <input class="btn-check" type="radio" id="location{{ $v->value }}" name="location" value="{{ $v->value }}" autocomplete="off" required @checked(old('location') == $v->value)>
                                            <label class="btn btn-outline-secondary text-dark" for="location{{ $v->value }}">{{ $v->name }}</label>
                                        @endforeach
                                    </div>
                                    @error('location')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <x-btn variant="dark">Simpan</x-btn>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Lanjutan</h6>
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action py-3" href="{{ route('hrms::service.attendance.manage.index') }}"><i class="mdi mdi-calendar-alert"></i> Kelola presensi</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Laporan</h6>
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi presensi</a>
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Data scanlog presensi</a>
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($end_at)) }}</small>
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
            document.getElementById('select-departments').addEventListener('change', renderPositions)
            renderPositions();
        })
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            new TomSelect('[name="employee"]', {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                load: function(q, callback) {
                    fetch('{{ route('api::hrms.employees.search') }}?q=' + encodeURIComponent(q))
                        .then(response => response.json())
                        .then(json => {
                            callback(json.employees);
                        }).catch(() => {
                            callback();
                        });
                }
            });
        });
    </script>
@endpush
