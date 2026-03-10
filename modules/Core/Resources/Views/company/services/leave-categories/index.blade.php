@extends('layouts.horizontal-layout')

@section('title', 'Kategori izin | ')
@section('navtitle', 'Kategori izin')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
$trashed = request('trash') ? true : false;

$columns = [
    [
        'label' => 'Nama kategori',
        'slot' => fn($category) => '<div class="fw-bold" style="max-width:160px;">'.$category->name.'</div>',
    ],

    [
        'label' => 'Parent',
        'slot' => fn($category) => $category->parent->name ?? '-',
    ],

    [
        'label' => 'Kuota',
        'slot' => fn($category) => data_get($category->meta, 'quota') !== null ? data_get($category->meta, 'quota').' hari' : '&#8734;',
        'class' => 'text-center',
    ],

    [
        'label' => 'Inputan waktu',
        'slot' => fn($category) => '<code>'.data_get($category->meta, 'time_input', '').'</code>',
    ],

    [
        'label' => 'Aksi',
        'slot' => fn($category) => view('components.partial-actions', [
            'item' => $category,
            'routes' => [
                'edit' => 'core::company.services.leave-categories.show',
                'destroy' => 'core::company.services.leave-categories.destroy',
                'restore' => 'core::company.services.leave-categories.restore',
            ],
            'trashed' => $category->trashed(),
            'useModal' => false,
        ])->render(),
        'class' => 'text-end',
    ],
];
@endphp


@push('additional-content')
    @php
        $extraMenus = [];

        $extraMenus[] = [
            'label' => request('trash') ? 'Lihat kategori aktif' : 'Lihat kategori dihapus',
            'route' => route('core::company.services.leave-categories.index', ['trash' => !request('trash')]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => request('trash') ? 'text-primary font-weight-bold' : 'text-danger'
        ];
    @endphp

    <x-sidebar-card 
        title="Menu Lainnya" 
        icon="settings" 
        :items="$extraMenus" 
    />
@endpush

@section('body-content')

@include('components.navbar-admin')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <x-table
                :isSearch="true"
                type="material"
                :data="$categories"
                :columns="$columns"
                :createCan="['store', Modules\Core\Models\CompanyDepartment::class]"
                createRoute="{{ route('core::company.services.leave-categories.create', ['next' => url()->current()]) }}"
                title="Daftar Kategori Izin Karyawan"
                searchRoute="{{ route('core::company.services.leave-categories.index', ['search' => request('search')]) }}"
                :trash="$trashed"
                :count="$categories_count"
                countLabel="Jumlah Kategori Izin Siswa"
            />
        </div>
    </div>
</div>
@endsection
