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
        'label' => 'Nama',
        'slot' => fn($category) => '
            <div class="fw-bold">'.$category->name.'</div>
            <small class="text-muted">'.($category->parent->name ?? '-').'</small>
        ',
    ],

    [
        'label' => 'Kuota',
        'slot' => fn($category) =>
            isset($category->meta->quota)
                ? $category->meta->quota.' hari'
                : '&#8734;',
        'class' => 'text-center',
    ],

    [
        'label' => 'Input waktu',
        'slot' => fn($category) =>
            '<code>'.($category->meta?->time_input ?? '').'</code>',
        'class' => 'text-center',
    ],

    [
        'label' => 'Dibuat pada',
        'slot' => fn($category) =>
            $category->created_at->diffForHumans(),
    ],

    [
        'label' => 'Aksi',
        'slot' => fn($category) => view('components.partial-actions', [
            'item' => $category,
            'routes' => [
                'edit' => 'core::company.services.leave-student-categories.show',
                'destroy' => 'core::company.services.leave-student-categories.destroy',
                'restore' => 'core::company.services.leave-student-categories.restore',
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
            'route' => route('core::company.services.leave-student-categories.index', ['trash' => !request('trash')]),
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
                    createRoute="{{ route('core::company.services.leave-student-categories.create', ['next' => url()->current()]) }}"
                    title="Daftar Kategori Izin"
                    searchRoute="{{ route('core::company.services.leave-student-categories.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$categories_count"
                    countLabel="Jumlah Kategori Izin"
                />
            </div>
        </div>
    </div>
@endsection
