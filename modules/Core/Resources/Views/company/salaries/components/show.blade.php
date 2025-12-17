@extends('layouts.horizontal-layout')

@section('title', 'Ubah komponen gaji | ')
@section('navtitle', 'Ubah komponen gaji')
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
                Ubah Komponen Gaji
            </div>

            <div class="card-body shadow-sm">
                <form class="form-block" action="{{ route('core::company.salaries.components.update', ['component' => $salary->id, 'next' => request('next')]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Kategori --}}
                    <x-input-group :isRow="true" required>
                        <x-label value="Kategori" />
                        <x-col size="12">
                            <x-select
                                name="ctg_id"
                                required
                                :value="old('ctg_id', $salary->ctg_id)"
                                :options="$slips->map(fn($slip) => [
                                    'label' => $slip->name,
                                    'children' => $slip->categories->map(fn($cat) => [
                                        'value' => $cat->id,
                                        'label' => $cat->name,
                                    ])->toArray()
                                ])->toArray()"
                                @class(['is-invalid' => $errors->has('ctg_id')])
                            />
                            @error('ctg_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-col>
                    </x-input-group>

                    {{-- Satuan --}}
                    <x-input-group :isRow="true" required>
                        <x-label value="Satuan" />
                        <x-col size="12">
                            <x-select
                                name="unit"
                                required
                                :value="old('unit', $salary->unit?->value)"
                                :options="collect($units)->map(fn($unit) => [
                                    'value' => $unit->value,
                                    'label' => $unit->label() . ' (' . implode(' ', array_filter([$unit->prefix(), $unit->suffix()])) . ')',
                                ])->toArray()"
                                @class(['is-invalid' => $errors->has('unit')])
                            />
                            @error('unit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-col>
                    </x-input-group>

                    {{-- Jenis operasi --}}
                    <x-input-group :isRow="true">
                        <x-label value="Jenis operasi" />
                        <x-col size="12">
                            <x-select
                                name="operate"
                                :value="old('operate', $salary->operate?->value)"
                                :options="collect($operates)->map(fn($op) => [
                                    'value' => $op->value,
                                    'label' => $op->label(),
                                ])->toArray()"
                                @class(['is-invalid' => $errors->has('operate')])
                            />
                            @error('operate')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-col>
                    </x-input-group>

                    {{-- Nama komponen --}}
                    <x-input-group :isRow="true" required>
                        <x-label value="Nama komponen" />
                        <x-col size="12">
                            <x-input
                                type="text"
                                name="name"
                                :value="old('name', $salary->name)"
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
                            <a class="btn btn-light text-dark" href="{{ request('next', route('core::company.salaries.components.index')) }}">
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
