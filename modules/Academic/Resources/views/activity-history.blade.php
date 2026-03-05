@extends('layouts.horizontal-layout')

@section('title', 'Data Murid - ')

@section('navtitle', 'Data Murid')

@section('breadcrumb')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item active">Aktivitas</li>
@endsection

@push('nav')
@include('academic::layouts.includes.navbar-academic')
@endpush

@php
    $trashed = false;
    $columns = [
        [
            'field' => 'type',
            'label' => 'Tipe',
            'slot'  => function($item) {
                if ($item->modelable_type === \Modules\Boarding\Models\BoardingStudentsLeave::class) {
                    return '<span class="badge bg-info">Izin Pulang</span>';
                } elseif ($item->modelable_type === \Modules\Boarding\Models\BoardingStudents::class) {
                    return '<span class="badge bg-success">Pondok</span>';
                }
                return '-';
            }
        ],
        [
            'field' => 'message',
            'label' => 'Keterangan',
            'slot'  => fn($item) => $item->message ?? '-'
        ],
    ];
@endphp

@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-7 col-lg-8">
            <x-table
                type="material"
                :data="$activityStudent"
                :columns="$columns"
                title="Riwayat Aktivitas"
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
                    <h1 class="text-value">{{ $activityStudentNum }}</h1>
                    <small class="text-muted text-uppercounseling font-weight-bold">Jumlah activity saat ini </small>
                </div>
            </div>
            {{-- <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::counselings.create', ['next' => url()->full()]) }}"><i class="mdi mdi-file-plus-outline"></i> Input konseling baru</a>
                    <a class="list-group-item list-group-item-action text-primary" href="{{ route('counseling::manage.counseling.categories.index') }}"><i class="mdi mdi-file-cabinet"></i> Kelola kategori</a>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
