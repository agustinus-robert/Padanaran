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

@php
    $extraMenus = [];

    $extraMenus[] = [
        'label' => request('trash') ? 'Lihat Divisi Aktif' : 'Lihat Divisi Terhapus',
        'route' => route('core::company.departments.index', ['trash' => !request('trash')]),
        'icon' => request('trash') ? 'visibility' : 'delete',
        'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
    ];
@endphp


@push('additional-content')
    <x-sidebar-card title="Menu Lainnya" icon="settings" :items="$extraMenus" />
@endpush


@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <x-table
                    type="material"
                    :data="$departments"
                    :columns="$columns"
                    title="Departement"
                    :createRoute="route('core::company.departments.create', ['next' => url()->current()])"                
                    searchRoute="{{ route('core::company.departments.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$departments_count"
                    countLabel="Jumlah Departement"
                />
            </div>
        </div>
    </div>
@endsection
