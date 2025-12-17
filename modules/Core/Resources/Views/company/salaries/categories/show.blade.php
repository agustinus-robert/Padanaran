@extends('layouts.horizontal-layout')

@section('title', 'Ubah kategori gaji | ')
@section('navtitle', 'Ubah kategori gaji')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')

@include('components.navbar-admin')

<div class="row container-fluid justify-content-center">
    <div class="col-xxl-6 col-xl-10">
        <div class="card mb-4 border-0">
            <div class="card-header bg-gradient-dark text-white">
                Ubah Kategori Gaji
            </div>

            <div class="card-body shadow-sm">
                <form class="form-block" action="{{ route('core::company.salaries.categories.update', ['category' => $category->id, 'next' => request('next')]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <x-input-group :isRow="true" required>
                        <x-label value="Index urutan" />
                        <x-col size="12">
                            <div class="input-group">
                                <x-input
                                    type="number"
                                    name="az"
                                    :value="old('az', $category->az)"
                                    required
                                    @class(['is-invalid' => $errors->has('az')])
                                />
                                <span class="p-2 border text-center" style="width:35px;">#</span>
                            </div>
                            @error('az')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-col>
                    </x-input-group>

                    <x-input-group :isRow="true" required>
                        <x-label value="Pilih slip" />
                        <x-col size="12">
                            <x-select
                                name="slip_id"
                                required
                                :value="old('slip_id', $category->slip_id)"
                                :options="$slips->map(fn($slip) => ['value' => $slip->id, 'label' => $slip->name])"
                                @class(['is-invalid' => $errors->has('slip_id')])
                            />
                            @error('slip_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-col>
                    </x-input-group>

                    <x-input-group :isRow="true" required>
                        <x-label value="Nama kategori" />
                        <x-col size="12">
                            <x-input
                                type="text"
                                name="name"
                                :value="old('name', $category->name)"
                                required
                                @class(['is-invalid' => $errors->has('name')])
                            />
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-col>
                    </x-input-group>

                    {{-- Tombol aksi --}}
                    <x-input-group :isRow="false">
                        <x-col size="12" class="d-flex gap-2 mt-2">
                            <x-btn type="success" variant="dark">
                                <span class="material-symbols-rounded">check</span> Perbarui
                            </x-btn>
                            <a class="btn btn-light text-dark" href="{{ request('next', route('core::company.salaries.categories.index')) }}">
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </a>
                        </x-col>
                    </x-input-group>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
