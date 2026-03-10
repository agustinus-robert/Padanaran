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

@push('additional-content')
    @php
        $extraMenus = [];

        $extraMenus[] = [
            'label' => request('trash') ? 'Lihat kategori aktif' : 'Lihat kategori dihapus',
            'route' => route('core::company.services.outwork-categories.index', ['trash' => !request('trash')]),
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
                    :createCan="['store', Modules\Core\Models\CompanyOutworkCategory::class]"
                    createRoute="{{ route('core::company.services.outwork-categories.create', ['next' => url()->current()]) }}"
                    title="Daftar Kategori Insentif"
                    searchRoute="{{ route('core::company.services.outwork-categories.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$categories_count"
                    countLabel="Jumlah Kategori Insentif"
                />
            </div>

        </div>
    </div>
@endsection
