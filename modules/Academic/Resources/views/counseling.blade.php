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

@push('additional-content')
    <div class="card mb-3 shadow-none border">
        <div class="card-header pb-0 p-3 bg-transparent">
            <h6 class="mb-0 d-flex align-items-center text-sm">
                <i class="material-symbols-rounded me-2 text-dark">settings</i> Lanjutan
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="list-group list-group-flush">
                <a href="{{ route('counseling::manage.counseling.categories.index') }}" 
                   class="list-group-item list-group-item-action border-0 d-flex align-items-center px-0 py-2 text-sm text-primary">
                    <div class="icon icon-shape icon-xs bg-gradient-primary shadow text-center border-radius-sm me-2 d-flex align-items-center justify-content-center">
                        <i class="material-symbols-rounded text-white" style="font-size: 1rem;">inventory_2</i>
                    </div>
                    <span class="fw-bold">Kelola Kategori</span>
                </a>
            </div>
            
            <hr class="horizontal dark my-2">
            
            <div class="p-2 bg-gray-100 border-radius-sm">
                <p class="text-xxs text-muted mb-0">
                    <i class="material-symbols-rounded text-xs me-1">info</i>
                    Konfigurasi jenis dan kategori konseling akademik.
                </p>
            </div>
        </div>
    </div>
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
                    title="Data Konseling"
                    createRoute="{{ route('counseling::counselings.create', ['next' => url()->current()]) }}"
                    searchRoute="{{ route('academic::counselings.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                    :count="$counselings_count"
                    countLabel="Jumlah Perizinan Tertunda"
                />
            </div>
        </div>
    </div>
@endsection
