@extends('layouts.horizontal-layout')

@section('title', 'Pengaturan slip gaji | ')
@section('navtitle', 'Pengaturan slip gaji')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')


@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $trashed = false;

    $columns = [
        [
            'label' => 'Label',
            'slot'  => fn($setting) => "<strong>{$setting->key}</strong>",
        ],
        [
            'label' => 'Tipe',
            'slot'  => fn($setting) => $setting->az->label(),
        ],
        [
            'label' => 'Konfigurasi',
            'slot'  => fn($setting) => json_encode($setting->meta),
        ],
        [
            'label' => 'Aksi',
            'slot'  => function($setting) {
                return view('components.partial-actions', [
                    'item' => $setting,
                    'routes' => [
                        'edit' => 'core::company.salaries.configs.edit',
                        'destroy' => 'core::company.salaries.configs.destroy',
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
        $extraMenus = [
            [
                'label' => request('trash') ? 'Tampilkan setting aktif' : 'Tampilkan setting dihapus',
                'route' => route('core::company.salaries.configs.index', ['next' => url()->current(), 'trash' => !request('trash')]),
                'icon' => request('trash') ? 'visibility' : 'delete',
                'class' => request('trash') ? 'text-primary font-weight-bold' : 'text-danger'
            ]
        ];
    @endphp

    <x-sidebar-card 
        title="Menu Lainnya" 
        icon="tune" 
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
                    :data="$settings"
                    :columns="$columns"
                    title="Pengaturan Selip Gaji"
                    :createCan="['store', Modules\Core\Models\CompanyPayrollSetting::class]"
                    createRoute="{{ route('core::company.salaries.configs.create', ['next' => url()->current()]) }}"
                    searchRoute="{{ route('core::company.salaries.configs.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$setting_count"
                    countLabel="Jumlah selip Gaji"
                />
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
    <style type="text/css">
        .ts-wrapper {
            padding: 0 !important;
        }

        .ts-control {
            border: 1px solid hsla(0, 0%, 82%, .2) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        new TomSelect('[name="employee"]', {

        });
    </script>
@endpush
