@extends('layouts.horizontal-layout')

@section('title', 'Karyawan | ')
@section('navtitle', 'Karyawan')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => '',
        'slot' => fn($employee) => '<div class="rounded-circle" style="background: url(\''.$employee->user->profile_avatar_path.'\') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>',
        'class' => 'text-center',
    ],

    [
        'label' => 'Nama',
        'slot' => fn($employee) => '<strong>'.($employee->user->name ?? $employee->userProfile->name).'</strong><br><small class="text-muted">bergabung '.($employee->joined_at?->diffForHumans() ?: '-').'</small>',
    ],

    [
        'label' => 'Perjanjian kerja',
        'slot' => fn($employee) => $employee->contract
            ? '<i class="mdi mdi-circle '.($employee->contract->is_active ? 'text-success' : 'text-danger').'"></i> &nbsp; '.$employee->contract->kd
            : '-',
        'class' => 'text-center',
    ],

    [
        'label' => 'Jabatan saat ini',
        'slot' => fn($employee) => isset($employee->contract)
            ? ($employee->contract->positions->isNotEmpty()
                ? $employee->contract->positions->map(fn($pos) => '<span class="badge bg-dark fw-normal">'.$pos->position?->name.'</span>')->implode(' ')
                : (!$employee->trashed()
                    ? '<a class="btn btn-link p-0" href="'.route("hrms::employment.contracts.positions.create", ["contract" => $employee->contract->id, "next" => url()->full()]).'"><i class="mdi mdi-plus"></i> Tambah jabatan</a>'
                    : '-')
              )
            : '-',
    ],

    [
        'label' => 'Aksi',
        'slot' => fn($employee) => view('components.partial-actions', [
            'item' => $employee,
            'routes' => [
                'show'    => 'hrms::employment.employees.show',
                'destroy' => 'hrms::employment.employees.destroy',
            ],
            'trashed' => $employee->trashed(),
            'useModal' => false,
        ])->render(),
        'class' => 'text-end',
    ],
];
@endphp

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">       
            <div class="col-md-8">
                    <x-table
                        :isSearch="true"
                        type="material"
                        :data="$employees"
                        :columns="$columns"
                        title="Daftar Karyawan"
                        searchRoute="{{ route('hrms::employment.employees.index', ['search' => request('search')]) }}"
                        :trash="$trashed"
                    />
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Jumlah karyawan</h6>
                    </div>

                    <div class="card-body">
                        <div class="display-4">{{ $employees_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Total</div>
                    </div>
                    <div><i class="mdi mdi-account-group-outline mdi-48px text-light"></i></div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Menu lainnya</h6>
                    </div>

                    <div class="card-body">
                        <div class="list-group list-group-flush border-top border-light">
                            @can('store', Modules\HRMS\Models\Employee::class)
                                <a class="list-group-item list-group-item-action" href="{{ route('hrms::employment.employees.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah karyawan baru</a>
                            @endcan
                            <a class="list-group-item list-group-item-action text-danger" href="{{ route('hrms::employment.employees.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat karyawan yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6>Pembaruan data karyawan</h6>
                    </div>

                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('hrms::employment.employees.template', ['next' => url()->full()]) }}">
                                <i class="mdi mdi-cloud-download-outline me-2"></i> Unduh template
                            </a>

                            <form class="list-group-item m-0 p-3" action="{{ route('hrms::employment.employees.upload', ['next' => url()->full()]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <x-input-group :isRow="true" required>
                                    <x-col size="8">
                                        <x-input-file
                                            name="file"
                                            :error="$errors->first('file')"
                                            accept=".xls,.xlsx"
                                        />
                                    </x-col>

                                    <x-col size="4">
                                        <x-btn type="submit" variant="dark"><i class="mdi mdi-upload"></i> Upload</x-btn>
                                    </x-col>
                                </x-input-group>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
