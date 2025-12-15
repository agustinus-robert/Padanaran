@extends('layouts.horizontal-layout')

@section('title', 'Ruang - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Sarpras</li>
    <li class="breadcrumb-item active">Ruang</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@php
$trashed = false;
$columns = [
    ['field' => 'kd', 'label' => 'Kode', 'slot' => fn($item) => $item->kd . '<div><small class="text-muted">' . $item->grade->name . '</small></div>'],
    ['field' => 'building.name', 'label' => 'Gedung', 'slot' => fn($item) => $item->building->name],
    ['field' => 'name', 'label' => 'Nama'],
    ['field' => 'capacity', 'label' => 'Kapasitas'],
    ['field' => 'actions', 'label' => 'Aksi', 'slot' => fn($item) => view('components.partial-actions', [
        'item' => $item,
        'routes' => [
            // 'show' => 'administration::facility.rooms.show',
            'edit' => 'administration::facility.rooms.show',
            'destroy' => 'administration::facility.rooms.destroy',
            'restore' => 'administration::facility.rooms.restore',
            'kill' => 'administration::facility.rooms.kill',
        ]
    ])->render()]
];
@endphp

@section('body-content')
    <div class="row container-fluid">
        @include('components.navbar-admin')

        <div class="col-md-8">
            <x-table
                type="material"
                :data="$rooms"
                :columns="$columns"
                title="Ruangan"
                searchRoute="{{ route('administration::facility.rooms.index', ['academic' => request('academic')]) }}"
                :trash="$trashed"
            />

        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Tambah Ruang</h6>
                </div>
                <div class="card-body">
                    <form class="form-block" action="" method="POST"> @csrf
                        <x-input-group :isRow="true">
                            <x-label value="Gedung" />

                            <x-col size="12">
                                <x-select
                                    name="building_id"
                                    placeholder="Pilih Gedung"
                                    :options="$buildings->map(fn($b) => [
                                        'value' => $b->id,
                                        'label' => $b->name
                                    ])"
                                    :value="old('building_id')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="Kode Ruang" />

                            <x-col size="12">
                                <x-input
                                    name="kd"
                                    required
                                    autocomplete="off"
                                    :value="old('kd')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="Nama Ruang" />

                            <x-col size="12">
                                <x-input
                                    name="name"
                                    required
                                    autocomplete="off"
                                    :value="old('name')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label value="Kapasitas" />

                            <x-col size="12">
                                <x-input
                                    name="capacity"
                                    required
                                    autocomplete="off"
                                    :value="old('capacity')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group class="mb-0">
                            <x-btn class="mt-2" type="submit" variant="success">
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
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::facility.rooms.index', ['trash' => request('trash', 0) ? null : 1]) }}"><i class="mdi mdi-delete-outline"></i> Tampilkan ruang yang {{ request('trash', 0) ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
