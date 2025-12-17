@extends('layouts.horizontal-layout')

@section('title', 'Kategori Insentif | ')
@section('navtitle', 'Kategori Insentif')

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
        'label' => 'Keterangan',
        'slot' => fn($category) => $category->description ?? '-',
    ],

    [
        'label' => 'Tarif',
        'slot' => fn($category) => '<a class="text-success">Rp'.Str::money($category->price, 0, 'IDR').'</a>',
        'class' => 'text-center',
    ],

    [
        'label' => 'Tarif (jam kerja)',
        'slot' => fn($category) => !empty($category->meta?->in_working_hours_price)
            ? '<a class="text-danger">Rp'.Str::money($category->meta?->in_working_hours_price ?? 0, 0, 'IDR').'</a>'
            : '',
        'class' => 'text-center',
    ],

    [
        'label' => 'Persiapan',
        'slot' => fn($category) => !empty($category->meta?->prepareable)
            ? '<code><i class="text-success mdi mdi-check-all"></i></code>'
            : '',
        'class' => 'text-center',
    ],

    [
        'label' => 'Tarif tetap',
        'slot' => fn($category) => !empty($category->meta?->fixed)
            ? '<code><i class="text-success mdi mdi-check-all"></i></code>'
            : '',
        'class' => 'text-center',
    ],

    [
        'label' => 'Aksi',
        'slot' => fn($category) => view('components.partial-actions', [
            'item' => $category,
            'routes' => [
                'edit' => 'core::company.services.outwork-categories.show',
                'destroy' => 'core::company.services.outwork-categories.destroy',
                'restore' => 'core::company.services.outwork-categories.restore',
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
                title="Daftar Kategori Insentif"
                searchRoute="{{ route('core::company.services.outwork-categories.index', ['search' => request('search')]) }}"
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
                    <div class="small fw-bold text-secondary text-uppercase">Total</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Menu lainnya</h6>
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top border-light">
                        @can('store', Modules\Core\Models\CompanyOutworkCategory::class)
                            <a class="list-group-item list-group-item-action" href="{{ route('core::company.services.outwork-categories.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat kategori baru</a>
                        @endcan
                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.services.outwork-categories.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat kategori yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
