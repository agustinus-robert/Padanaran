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


@section('body-content')
    @include('components.navbar-admin')

    <div class="row">
        <div class="col-md-7 col-lg-8">
             <x-table
                type="material"
                :data="$counselings"
                :columns="$columns"
                title="Konseling"
                searchRoute="{{ route('counseling::counselings.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah Konseling Saat Ini</h6>
                </div>

                <div class="card-body">
                    <div class="h1 text-muted text-right">
                        <i class="mdi mdi-file-cabinet float-right"></i>
                    </div>
                    <div class="text-value">{{ $counselings_count }}</div>
                    <small class="text-muted text-uppercounseling font-weight-bold">Total</small>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6>Lanjutan</h6>
                </div>

                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a class="list-group-item list-group-item-action text-muted" href="{{ route('counseling::counselings.create', ['next' => url()->full()]) }}"><i class="mdi mdi-file-plus-outline"></i> Input konseling baru</a>
                        <a class="list-group-item list-group-item-action text-muted" href="{{ route('counseling::manage.counseling.categories.index') }}"><i class="mdi mdi-file-cabinet"></i> Kelola kategori</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
