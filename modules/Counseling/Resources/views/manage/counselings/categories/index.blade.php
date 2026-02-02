@extends('layouts.horizontal-layout')

@section('title', 'Kelola kategori konseling - ')

@section('navtitle', 'Kategori Konseling')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@php
$trashed = false;

$columns = [
    [
        'label' => 'No',
        'slot' => fn($item, $loop) => $loop->iteration + ($categories->firstItem() - 1),
        'nowrap' => true,
    ],

    [
        'label' => 'Nama',
        'slot' => fn($item) => $item->name,
        'nowrap' => true,
    ],

    [
        'label' => 'Item deskripsi',
        'slot' => fn($item) => $item->descriptions_count ?: 0 . ' item',
        'nowrap' => true,
    ],

    [
        'label' => '',
        'field' => 'actions',
        'slot' => function ($item) {
            return view('components.partial-actions', [
                'item' => $item,
                'routes' => [
                    'edit'    => 'counseling::manage.counseling.categories.edit',
                    'destroy' => 'counseling::manage.counseling.categories.destroy',
                    'restore' => 'counseling::manage.counseling.categories.restore',
                    'kill'    => 'counseling::manage.counseling.categories.kill',
                ],
                'trashed' => $item->trashed(),
            ])->render();
        },
        'nowrap' => true,
    ],
];
@endphp


@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid">
        <div class="col-md-7 col-lg-8">
             <x-table
                type="material"
                :data="$categories"
                :columns="$columns"
                title="Konseling"
                searchRoute="{{ route('counseling::manage.counseling.categories.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah kategori konseling</h6>
                </div>

                <div class="card-body">
                    <div class="h1 text-muted text-right">
                        <i class="mdi mdi-briefcase-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $categories_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total</small>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Tambah kategori</h6>
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('counseling::manage.counseling.categories.store') }}" method="POST"> @csrf
                        <x-input-group :isRow="false" required label="Nama kategori">
                            <x-input
                                name="name"
                                :value="old('name')"
                                placeholder="Masukkan nama kategori..."
                                :error="$errors->has('name')"
                                autocomplete="off"
                            />

                            @error('name')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </x-input-group>

                        <x-input-group :isRow="false">
                            <x-btn variant="dark">Simpan</x-btn>
                        </x-input-group>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('counseling::manage.counseling.categories.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan kategori yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
