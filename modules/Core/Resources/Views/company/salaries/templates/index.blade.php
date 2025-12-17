@extends('layouts.horizontal-layout')

@section('title', 'Template slip gaji | ')
@section('navtitle', 'Template slip gaji')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $trashed = false;
    $columns = [
        [
            'label' => 'Nama Template',
            'slot' => fn($template) => $template->name,
            'class' => 'col-auto',
        ],
        [
            'label' => 'Aksi',
            'slot' => function($template) {
                if ($template->trashed()) {
                    return view('components.partial-actions', [
                        'item' => $template,
                        'routes' => [
                            'restore' => 'core::company.salaries.templates.restore',
                        ],
                        'trashed' => true,
                        'useModal' => false,
                    ])->render();
                }

                return view('components.partial-actions', [
                    'item' => $template,
                    'routes' => [
                        'edit' => 'core::company.salaries.templates.show',
                        'destroy' => 'core::company.salaries.templates.destroy',
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
                :data="$templates"
                :columns="$columns"
                title="Daftar Komponen Gaji"
                searchRoute="{{ route('core::company.salaries.templates.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>
                        Jumlah template slip gaji
                    </h6>
                </div>

                <div class="card-body">
                    <div class="display-4">{{ $template_count }}</div>
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
                        @can('store', Modules\Core\Models\CompanySalaryTemplate::class)
                            <a class="list-group-item list-group-item-action text-black" href="{{ route('core::company.salaries.templates.create') }}"><i class="mdi mdi-plus"></i> Tambah template</a>
                            <a class="list-group-item list-group-item-action text-black" href="{{ route('core::company.salaries.templates.sync') }}"><i class="mdi mdi-sync"></i> Sync default template</a>
                        @endcan
                        <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.salaries.templates.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat template slip gaji yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
