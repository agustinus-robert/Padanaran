@extends('layouts.horizontal-layout')

@section('title', 'Data kasus - ')

@section('navtitle', 'Khasus')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@php
$trashed = false; // jika ada soft delete, bisa diatur sesuai kebutuhan
$columns = [
    [
        'label' => 'Nama',
        'slot' => fn($item) => '
            '.$item->semester->student->full_name.'<br>
            <small class="text-muted">'.$item->semester->classroom->name.'</small>
        ',
    ],
    [
        'label' => 'Kasus',
        'slot' => fn($item) => '
            '.$item->description.'<br>
            <small class="text-muted">'.$item->category->name.'</small>
        ',
        'style' => 'min-width:200px;',
    ],
    [
        'label' => 'Saksi',
        'slot' => fn($item) => $item->witness,
    ],
    [
        'label' => 'Poin',
        'slot' => fn($item) => '<strong class="text-center">'.$item->point.'</strong>',
        'class' => 'text-center',
    ],
    [
        'label' => '',
        'field' => 'actions',
        'slot' => fn($item) => view('components.partial-actions', [
            'item' => $item,
            'routes' => [
                'edit' => 'counseling::cases.edit',
                'destroy' => 'counseling::cases.destroy',
            ],
        ])->render(),
        'class' => 'text-right',
    ],
];
@endphp

@php
    $extraMenus = [
        [
            'label' => 'Kelola kategori',
            'route' => route('counseling::manage.cases.categories.index'),
            'icon' => 'category',
            'class' => 'text-muted'
        ],
        [
            'label' => 'Kelola deskripsi',
            'route' => route('counseling::manage.cases.descriptions.index'),
            'icon' => 'description',
            'class' => 'text-muted'
        ],
    ];
@endphp

@push('additional-content')
    <x-sidebar-card 
        title="Menu Lanjutan" 
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
                    type="material"
                    :data="$cases"
                    :columns="$columns"
                    title="Data Khasus"
                    createRoute="{{ route('counseling::cases.create', ['next' => url()->full()]) }}"                
                    searchRoute="{{ route('counseling::cases.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$cases_count"
                    countLabel="Jumlah Khasus"
                />
            </div>
        
        </div>
    </div>
@endsection
