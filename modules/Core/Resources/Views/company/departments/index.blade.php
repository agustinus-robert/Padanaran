@extends('layouts.horizontal-layout')

@section('title', 'Divisi | ')
@section('navtitle', 'Divisi')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
$trashed = request('trash') ? true : false;

$columns = [
    [
        'label' => 'Nama',
        'slot' => fn($department) => '
            <strong>'.$department->name.'</strong><br>
            <div class="text-muted">'.($department->grade->name ?? '-').'</div>
        ',
    ],

    [
        'label' => 'Visibilitas',
        'slot' => fn($department) => $department->is_visible
            ? '<i class="mdi mdi-eye-outline"></i>'
            : '<i class="mdi mdi-eye-off-outline text-danger"></i>',
        'class' => 'text-center',
    ],

    [
        'label' => 'Jumlah jabatan',
        'slot' => fn($department) => $department->positions_count.' jabatan',
    ],

    [
        'label' => 'Dibuat pada',
        'slot' => fn($department) => $department->created_at->diffForHumans(),
    ],

    [
        'label' => 'Aksi',
        'slot' => fn($department) => view('components.partial-actions', [
            'item' => $department,
            'routes' => [
                'edit' => 'core::company.departments.show',
                'destroy' => 'core::company.departments.destroy',
                'restore' => 'core::company.departments.restore',
            ],
            'trashed' => $department->trashed(),
            'useModal' => false,
        ])->render(),
        'class' => 'text-end',
    ],
];
@endphp


@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-8">
            <x-table
                type="material"
                :data="$departments"
                :columns="$columns"
                title="Departement"
                searchRoute="{{ route('core::company.departments.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Jumlah Devisi</h6>
                </div>

                <div class="card-body">
                    <div>
                        <div class="display-5">{{ $departments_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Total</div>
                    </div>
                    <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="text-black">Menu lainnya</h6>
                </div>

                <div class="list-group list-group-flush">
                    @can('store', Modules\Core\Models\CompanyDepartment::class)
                        <a class="list-group-item list-group-item-action" href="{{ route('core::company.departments.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat divisi baru</a>
                    @endcan
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.departments.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat divisi yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
