@extends('layouts.horizontal-layout')

@section('title', ($isEdit ?? false) ? 'Ubah kategori izin | ' : 'Tambah kategori izin | ')
@section('navtitle', 'Kategori izin')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')

@include('components.navbar-admin')

@php
    $isEdit = isset($category) && $category?->exists;
@endphp

<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-10">
        <div class="card mb-4 border-0">
            <div class="card-header bg-gradient-dark text-white">
                <h6 class="text-white">Kategori Izin Siswa</h6>
            </div>

            <div class="card-body shadow-sm">

                <form
                    method="POST"
                    action="{{ $isEdit
                        ? route('core::company.services.leave-student-categories.update', [
                            'category' => $category->id,
                            'next' => request('next')
                        ])
                        : route('core::company.services.leave-student-categories.store', [
                            'next' => request('next')
                        ])
                    }}"
                >
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Nama --}}
                    <x-input-group :isRow="true" required>
                        <x-label col="3" value="Nama kategori izin" />
                        <x-col size="6">
                            <x-input
                                name="name"
                                required
                                :value="old('name', $isEdit ? $category->name : '')"
                            />
                        </x-col>
                    </x-input-group>

                    {{-- Parent --}}
                    <x-input-group :isRow="true">
                        <x-label col="3" value="Kategori parent" />
                        <x-col size="6">
                            <x-select
                                name="parent_id"
                                placeholder="Tanpa parent"
                                :value="old('parent_id', $isEdit ? $category->parent_id : null)"
                                :options="$categories->map(fn($_c) => [
                                    'value' => $_c->id,
                                    'label' => $_c->name
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
                                    :value="old('quota', $isEdit ? $category->meta?->quota : '')"
                                />
                                <span class="p-2">hari</span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Kosongkan jika tidak ada batasan kuota
                            </small>
                        </x-col>
                    </x-input-group>

                    {{-- Inputan waktu --}}
                    <x-input-group :isRow="true">
                        <x-label col="3" value="Inputan waktu" />
                        <x-col size="4">
                            @foreach([
                                'start_to_end' => 'Menampilkan jam mulai izin dan jam izin akhir',
                                'start_only'   => 'Hanya menampilkan jam mulai izin'
                            ] as $v => $description)
                                <div class="form-check mb-3 d-grid" style="grid-template-columns: 20px 1fr;">
                                    <input
                                        class="form-check-input only_one m-0"
                                        type="checkbox"
                                        name="time_input"
                                        id="time_input_{{ $v }}"
                                        value="{{ $v }}"
                                        @checked(old('time_input', $isEdit ? $category->meta?->time_input : null) === $v)
                                    >
                                    <label class="form-check-label ms-2" for="time_input_{{ $v }}">
                                        <code class="d-block">{{ $v }}</code>
                                        <small class="text-muted d-block">{{ $description }}</small>
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
                            href="{{ request('next', route('core::company.services.leave-student-categories.index')) }}">
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
    document.querySelectorAll('.only_one').forEach(el => {
        el.addEventListener('change', e => {
            document.querySelectorAll('.only_one').forEach(o => {
                if (o !== e.target) o.checked = false
            })
        })
    })
})
</script>
@endpush
