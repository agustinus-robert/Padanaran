@extends('layouts.horizontal-layout')

@section('title', ($isEdit ?? false) ? 'Ubah kategori cuti | ' : 'Tambah kategori cuti | ')
@section('navtitle', 'Kategori cuti')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $isEdit = isset($category) && $category?->exists;
@endphp

@section('body-content')

@include('components.navbar-admin')

<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-10">
        <div class="card mb-4 border-0">
            <div class="card-header bg-gradient-dark text-white">
                <h6 class="text-white">{{ $isEdit ? 'Ubah' : 'Tambah' }} Kategori Cuti</h6>
            </div>

            <div class="card-body shadow-sm">
                <form
                    method="POST"
                    action="{{ $isEdit
                        ? route('core::company.services.vacation-categories.update', ['category' => $category->id, 'next' => request('next')])
                        : route('core::company.services.vacation-categories.store', ['next' => request('next')])
                    }}"
                >
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Nama --}}
                    <x-input-group :isRow="true" required>
                        <x-label col="3" value="Nama kategori cuti" />
                        <x-col size="6">
                            <x-input
                                name="name"
                                required
                                :value="old('name', $isEdit ? $category->name : '')"
                            />
                        </x-col>
                    </x-input-group>

                    {{-- Tipe cuti --}}
                    <x-input-group :isRow="true" required>
                        <x-label col="3" value="Tipe cuti" />
                        <x-col size="6">
                            <x-select
                                name="type"
                                required
                                placeholder="-- Pilih tipe cuti --"
                                :value="old('type', $isEdit ? $category->type->value : null)"
                                :x-options="collect($types)->map(fn($_type) => [
                                    'value' => $_type->value,
                                    'label' => $_type->label()
                                ])"
                            />
                        </x-col>
                    </x-input-group>

                    {{-- Kuota --}}
                    <x-input-group :isRow="true">
                        <x-label col="3" value="Kuota" />
                        <x-col size="6">
                            <div class="input-group">
                                <x-input
                                    type="number"
                                    name="quota"
                                    max="366"
                                    :value="old('quota', $isEdit ? data_get($category->meta, 'quota', '') : '')"
                                />
                                <span class="p-2">hari</span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Kosongkan jika tidak ada batasan kuota
                            </small>
                        </x-col>
                    </x-input-group>

                    {{-- Jenis inputan --}}
                    <x-input-group :isRow="true">
                        <x-label col="3" value="Jenis inputan" />
                        <x-col size="4">
                            @foreach(['options', 'range'] as $v)
                                <div class="form-check mb-3">
                                    <input
                                        class="form-check-input only_one"
                                        type="radio"
                                        name="fields"
                                        id="fields_{{ $v }}"
                                        value="{{ $v }}"
                                        @checked(old('fields', $isEdit ? data_get($category->meta, 'fields', null) : null) === $v)
                                        required
                                    >
                                    <label class="form-check-label" for="fields_{{ $v }}">
                                        <code>{{ $v }}</code>
                                    </label>
                                </div>
                            @endforeach
                        </x-col>
                    </x-input-group>

                    {{-- Mode freelance --}}
                    <x-input-group :isRow="true">
                        <x-label col="3" value="Mode freelance?" />
                        <x-col size="6">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="as_freelance"
                                    value="1"
                                    name="as_freelance"
                                    @checked(old('as_freelance', $isEdit ? data_get($category->meta, 'as_freelance', false) : false))
                                >
                                <label class="form-check-label" for="as_freelance">
                                    <span id="as_freelance-text">
                                        {{ old('as_freelance', $isEdit ? data_get($category->meta, 'as_freelance', false) : false)
                                            ? 'Ya, kategori ini menyediakan mode freelance'
                                            : 'Tidak, kategori ini tidak menyediakan mode freelance' }}
                                    </span>
                                </label>
                            </div>
                        </x-col>
                    </x-input-group>

                    <x-input-group>
                        <x-col size="12" offset="3">
                            <x-btn type="submit" variant="success">
                                Simpan
                            </x-btn>

                            <a class="btn btn-secondary"
                            href="{{ request('next', route('core::company.services.vacation-categories.index')) }}">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Radio only one
    document.querySelectorAll('.only_one').forEach(el => {
        el.addEventListener('change', e => {
            document.querySelectorAll('.only_one').forEach(o => {
                if (o !== e.target) o.checked = false
            })
        })
    });

    // Update label freelance
    document.querySelector('#as_freelance')?.addEventListener('change', e => {
        document.querySelector('#as_freelance-text').innerHTML = e.target.checked
            ? 'Ya, kategori ini menyediakan mode freelance'
            : 'Tidak, kategori ini tidak menyediakan mode freelance';
    });
});
</script>
@endpush
