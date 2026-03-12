@extends('layouts.horizontal-layout')

@section('title', 'Core | Insurances | Manage')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('navtitle', 'Manage')

@php
    $trashed = false;
    $columns = [
    [
        'label' => 'Kode',
        'slot'  => fn($insurance) => $insurance->kd,
    ],
    [
        'label' => 'Nama',
        'slot'  => fn($insurance) => '<strong>'.$insurance->name.'</strong>',
    ],
    [
        'label' => 'Aksi',
        'slot'  => function($insurance) {
            if($insurance->trashed()) {
                return view('components.partial-actions', [
                    'item' => $insurance,
                    'routes' => [
                        'restore' => 'core::company.insurances.manages.restore',
                    ],
                    'trashed' => true,
                    'useModal' => false,
                ])->render();
            }

            return view('components.partial-actions', [
                'item' => $insurance,
                'routes' => [
                    'edit' => 'core::company.insurances.manages.show',
                    'destroy' => 'core::company.insurances.manages.destroy',
                ],
                'trashed' => false,
                'useModal' => false,
            ])->render();
        },
        'class' => 'text-end',
    ],
];

@endphp

@push('additional-content')
    @php
        $extraMenus = [];

        $extraMenus[] = [
            'label' => request('trash') ? 'Lihat item aktif' : 'Lihat item dihapus',
            'route' => route('core::company.insurances.manages.index', ['trash' => !request('trash')]),
            'icon' => request('trash') ? 'visibility' : 'delete',
            'class' => (request('trash') ? 'text-primary font-weight-bold' : 'text-danger') . ' disabled' 
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
                :data="$insurances"
                :columns="$columns"
                {{-- :createCan="['store', Modules\Core\Models\CompanyBuilding::class]"
                createRoute="{{ route('core::company.insurances.manages.create', ['next' => url()->current()]) }}" --}}
                title="Daftar Asuransi"
                searchRoute="{{ route('core::company.insurances.manages.index', ['search' => request('search')]) }}"
                :trash="$trashed"
                :count="$insurances->count()"
                countLabel="Jumlah Asuransi"
            />

        </div>
    </div>
</div>
@endsection
