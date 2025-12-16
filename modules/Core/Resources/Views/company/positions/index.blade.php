@extends('layouts.horizontal-layout')

@section('title', 'Jabatan | ')
@section('navtitle', 'Jabatan')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
$trashed = request('trash') ? true : false;

$columns = [
    [
        'label' => 'Nama',
        'slot' => fn($position) => '
            <div class="fw-bold">'.$position->name.'</div>
            <small class="text-muted">'.$position->department->name.'</small>
        ',
    ],

    [
        'label' => 'Visibilitas',
        'slot' => fn($position) => $position->is_visible
            ? '<i class="mdi mdi-eye-outline"></i>'
            : '<i class="mdi mdi-eye-off-outline text-danger"></i>',
        'class' => 'text-center',
    ],

    [
        'label' => 'Tingkat',
        'slot' => fn($position) => '<span class="text-muted text-center">#'.$position->level->value.'</span>',
        'class' => 'text-center',
    ],

    [
        'label' => 'Diterapkan kepada',
        'slot' => fn($position) => $position->employee_positions_count.' pengguna',
    ],

    [
        'label' => 'Dibuat pada',
        'slot' => fn($position) => $position->created_at->diffForHumans(),
    ],

    [
        'label' => 'Aksi',
        'slot' => fn($position) => view('components.partial-actions', [
            'item' => $position,
            'routes' => [
                'edit' => 'core::company.positions.show',
                'destroy' => 'core::company.positions.destroy',
                'restore' => 'core::company.positions.restore',
            ],
            'trashed' => $position->trashed(),
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
                :data="$positions"
                :columns="$columns"
                title="Daftar jabatan"
                searchRoute="{{ route('core::company.positions.index', ['search' => request('search')]) }}"
                :trash="$trashed"
                :extra="[view('core::layouts.components.extra-filter', ['departments' => $departments])->render()]"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah jabatan</h6>
                </div>

                <div class="card-body">
                    <div>
                        <div class="display-4">{{ $positions_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Total</div>
                    </div>
                    <div><i class="mdi mdi-tag-outline mdi-48px text-light"></i></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Menu lainnya</h6>
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush border-top border-light">
                        @can('store', Modules\Core\Models\CompanyDepartment::class)
                            <a class="list-group-item list-group-item-action" href="{{ route('core::company.positions.create', ['next' => url()->current()]) }}"><i class="mdi mdi-plus"></i> Buat jabatan baru</a>
                        @endcan
                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.positions.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat jabatan yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
