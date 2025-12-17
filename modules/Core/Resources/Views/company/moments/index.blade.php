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


@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-8">
            <x-table
                :isSearch="false"
                type="material"
                :data="$moments"
                :columns="$columns"
                title="Daftar hari libur"
                searchRoute="{{ route('core::company.moments.index', ['search' => request('search')]) }}"
                :trash="$trashed"
                :extra="[view('core::company.moments.extra-filter')->render()]"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah hari libur tahun {{ date('Y') }}</h6>
                </div>

                <div class="card-body">
                    <div class="display-5">{{ $moments_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Total</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Menu lainnya</h6>
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top border-light">
                        @can('store', Modules\Core\Models\CompanyDepartment::class)
                            <a class="list-group-item list-group-item-action" href="{{ route('core::company.moments.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat hari libur baru</a>
                        @endcan
                    </div>
                </div>
            </div>
            <a class="btn btn-outline-primary w-100 text-primary d-flex align-items-center bg-white py-3 text-start" style="cursor: pointer;" href="{{ route('core::company.moments.sync', ['next' => url()->current()]) }}">
                <i class="mdi mdi-progress-upload me-3"></i>
                <div>Ambil data hari libur <br> <small class="text-muted">Daftar hari libur diambil dari API</small></div>
            </a>
        </div>
    </div>
@endsection
