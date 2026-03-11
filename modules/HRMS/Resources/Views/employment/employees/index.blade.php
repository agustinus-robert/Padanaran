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

@push('additional-content')
    @php
        $isTrash = request('trash');
        $navMenus = [
            [
                'label' => 'Lihat karyawan yang ' . ($isTrash ? 'tidak ' : '') . 'dihapus',
                'route' => route('hrms::employment.employees.index', ['trash' => !$isTrash]),
                'icon' => $isTrash ? 'visibility' : 'delete_outline',
                'class' => 'text-danger'
            ],
        ];
    @endphp

    <x-sidebar-card 
        title="Menu lainnya" 
        icon="more_vert" 
        :items="$navMenus" 
    />

    <div class="card mt-3">
        <div class="card-header pb-0 p-3">
            <div class="d-flex align-items-center">
                <h6 class="mb-0">Pembaruan data karyawan</h6>
            </div>
        </div>
        <div class="card-body p-3">
            <ul class="list-group">
                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2 pt-0">
                    <a class="btn btn-link text-dark dropdown-item mb-0 px-0 d-flex align-items-center" 
                       href="{{ route('hrms::employment.employees.template', ['next' => url()->full()]) }}">
                        <i class="material-symbols-rounded me-2">cloud_download</i>
                        Unduh template
                    </a>
                </li>
                
                <li class="list-group-item border-0 px-0">
                    <form action="{{ route('hrms::employment.employees.upload', ['next' => url()->full()]) }}" 
                          method="post" 
                          enctype="multipart/form-data" 
                          class="row g-2 align-items-center">
                        @csrf
                        <div class="col-8">
                            <div class="input-group input-group-static">
                                <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>
                                @if($errors->has('file'))
                                    <small class="text-danger text-xxs">{{ $errors->first('file') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-dark btn-sm mb-0 w-100">
                                <i class="material-symbols-rounded text-sm">upload</i>
                            </button>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">       
            <div class="col-md-12">
                <x-table
                    :isSearch="true"
                    type="material"
                    :data="$employees"
                    :columns="$columns"
                    :createCan="['store', Modules\HRMS\Models\Employee::class]"
                    title="Daftar Karyawan"
                    :createRoute="route('hrms::employment.employees.create', ['next' => url()->full()])"                
                    searchRoute="{{ route('hrms::employment.employees.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$employees_count"
                    countLabel="Jumlah Pertemuan"
                />
            </div>
        </div>
    </div>
@endsection
