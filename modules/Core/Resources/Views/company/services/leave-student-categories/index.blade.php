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


@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-8">
            <x-table
                :isSearch="true"
                type="material"
                :data="$categories"
                :columns="$columns"
                title="Daftar Kategori Izin"
                searchRoute="{{ route('core::company.services.leave-student-categories.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah Kategori</h6>
                </div>

                <div class="card-body">
                    <div class="display-5">{{ $categories_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">total</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Menu lainnya</h6>
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top border-light">
                        @can('store', Modules\Core\Models\CompanyDepartment::class)
                            <a class="list-group-item list-group-item-action" href="{{ route('core::company.services.leave-student-categories.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat kategori baru</a>
                        @endcan
                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.services.leave-student-categories.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat kategori yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
