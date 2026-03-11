@extends('layouts.horizontal-layout')

@section('title', 'Data konseling - ')

@section('navtitle', 'Konseling')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@php
    $trashed = false;
    $columns = [
        [
            'label' => 'Nama',
            'slot' => fn ($item) => '
                '.$item->semester->student->full_name.'<br>
                <small class="text-muted">
                    '.$item->semester->classroom->name.'
                </small>
            ',
        ],

        [
            'label' => 'Kasus',
            'slot' => fn ($item) => '
                '.$item->description.'<br>
                <small class="text-muted">
                    '.$item->category->name.'
                </small>
            ',
            'style' => 'min-width:200px;',
        ],

        [
            'label' => 'Tindak lanjut',
            'slot' => fn ($item) => $item->follow_up,
        ],

        [
            'label' => '',
            'field' => 'actions',
            'slot' => fn ($item) => view('components.partial-actions', [
                'item' => $item,
                'routes' => [
                    'edit' => 'counseling::counselings.edit',
                    'destroy' => 'counseling::counselings.destroy',
                ],
            ])->render(),
        ],
    ];
@endphp

@push('additional-content')
    @php
        $extraMenus = [
            [
                'label' => 'Kelola kategori',
                'route' => route('counseling::manage.counseling.categories.index'),
                'icon' => 'inventory_2',
                'class' => 'text-dark'
            ],
        ];
    @endphp

    <x-sidebar-card 
        title="Lanjutan" 
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
                    :data="$counselings"
                    :columns="$columns"
                    title="Konseling"
                    :createRoute="route('counseling::counselings.create', ['next' => url()->current()])"                
                    searchRoute="{{ route('counseling::counselings.index', ['search' => request('search')]) }}"
                    :trash="$trashed"
                    :count="$counselings_count"
                    countLabel="Jumlah Konseling"
                />
            </div>
        </div>
    </div>
@endsection
