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


@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-8">
            <x-table
            :isSearch="true"
            type="material"
            :data="$categories"
            :columns="$columns"
            title="Daftar Kategori Cuti"
            searchRoute="{{ route('core::company.services.vacation-categories.index', ['search' => request('search')]) }}"
            :trash="$trashed"
        />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah Kategori</h6>
                </div>

                <div class="card-body">
                    <div class="display-4">{{ $categories_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah kategori</div>
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
                            <a class="list-group-item list-group-item-action" href="{{ route('core::company.services.vacation-categories.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat kategori baru</a>
                        @endcan
                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.services.vacation-categories.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat kategori yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
