@extends('layouts.horizontal-layout')

@section('title', 'Divisi | ')
@section('navtitle', 'Divisi')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="card mb-4 p-0 border-0">
                <div class="card-header bg-gradient-dark text-white">
                    <h6 class="text-white">Departement</h6>
                </div>

                <div class="card-body">
                    <form class="form-block" action="{{ route('core::company.departments.store', ['next' => request('next')]) }}" method="POST"> @csrf
                        <x-input-group :isRow="true">
                            <x-label col="3" value="Kode devisi" />
                            <x-col size="8">
                                <x-input
                                    type="text"
                                    name="kd"
                                    :value="old('kd', $department->kd ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label col="3" value="Nama devisi" />
                            <x-col size="8">
                                <x-input
                                    type="text"
                                    name="name"
                                    :value="old('kd', $department->name ?? '')"
                                />
                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label col="3" value="Deskripsi" />
                            <x-col size="8">
                               <x-textarea
                                    label="Deskripsi"
                                    name="description"
                                    rows="5"
                                    placeholder="Masukkan deskripsi..."
                                    :value="old('description', $department->description ?? '')"
                                />

                            </x-col>
                        </x-input-group>

                        <x-input-group :isRow="true">
                            <x-label col="3" value="Divisi Atasan" />
                            <x-col size="8">
                              <x-select
                                    name="parent_id"
                                    placeholder="-- Pilih --"
                                    :value="old('parent_id', $department->parent_id ?? null)"
                                    :options="$departments->map(fn($_department) => [
                                        'value' => $_department->id,
                                        'label' => $_department->name
                                    ])"
                                />


                                 @error('parent_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </x-col>
                        </x-input-group>

                        <div class="required row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Visibilitas</label>
                            <div class="col-lg-8">
                                <div class="btn-group">
                                    <input class="btn-check" type="radio" id="is_visible1" name="is_visible" value="1" required autocomplete="off"
                                        @checked(!is_null(old('is_visible', $department->is_visible ?? null)) && old('is_visible', $department->is_visible ?? null) == 1) />
                                    <label class="btn btn-outline-light text-dark" for="is_visible1">
                                        <span class="material-symbols-rounded">visibility</span>
                                    </label>

                                    <input class="btn-check" type="radio" id="is_visible0" name="is_visible" value="0" required autocomplete="off"
                                        @checked(!is_null(old('is_visible', $department->is_visible ?? null)) && old('is_visible', $department->is_visible ?? null) == 0) />
                                    <label class="btn btn-outline-light text-dark" for="is_visible0">
                                        <span class="material-symbols-rounded">visibility_off</span>
                                    </label>
                                </div>

                                @error('description')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                         <x-input-group>
                            <x-col size="12" offset="3">
                                <x-btn type="submit" variant="success">
                                    Simpan
                                </x-btn>

                                <a class="btn btn-secondary"
                                href="{{ request('next', route('core::company.departments.index')) }}">
                                    Kembali
                                </a>
                            </x-col>
                        </x-input-group>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
