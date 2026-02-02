@extends('layouts.horizontal-layout')

@section('title', 'Gedung - ')

@section('navtitle', 'Gedung')

@section('breadcrumb')
    <li class="breadcrumb-item">Sarpras</li>
    <li class="breadcrumb-item active">Gedung</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')
    <div class="row container-fluid">
        @include('components.navbar-admin')

        <div class="row">
            <div class="col-sm-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="mb-1">Info Gedung</h4>
                        <p class="text-muted mb-2">Informasi tentang Gedung {{ $building->name }}</p>
                    </div>
                    <div class="list-group list-group-flush">

                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header"><h4>Edit Gedung</h4></div>
                    <div class="card-body">
                        <form class="form-block" action="{{ route('administration::facility.buildings.update', ['building' => $building->id]) }}" method="POST"> @csrf @method('PUT')
                            <x-input-group :isRow="true">
                                <x-label value="Kode Gedung" />

                                <x-col size="12">
                                    <x-input
                                        name="kd"
                                        required
                                        autocomplete="off"
                                        :value="old('kd', $building->kd)"
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
                                        :value="old('name', $building->name)"
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
                                        :value="old('address', $building->address)"
                                    />
                                </x-col>
                            </x-input-group>

                            <x-input-group :isRow="true">
                                <x-label value="RT" />

                                <x-col size="12">
                                    <x-input
                                        name="rt"
                                        required
                                        autocomplete="off"
                                        :value="old('rt', $building->rt)"
                                    />
                                </x-col>
                            </x-input-group>

                            <x-input-group :isRow="true">
                                <x-label value="RW" />

                                <x-col size="12">
                                    <x-input
                                        name="rw"
                                        required
                                        autocomplete="off"
                                        :value="old('rw', $building->rw)"
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
                                        :value="old('village', $building->village)"
                                    />
                                </x-col>
                            </x-input-group>

                            <x-input-group :isRow="true">
                                <x-label value="Kecamatan" />

                                <x-col size="12">
                                    <x-select
                                        name="district_id"
                                        placeholder="Pilih Kecamatan"
                                        :options="$districtAll->map(fn($d) => [
                                            'value' => $d->id,
                                            'label' => $d->name,
                                        ])"
                                        :value="old('district_id', $building->district_id)"
                                    />
                                </x-col>
                            </x-input-group>

                            <x-input-group :isRow="true">
                                <x-label value="Kode Pos" />

                                <x-col size="12">
                                    <x-input
                                        name="postal"
                                        required
                                        autocomplete="off"
                                        :value="old('postal', $building->postal)"
                                    />
                                </x-col>
                            </x-input-group>

                            <x-input-group class="mb-0">
                                <x-btn type="submit" variant="success">
                                    Update
                                </x-btn>
                            </x-input-group>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
