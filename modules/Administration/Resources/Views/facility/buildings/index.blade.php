@extends('layouts.horizontal-layout')

@section('title', 'Gedung - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Sarpras</li>
    <li class="breadcrumb-item active">Gedung</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    [
        'field' => 'kd',
        'label' => 'Kode',
        'slot' => fn ($item) =>
            e($item->kd) .
            '<div><small class="text-muted">' .
            e($item->grade->name ?? '-') .
            '</small></div>',
    ],

    [
        'field' => 'name',
        'label' => 'Nama',
    ],

    [
        'field' => 'village',
        'label' => 'Alamat',
    ],

    ['field' => 'actions', 'label' => 'Aksi', 'slot' => fn($item) => view('components.partial-actions',
    [
    'item' => $item,
    'routes' => [
        'edit' => 'administration::facility.buildings.show',
        'destroy' => 'administration::facility.buildings.destroy',
        'restore' => 'administration::facility.buildings.restore',
        'kill' => 'administration::facility.buildings.kill',
    ]
    ])->render()],
];
@endphp


@section('body-content')
    <div class="row container-fluid">
        @include('components.navbar-admin')

        <div class="col-md-8">
             <x-table
                type="material"
                :data="$buildings"
                :columns="$columns"
                title="Gedung"
                searchRoute="{{ route('administration::facility.buildings.index', ['academic' => request('academic')]) }}"
                :trash="$trashed"
            />
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Tambah Gedung</h6>
                </div>
                <div class="card-body">
                    <form class="form-block" action="" method="POST"> @csrf
                        <x-input-group :isRow="true">

                            <x-label value="Kode Gedung" />
                            <x-col size="12">
                                <x-input
                                    name="kd"
                                    required
                                    autocomplete="off"
                                    :value="old('kd', $building->kd ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="Nama Gedung" />

                            <x-col size="12">
                                <x-input
                                    name="name"
                                    required
                                    autocomplete="off"
                                    :value="old('name', $building->name ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="Alamat" />

                            <x-col size="12">
                                <x-input
                                    name="address"
                                    required
                                    autocomplete="off"
                                    :value="old('address', $building->address ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="RT" />

                            <x-col size="12">
                                <x-input
                                    type="number"
                                    name="rt"
                                    autocomplete="off"
                                    :value="old('rt', $building->rt ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="RW" />

                            <x-col size="12">
                                <x-input
                                    type="number"
                                    name="rw"
                                    autocomplete="off"
                                    :value="old('rw', $building->rw ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="Kelurahan" />

                            <x-col size="12">
                                <x-input
                                    name="village"
                                    required
                                    autocomplete="off"
                                    :value="old('village', $building->village ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="Kecamatan" />

                            <x-col size="12">
                                <x-select
                                    name="district_id"
                                    :options="$district->map(fn($d) => [
                                        'value' => $d->id,
                                        'label' => $d->name,
                                    ])"
                                    :selected="old('district_id', $building->district_id ?? '')"
                                    placeholder="Pilih Kecamatan"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="Kode Pos" />

                            <x-col size="12">
                                <x-input
                                    type="number"
                                    name="postal"
                                    required
                                    autocomplete="off"
                                    :value="old('postal', $building->postal ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group class="mt-2">
                            <x-btn type="submit" variant="success">
                                Simpan
                            </x-btn>
                        </x-input-group>

                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="mdi mdi-cogs float-left mr-2"></i>Lanjutan
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::facility.buildings.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan Gedung yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
