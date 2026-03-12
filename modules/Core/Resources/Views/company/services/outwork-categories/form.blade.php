@extends('layouts.horizontal-layout')

@section('title', ($isEdit ?? false ? 'Ubah' : 'Tambah').' Kategori kegiatan lainnya | ')
@section('navtitle', 'Kategori kegiatan lainnya')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $isEdit = isset($category) && is_object($category) && $category->exists;
    
    if (isset($category) && is_array($category)) {
        $isEdit = !empty($category);
    }
@endphp


@section('body-content')
@include('components.navbar-admin')

<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-10">

        <div class="card mb-4 border-0">
            <div class="card-header bg-gradient-dark text-white">
                <h6 class="text-white">{{ $isEdit ? 'Ubah' : 'Tambah' }} Kategori Insentif</h6>
            </div>


            <div class="card-body shadow-sm">
                <form method="POST" action="{{ $isEdit
                    ? route('core::company.services.outwork-categories.update', ['category' => $category->id, 'next' => request('next')])
                    : route('core::company.services.outwork-categories.store', ['next' => request('next')]) }}">
                    @csrf
                    @if($isEdit) @method('PUT') @endif

                    {{-- Nama kategori --}}
                    <x-input-group :isRow="true" required>
                        <x-label col="3" value="Nama kategori" />
                        <x-col size="6">
                            <x-input
                                name="name"
                                required
                                :value="old('name', $isEdit ? $category->name : '')"
                            />
                        </x-col>
                    </x-input-group>

                    {{-- Keterangan --}}
                    <x-input-group :isRow="true" required>
                        <x-label col="3" value="Keterangan" />
                        <x-col size="6">
                            <x-input
                                name="description"
                                required
                                :value="old('description', $isEdit ? $category->description : '')"
                            />
                        </x-col>
                    </x-input-group>

                    {{-- Tarif --}}
                    <x-input-group :isRow="true">
                        <x-label col="3" value="Tarif" />
                        <x-col size="6">
                            <div class="input-group gap-2">
                                <div class="input-group-text" data-bs-toggle="tooltip" data-bs-title="Di luar jam kerja">
                                    <i class="mdi mdi-timer-off-outline text-danger"></i>
                                </div>
                                <x-input
                                    type="number"
                                    name="price"
                                    placeholder="Di luar jam kerja"
                                    :value="old('price', $isEdit ? $category->price : '')"
                                />
                                <div class="input-group-text" data-bs-toggle="tooltip" data-bs-title="Di jam kerja">
                                    <i class="text-success mdi mdi-timer-outline"></i>
                                </div>
                                <x-input
                                    type="number"
                                    name="in_working_hours_price"
                                    placeholder="Di jam kerja"
                                    :value="old('in_working_hours_price', $isEdit ? $category->meta?->in_working_hours_price : '')"
                                />
                            </div>
                        </x-col>
                    </x-input-group>

                    {{-- Meta: Persiapan & Tarif flat --}}
                    <x-input-group :isRow="true">
                        <x-label col="3" value="Inputan waktu" />
                        <x-col size="4">
                            @foreach(['prepareable' => 'Persiapan kegiatan', 'fixed' => 'Tarif flat'] as $v => $description)
                                <div class="form-check mb-2 d-grid" style="grid-template-columns: 20px 1fr;">
                                    <input class="form-check-input only_one" type="checkbox" name="meta[{{ $v }}]" id="meta{{ $v }}" value="1"
                                        @checked(old("meta.$v", data_get($category ?? [], "meta.$v", 0)))>
                                    <label class="form-check-label ms-2" for="meta{{ $v }}">
                                        <code>{{ $v }}</code> <br>
                                        <small class="text-muted">{{ $description }}</small>
                                    </label>
                                </div>
                            @endforeach

                        </x-col>
                    </x-input-group>

                     <x-input-group>
                        <x-col size="12" offset="3">
                            <x-btn type="submit" variant="success">
                                Simpan
                            </x-btn>

                            <a class="btn btn-secondary"
                            href="{{ request('next', route('core::company.services.outwork-categories.index')) }}">
                                Kembali
                            </a>
                        </x-col>
                    </x-input-group>
                    {{-- Action --}}

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
