@extends('layouts.horizontal-layout')

@section('title', 'Data Konseling Murid - ')

@section('navtitle', 'Data Konseling Murid')

@section('breadcrumb')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item active">Konseling Murid</li>
@endsection

@push('nav')
@include('academic::layouts.includes.navbar-academic')
@endpush

@php
    $trashed = false;
    $columns = [
        [
            'field' => 'no',
            'label' => 'No',
            'class' => 'align-middle',
            'slot'  => fn($item) => ($counselings->getCollection()->search($item) + $counselings->firstItem())
        ],
        [
            'field' => 'name',
            'label' => 'Nama',
            'slot'  => fn($item) => '
                ' . $item->semester->student->full_name . ' <br>
                <small class="text-muted">' . $item->semester->classroom->name . '</small>
            '
        ],
        [
            'field' => 'case',
            'label' => 'Kasus',
            'slot'  => fn($item) => '
                ' . $item->description . ' <br>
                <small class="text-muted">' . $item->category->name . '</small>
            '
        ],
        [
            'field' => 'follow_up',
            'label' => 'Tindak lanjut',
            'slot'  => fn($item) => $item->follow_up ?? '-'
        ],
    ];
@endphp

@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-7 col-lg-8">
            <x-table
                type="material"
                :data="$counselings"
                :columns="$columns"
                title="Data Konseling"
                searchRoute="{{ route('academic::counselings.index', ['academic' => request('academic')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-file-cabinet float-right"></i>
                    </div>
                    <div class="text-value">{{ $counselings_count }}</div>
                    <small class="text-muted text-uppercounseling font-weight-bold">Jumlah konseling saat ini </small>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::counselings.create', ['next' => url()->full()]) }}"><i class="mdi mdi-file-plus-outline"></i> Input konseling baru</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.counseling.categories.index') }}"><i class="mdi mdi-file-cabinet"></i> Kelola kategori</a>
                </div>
            </div>
        </div>
    </div>
@endsection
