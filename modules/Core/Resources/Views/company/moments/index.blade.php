@extends('layouts.horizontal-layout')

@section('title', 'Hari libur | ')
@section('navtitle', 'Hari libur')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
$trashed = false;
$columns = [
    [
        'label' => 'Tipe',
        'slot' => fn ($moment) =>
            $moment->type->label() ?: '-',
    ],

    [
        'label' => 'Nama hari libur',
        'slot' => fn ($moment) => '
            <div class="fw-bold" style="max-width:160px">
                '.$moment->name.'
            </div>
        ',
    ],

    [
        'label' => 'Tanggal',
        'slot' => fn ($moment) =>
            strftime('%d %B %Y', strtotime($moment->date)),
        'class' => 'text-center',
    ],

    [
        'label' => 'Libur',
        'slot' => fn ($moment) => $moment->is_holiday
            ? '<i class="mdi mdi-check text-success"></i>'
            : '<i class="mdi mdi-close text-danger"></i>',
        'class' => 'text-center',
    ],

    [
        'label' => 'Aksi',
        'slot' => fn ($moment) => view('components.partial-actions', [
            'item' => $moment,
            'routes' => [
                'edit'    => 'core::company.moments.show',
                'destroy' => 'core::company.moments.destroy',
            ],
            'trashed' => $moment->trashed(),
            'useModal' => false,
        ])->render(),
        'class' => 'text-end',
    ],

];
@endphp

@push('additional-content')
    @php
        $extraMenus = [];

        if (auth()->user()->can('store', Modules\Core\Models\CompanyDepartment::class)) {
            $extraMenus[] = [
                'label' => 'Buat hari libur baru',
                'route' => route('core::company.moments.create', ['next' => url()->current()]),
                'icon' => 'add_circle',
                'class' => 'text-dark'
            ];
        }
    @endphp

    <x-sidebar-card 
        title="Menu Lainnya" 
        icon="settings" 
        :items="$extraMenus" 
    />

    <a class="card border-0 shadow-sm bg-white mb-4 text-decoration-none transition-all" 
    href="{{ route('core::company.moments.sync', ['next' => url()->current()]) }}"
    style="border-radius: 12px; transition: all 0.3s ease;">
        <div class="card-body p-3 d-flex align-items-center">
            <div class="icon-shape bg-gradient-light shadow-sm rounded-3 d-flex align-items-center justify-content-center me-3" 
                style="width: 48px; height: 48px; background-color: #f8f9fa;">
                <span class="material-symbols-rounded text-primary" style="font-size: 1.8rem;">sync_saved_locally</span>
            </div>
            
            <div class="flex-grow-1">
                <h6 class="mb-0 text-dark font-weight-bold" style="letter-spacing: -0.02rem;">Ambil Data Hari Libur</h6>
                <p class="mb-0 text-muted text-xs">Sinkronisasi otomatis via API</p>
            </div>

            <div class="text-secondary opacity-5">
                <span class="material-symbols-rounded">chevron_right</span>
            </div>
        </div>
    </a>

    <style>
        .transition-all:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            background-color: #fcfcfc !important;
        }
        .transition-all:hover .text-primary {
            transform: rotate(180deg);
            transition: transform 0.5s ease;
        }
    </style>
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <x-table
                    :isSearch="false"
                    type="material"
                    :data="$moments"
                    :columns="$columns"
                    title="Daftar hari libur"
                    {{-- searchRoute="{{ route('core::company.moments.index', ['search' => request('search')]) }}" --}}
                    :trash="$trashed"
                    {{-- :extra="[view('core::company.moments.extra-filter')->render()]" --}}
                    :count="$moments_count"
                    countLabel="Jumlah Hari Libur"
                />
            </div>
        </div>
    </div>
@endsection
