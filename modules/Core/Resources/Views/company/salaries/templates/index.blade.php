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

@push('additional-content')
    @php
        $extraMenus = [];

        $extraMenus[] = [
            'label' => request('trash') ? 'Lihat template aktif' : 'Lihat template dihapus',
            'route' => route('core::company.salaries.templates.index', ['trash' => !request('trash')]),
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
                    :data="$templates"
                    :columns="$columns"
                    :createCan="['store', Modules\Core\Models\CompanyBuilding::class]"
                    createRoute="{{ route('core::company.salaries.templates.create') }}"
                    title="Template Selip Gaji"
                    searchRoute="{{ route('core::company.salaries.templates.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$template_count"
                    countLabel="Jumlah selip Gaji"
                />
            </div>
        </div>
    </div>
@endsection
