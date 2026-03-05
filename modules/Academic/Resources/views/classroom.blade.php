@extends('layouts.horizontal-layout')

@section('title', 'Data Murid - ')

@section('navtitle', 'Data Murid')

@section('breadcrumb')
    <li class="breadcrumb-item">Akademic</li>
    <li class="breadcrumb-item active">Data Murid</li>
@endsection

@push('nav')
@include('academic::layouts.includes.navbar-academic')
@endpush

@php
    $trashed = false;
    $columns = [
        [
            'field' => 'name',
            'label' => 'Nama Lengkap',
            'slot'  => fn($item) => $item->student->user->name
        ],
        [
            'field' => 'nisn',
            'label' => 'Nisn',
            'slot'  => fn($item) => $item->student->nisn
        ],
    ];
@endphp

@section('body-content')
    @include('components.navbar-admin')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <x-table
                type="material"
                :data="$students"
                :columns="$columns"
                title="Data Murid"
                searchRoute="{{ route('academic::classroom.index', ['academic' => request('academic')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-file-cabinet float-right"></i>
                    </div>
                    <div class="text-value">{{ $studentsCount }}</div>
                    <small class="text-muted text-uppercounseling font-weight-bold">Jumlah murid di kelas ini </small>
                </div>
            </div>
        </div>
    </div>
@endsection
