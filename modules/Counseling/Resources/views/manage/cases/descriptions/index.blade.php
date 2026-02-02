@extends('layouts.horizontal-layout')

@section('title', 'Kelola deskripsi kasus - ')

@section('navtitle', 'Deskripsi Khasus')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@php
    $trashed = false;
    $columns = [
        [
            'label' => 'Kategori',
            'slot' => fn($item) => '<a href="'.route('counseling::manage.cases.categories.edit', [
                                        'category' => $item->ctg_id,
                                        'next' => url()->full()
                                    ]).'">'.$item->category->name.'</a>',
            'nowrap' => true,
        ],
        [
            'label' => 'Deskripsi',
            'slot' => fn($item) => $item->name,
            'nowrap' => true,
        ],
        [
            'label' => 'Poin',
            'slot' => fn($item) => $item->point,
            'class' => 'text-center',
            'nowrap' => true,
        ],
        [
            'label' => '',
            'field' => 'actions',
            'slot' => fn($item) => view('components.partial-actions', [
                'item' => $item,
                'routes' => [
                    'edit' => ['counseling::manage.cases.descriptions.edit', ['description' => $item->id, 'next' => url()->full()]],
                    'destroy' => ['counseling::manage.cases.descriptions.destroy', ['description' => $item->id]],
                ],
            ])->render(),
            'nowrap' => true,
            'class' => 'py-2 text-right align-middle',
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
                title="Deskripsi Item"
                {{-- searchRoute="{{ route('counseling::manage.cases.descriptions.index', ['search' => request('search')]) }}" --}}
                :trash="$trashed"
                :extra="[view('counseling::manage.cases.descriptions.extra-filter', compact('categories'))->render()]"
            />
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Jumlah deskripsi kasus</h6>
                </div>

                <div class="card-body">
                    <div class="h1 text-muted mb-4 text-right">
                        <i class="mdi mdi-briefcase-outline float-right"></i>
                    </div>
                    <div class="text-value">{{ $descriptions_count }}</div>
                    <small class="text-muted text-uppercase font-weight-bold">Total</small>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6>Tambah deskripsi</h6>
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('counseling::manage.cases.descriptions.store') }}" method="POST"> @csrf
                        <x-input-group label="Kategori" :isRow="false" required>
                            <x-select
                                name="ctg_id"
                                :options="$categories->mapWithKeys(fn($c) => [$c->id => $c->name])"
                                :selected="old('ctg_id')"
                                :error="$errors->has('ctg_id')"
                                placeholder="-- Pilih --"
                            />
                            @error('ctg_id')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </x-input-group>

                        <x-input-group label="Deskripsi" :isRow="false" required>
                            <x-input
                                name="name"
                                type="text"
                                :value="old('name')"
                                :error="$errors->has('name')"
                                autocomplete="off"
                            />
                            @error('name')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </x-input-group>

                        <x-input-group label="Poin" :isRow="false" required>
                            <x-input
                                name="point"
                                type="number"
                                :value="old('point')"
                                :error="$errors->has('point')"
                                class="w-50"
                                autocomplete="off"
                            />
                            @error('point')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </x-input-group>

                        <x-btn type="submit" variant="dark">Simpan</x-btn>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
