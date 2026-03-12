@extends('layouts.horizontal-layout')

@section('title', 'Jabatan | ')
@section('navtitle', 'Jabatan')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')

@include('components.navbar-admin')

@php
    /** MODE */
    $isEdit = isset($position) && $position->exists;

    /** SAFE DEFAULTS */
    $parentIds = old('parents', $isEdit ? $position->parents->pluck('id')->toArray() : []);
    $childIds  = old('children', $isEdit ? $position->children->pluck('id')->toArray() : []);
    $visible   = old('is_visible', $isEdit ? $position->is_visible : 1);
@endphp

<div class="container-fluid py-4">
    <form
        class="form-block"
        method="POST"
        action="{{ $isEdit
            ? route('core::company.positions.update', ['position' => $position->id, 'next' => request('next')])
            : route('core::company.positions.store', ['next' => request('next')])
        }}"
    >
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row justify-content-center">
            {{-- KOLOM KIRI: Informasi Jabatan --}}
            <div class="col-xxl-5 col-xl-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 d-flex align-items-center pt-3">
                        <span class="material-symbols-rounded me-2 text-dark">work</span>
                        <h5 class="mb-0">Informasi Jabatan</h5>
                    </div>

                    <div class="card-body">
                        {{-- Departemen --}}
                        <x-input-group :isRow="true">
                            <x-label col="3" value="Departemen" />
                            <x-col size="8">
                                <x-select
                                    name="dept_id"
                                    placeholder="-- Pilih --"
                                    :value="old('dept_id', $position->dept_id ?? null)"
                                    :options="$departments->map(fn($_d) => [
                                        'value' => $_d->id,
                                        'label' => $_d->name
                                    ])"
                                />
                                @error('dept_id')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        {{-- Kode --}}
                        <x-input-group :isRow="true" required>
                            <x-label col="3" value="Kode jabatan" />
                            <x-col size="8">
                                <x-input
                                    type="text"
                                    name="kd"
                                    required
                                    :value="old('kd', $position->kd ?? '')"
                                />
                                @error('kd')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        {{-- Nama --}}
                        <x-input-group :isRow="true" required>
                            <x-label col="3" value="Nama jabatan" />
                            <x-col size="8">
                                <x-input
                                    type="text"
                                    name="name"
                                    required
                                    :value="old('name', $position->name ?? '')"
                                />
                                @error('name')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        {{-- Deskripsi --}}
                        <x-input-group :isRow="true">
                            <x-label col="3" value="Deskripsi" />
                            <x-col size="8">
                                <x-textarea
                                    name="description"
                                    rows="4"
                                    :value="old('description', $position->description ?? '')"
                                />
                                @error('description')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        {{-- Peran bawaan --}}
                        <x-input-group :isRow="true">
                            <x-label col="3" value="Peran bawaan" />
                            <x-col size="8">
                                <x-select
                                    name="default_applied_role"
                                    placeholder="-- Pilih --"
                                    :value="old('default_applied_role', $position?->getMeta('default_applied_role')?->id ?? null)"
                                    :options="$roles->map(fn($_r) => [
                                        'value' => $_r->id,
                                        'label' => $_r->name
                                    ])"
                                />
                                <small class="text-muted d-block mt-1">
                                    Peran ini diterapkan ke pengguna dengan jabatan ini
                                </small>
                                @error('default_applied_role')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        {{-- Visibilitas --}}
                        <div class="mb-3 required row">
                            <label class="col-lg-4 col-xl-3 col-form-label">Visibilitas</label>
                            <div class="col-lg-8">
                                <div class="btn-group">
                                    <input class="btn-check" type="radio" id="is_visible1" name="is_visible" value="1" @checked($visible == 1)>
                                    <label class="btn btn-outline-secondary" for="is_visible1">
                                        <span class="material-symbols-rounded text-sm">visibility</span>
                                    </label>

                                    <input class="btn-check" type="radio" id="is_visible0" name="is_visible" value="0" @checked($visible == 0)>
                                    <label class="btn btn-outline-secondary" for="is_visible0">
                                        <span class="material-symbols-rounded text-sm">visibility_off</span>
                                    </label>
                                </div>
                                @error('is_visible')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Struktur Jabatan --}}
            <div class="col-xxl-7 col-xl-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 d-flex align-items-center pt-3">
                        <span class="material-symbols-rounded me-2 text-dark">account_tree</span>
                        <h5 class="mb-0">Struktur Jabatan</h5>
                    </div>

                    <div class="card-body">
                        {{-- Atasan --}}
                        <x-input-group :isRow="true">
                            <x-label col="3" value="Atasan" />
                            <x-col size="8">
                                <select class="form-select border p-2" name="parents[]" multiple style="height:200px">
                                    @foreach($positions as $dept => $_positions)
                                        <optgroup label="{{ $dept ?: 'Lainnya' }}">
                                            @foreach($_positions as $_p)
                                                <option
                                                    value="{{ $_p->id }}"
                                                    @selected(in_array($_p->id, $parentIds))
                                                    @disabled($isEdit && $_p->id == $position->id)
                                                >
                                                    {{ $_p->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Tahan <code>ctrl</code> untuk multi-pilih</small>
                            </x-col>
                        </x-input-group>

                        {{-- Bawahan --}}
                        <x-input-group :isRow="true">
                            <x-label col="3" value="Bawahan" />
                            <x-col size="8">
                                <select class="form-select border p-2" name="children[]" multiple style="height:200px">
                                    @foreach($positions as $dept => $_positions)
                                        <optgroup label="{{ $dept ?: 'Lainnya' }}">
                                            @foreach($_positions as $_p)
                                                <option
                                                    value="{{ $_p->id }}"
                                                    @selected(in_array($_p->id, $childIds))
                                                    @disabled($isEdit && $_p->id == $position->id)
                                                >
                                                    {{ $_p->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </x-col>
                        </x-input-group>

                        {{-- Level --}}
                        <x-input-group :isRow="true" required>
                            <x-label col="3" value="Tingkat" />
                            <x-col size="4">
                                <input
                                    type="number"
                                    class="form-control border p-2"
                                    name="level"
                                    max="10"
                                    required
                                    value="{{ old('level', $isEdit ? $position->level->value : 1) }}"
                                >
                            </x-col>
                        </x-input-group>

                        <hr class="horizontal dark my-4">

                        <x-input-group>
                            <x-col size="12" offset="3">
                                <x-btn type="submit" variant="success">
                                    Simpan
                                </x-btn>

                                <a class="btn btn-secondary"
                                href="{{ request('next', route('core::company.positions.index')) }}">
                                    Kembali
                                </a>
                            </x-col>
                        </x-input-group>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    const positions = {!! $positions->flatten()->pluck('name', 'level.value') !!}
    const setLevelDesc = (e) => {
        let level = document.querySelector('[name="level"]').value;
        let x = Object.keys(positions).indexOf(level) == -1
        document.getElementById('level-desc').innerHTML = (x ? 'Tidak setara dengan apapun' : ('Setara ' + positions[level]))
    }

    window.addEventListener('DOMContentLoaded', () => {
        ['keyup', 'change'].forEach((event) => {
            document.querySelector('[name="level"]').addEventListener(event, (e) => setLevelDesc(e))
        })

        setLevelDesc();
    });
</script>
@endpush
