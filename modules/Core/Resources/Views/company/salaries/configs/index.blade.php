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


@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid row">
        <div class="col-md-8">
             <x-table
                :isSearch="true"
                type="material"
                :data="$settings"
                :columns="$columns"
                title="Pengaturan Selip Gaji"
                searchRoute="{{ route('core::company.salaries.configs.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>
                        Jumlah Pengaturan
                    </h6>
                </div>

                <div class="card-body">
                    <div class="display-4">{{ $setting_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Total</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6>
                        Menu lainnya
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush border-top border-light">
                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.salaries.configs.index', ['next' => url()->current(), 'trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can"></i> Tampilkan setting {{ request('trash') ? 'tidak' : '' }} dihapus!</a>
                    </div>
                </div>
            </div>

            @can('store', Modules\Core\Models\CompanyPayrollSetting::class)
                <a class="btn btn-outline-primary w-100 text-primary d-flex align-items-center bg-white py-3 text-start" style="cursor: pointer;" href="{{ route('core::company.salaries.configs.create', ['next' => url()->current()]) }}">
                    <i class="mdi mdi-plus-outline me-3"></i>
                    <div>Tambah pengaturan <br> <small class="text-muted">Klik di sini untuk menambah pengaturan!</small></div>
                </a>
            @endcan
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
