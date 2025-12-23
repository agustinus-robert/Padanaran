@extends('layouts.horizontal-layout')

@section('title', 'Kelola kategori kasus - ')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@php
    $trashed = false;
    $columns = [
        [
            'label' => 'No',
            'slot' => fn($category, $loop, $categories) => $loop->iteration + ($categories->firstItem() - 1),
        ],
        [
            'label' => 'Nama',
            'slot' => fn($category) => $category->name,
            'nowrap' => true,
        ],
        [
            'label' => 'Item deskipsi',
            'slot' => fn($category) => ($category->descriptions_count ?: 0) . ' item',
            'nowrap' => true,
        ],
        [
            'label' => '',
            'slot' => fn($category) => view('components.partial-actions', [
                'item' => $category,
                'routes' => [
                    'edit' => 'counseling::manage.cases.categories.edit',
                    'destroy' => 'counseling::manage.cases.categories.destroy',
                    'restore' => 'counseling::manage.cases.categories.restore',
                    'kill' => 'counseling::manage.cases.categories.kill',
                ],
                'trashed' => $category->trashed(),
            ])->render(),
            'nowrap' => true,
            'html' => true,
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
                title="Kategori"
                searchRoute="{{ route('counseling::manage.cases.categories.index', ['search' => request('search')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah Kategori Khasus</h6>
                </div>

                <div class="card-header">
                    <div class="text-value">{{ $categories_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total</small>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Tambah kategori</h6>
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('counseling::manage.cases.categories.store') }}" method="POST"> @csrf
                        <x-input-group label="Nama kategori" :isRow="false" required>
                            <x-input
                                name="name"
                                :value="old('name')"
                                :error="$errors->has('name')"
                                autocomplete="off"
                            />
                            @error('name')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </x-input-group>

                        <x-input-group :isRow="false">
                            <x-btn variant="dark" type="submit">Simpan</x-btn>
                        </x-input-group>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('counseling::manage.cases.categories.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan kategori yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
