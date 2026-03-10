@extends('layouts.horizontal-layout')

@section('title', 'Kategori cuti | ')
@section('navtitle', 'Kategori cuti')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
$trashed = request('trash') ? true : false;

$columns = [
    [
        'label' => 'Tipe',
        'slot' => fn($category) => '<div class="fw-bold" style="max-width:160px;">'.$category->name.'</div>',
    ],

    [
        'label' => 'Jenis cuti',
        'slot' => fn($category) => $category->type->label() ?? '-',
    ],

    [
        'label' => 'Kuota',
        'slot' => fn($category) => isset($category->meta->quota) ? $category->meta->quota.' hari' : '&#8734;',
        'class' => 'text-center',
    ],

    [
        'label' => 'Jenis inputan',
        'slot' => fn($category) => '<code class="d-block text-center">'.($category->meta?->fields ?? '').'</code>',
        'class' => 'text-center',
    ],

    [
        'label' => 'Freelance',
        'slot' => fn($category) => ($category->meta?->as_freelance ?? false) ? '<i class="mdi mdi-check"></i>' : '',
        'class' => 'text-center',
    ],

    [
        'label' => 'Aksi',
        'slot' => fn($category) => view('components.partial-actions', [
            'item' => $category,
            'routes' => [
                'edit' => 'core::company.services.vacation-categories.show',
                'destroy' => 'core::company.services.vacation-categories.destroy',
                'restore' => 'core::company.services.vacation-categories.restore',
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
            'route' => route('core::company.services.vacation-categories.index', ['trash' => !request('trash')]),
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
                createRoute="{{ route('core::company.services.vacation-categories.create', ['next' => url()->current()]) }}"
                title="Daftar Kategori Cuti"
                searchRoute="{{ route('core::company.services.vacation-categories.index', ['search' => request('search')]) }}"
                :trash="$trashed"
                :count="$categories_count"
                countLabel="Jumlah Cuti"
            />
            </div>
        </div>
    </div>
@endsection
