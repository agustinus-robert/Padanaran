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

@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-7 col-lg-8">
            <x-table
                type="material"
                :data="$cases"
                :columns="$columns"
                title="Data Khasus"
                searchRoute="{{ route('counseling::cases.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah Khasus Saat Ini
                </div>

                <div class="card-body">
                    <div class="text-value">{{ $cases_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total</small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-muted" href="{{ route('counseling::cases.create', ['next' => url()->full()]) }}"><i class="mdi mdi-briefcase-plus-outline"></i> Input kasus baru</a>
                    <a class="list-group-item list-group-item-action text-muted" href="{{ route('counseling::manage.cases.categories.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola kategori</a>
                    <a class="list-group-item list-group-item-action text-muted" href="{{ route('counseling::manage.cases.descriptions.index') }}"><i class="mdi mdi-briefcase-outline"></i> Kelola deskripsi</a>
                </div>
            </div>
        </div>
    </div>
@endsection
