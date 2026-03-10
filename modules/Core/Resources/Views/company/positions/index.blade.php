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

@push('additional-content')
    @php
        $extraMenus = [
            [
                'label' => request('trash') ? 'Lihat Jabatan Aktif' : 'Lihat Jabatan Terhapus',
                'route' => route('core::company.positions.index', ['trash' => !request('trash')]),
                'icon' => request('trash') ? 'visibility' : 'delete',
                'class' => request('trash') ? 'bg-light text-primary font-weight-bold' : 'text-danger'
            ]
        ];
    @endphp

    <x-sidebar-card title="Menu Lainnya" icon="settings" :items="$extraMenus" />
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <x-table
                    :isSearch="false"
                    type="material"
                    :data="$positions"
                    :columns="$columns"
                    :createCan="['store', Modules\Core\Models\CompanyPosition::class]"
                    createRoute="{{ route('core::company.positions.create', ['next' => url()->current()]) }}"
                    title="Daftar jabatan"
                    {{-- searchRoute="{{ route('core::company.positions.index', ['search' => request('search')]) }}" --}}
                    :trash="$trashed"
                    :extra="[view('core::layouts.components.extra-filter', ['departments' => $departments])->render()]"
                    :count="$positions_count"
                    countLabel="Jumlah Posisi"
                />
            </div>
        </div>
    </div>
@endsection
