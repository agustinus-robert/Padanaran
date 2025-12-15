@extends('layouts.horizontal-layout')

@section('title', 'Ruangan - ')
@section('titleTemplate', config('account.admin.name'))
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Sarpras</li>
    <li class="breadcrumb-item">Gedung</li>
    <li class="breadcrumb-item active">Ruangan</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')
   <div class="row container-fluid">
        @include('components.navbar-admin')

        <div class="col-sm-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-1">Info Ruang</h4>
                    <p class="text-muted mb-2">Informasi tentang Ruang {{ $room->name }}</p>
                </div>
                <div class="list-group list-group-flush">

                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header"><h4>Edit Ruang</h4></div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::facility.rooms.update', ['room' => $room->id]) }}" method="POST"> @csrf @method('PUT')
                        <x-input-group class="mb-2" :isRow="true">
                            <x-label value="Kode Ruang" />

                            <x-col size="12">
                                <x-input
                                    name="kd"
                                    required
                                    autocomplete="off"
                                    :value="old('kd', $room->kd)"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group class="mb-2" :isRow="true">
                            <x-label value="Nama Ruang" />

                            <x-col size="12">
                                <x-input
                                    name="name"
                                    required
                                    autocomplete="off"
                                    :value="old('name', $room->name)"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group class="mb-2" :isRow="true">
                            <x-label value="Kapasitas" />

                            <x-col size="12">
                                <x-input
                                    name="capacity"
                                    required
                                    autocomplete="off"
                                    :value="old('capacity', $room->capacity)"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group class="mb-0">
                            <x-btn class="mt-2" type="submit" variant="success">
                                Update
                            </x-btn>
                        </x-input-group>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
