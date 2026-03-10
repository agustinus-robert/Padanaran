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

@section('body-content')

@include('components.navbar-admin')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <x-table
                :isSearch="true"
                type="material"
                :data="$insurances"
                :columns="$columns"
                title="Daftar Asuransi"
                searchRoute="{{ route('core::company.insurances.manages.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />

        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah Asuransi</h6>
                </div>

                <div class="card-body">
                    <div class="display-5">{{ $insurances->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Total</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
            <div class="card">
                <div class="card-header">
                    Menu lainnya
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top border-light">
                        @can('store', Modules\Core\Models\CompanyBuilding::class)
                            <a class="list-group-item list-group-item-action disabled" href="{{ route('core::company.insurances.manages.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Tambah baru</a>
                        @endcan
                        <a class="list-group-item list-group-item-action text-danger disabled" href="{{ route('core::company.insurances.manages.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat item yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
