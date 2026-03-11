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

@push('additional-content')
    {{-- 1. KARTU FILTER --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 d-flex align-items-center">
                <i class="material-symbols-rounded me-2">filter_list</i> Filter Scanlog
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.attendance.scanlogs.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Periode</label>
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

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <x-btn type="submit" variant="dark" class="btn-sm mb-0 flex-grow-1">Terapkan</x-btn>
                    <a class="btn btn-light btn-sm mb-0" href="{{ route('hrms::service.attendance.scanlogs.index') }}">
                        <i class="material-symbols-rounded text-sm">restart_alt</i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. INPUT PRESENSI MANUAL --}}
    @can('store', \Modules\HRMS\Models\EmployeeScanLog::class)
    <div class="card mb-3 border border-primary border-1">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0 text-primary d-flex align-items-center">
                <i class="material-symbols-rounded me-2">edit_calendar</i> Presensi Manual
            </h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('hrms::service.attendance.scanlogs.store') }}" method="post"> 
                @csrf
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Nama Karyawan</label>
                    <x-select
                        name="employee"
                        :options="isset($employee) ? [['value' => $employee->id, 'label' => $employee->user->name, 'selected' => true]] : []"
                        required
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">Tanggal & Waktu</label>
                    <x-input type="datetime-local" name="datetime" value="{{ old('datetime', now()->format('Y-m-d\TH:i')) }}" required />
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold d-block">Lokasi Kerja</label>
                    <div class="btn-group w-100 shadow-none">
                        @foreach (Modules\Core\Enums\WorkLocationEnum::cases() as $v)
                            <input class="btn-check" type="radio" id="location{{ $v->value }}" name="location" value="{{ $v->value }}" required @checked(old('location') == $v->value)>
                            <label class="btn btn-outline-secondary btn-sm mb-0 text-dark" for="location{{ $v->value }}">{{ $v->name }}</label>
                        @endforeach
                    </div>
                    @error('location') <small class="text-danger text-xxs"> {{ $message }} </small> @enderror
                </div>

                <x-btn variant="primary" class="btn-sm w-100 mb-0">Simpan Presensi</x-btn>
            </form>
        </div>
    </div>
    @endcan

    {{-- 3. LANJUTAN & LAPORAN --}}
    <div class="card mb-3">
        <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Navigasi & Laporan</h6>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm" 
                   href="{{ route('hrms::service.attendance.manage.index') }}">
                    <i class="material-symbols-rounded me-2 text-info">settings_suggest</i> Kelola Presensi
                </a>
                <hr class="horizontal dark my-2">
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled text-muted" href="javascript:;">
                    <i class="material-symbols-rounded me-2">description</i> Rekapitulasi presensi
                </a>
                <a class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm disabled text-muted" href="javascript:;">
                    <i class="material-symbols-rounded me-2">analytics</i> Data scanlog presensi
                </a>
            </div>
            
            <div class="bg-gray-100 border-radius-lg p-2 mt-3">
                <p class="text-xxs text-muted mb-0 italic" style="line-height: 1.4;">
                    Data diambil per: <br>
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
                    :data="$scanlogs"
                    :columns="$columns"
                    title="Kelola presensi karyawan"
                    searchRoute="{{ route('hrms::service.attendance.schedules.index', ['search' => request('search')]) }}"
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
