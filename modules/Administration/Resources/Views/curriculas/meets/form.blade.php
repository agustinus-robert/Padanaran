@extends('layouts.horizontal-layout')

@section('title', isset($meet) ? 'Edit Pertemuan - ' : 'Tambah Pertemuan - ')
@section('titleTemplate', config('account.admin.name'))

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item">
        <a href="{{ request('next', route('administration::curriculas.meets.index')) }}">
            Pertemuan
        </a>
    </li>
    <li class="breadcrumb-item active">
        {{ isset($meet) ? 'Edit' : 'Tambah' }}
    </li>
@endsection

@push('nav')
    @include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')

@include('components.navbar-admin')
<div class="row container-fluid">

    {{-- ================= FORM ================= --}}
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">

                <form class="form-block"
                      method="POST"
                      action="{{ isset($meet)
                          ? route('administration::curriculas.meets.update', [
                              'meet' => $meet->id,
                              'next' => request('next', route('administration::curriculas.meets.index', [
                                  'academic' => request('academic', $acsem->id)
                              ]))
                          ])
                          : route('administration::curriculas.meets.store', [
                              'academic' => $acsem->id,
                              'next' => request('next', route('administration::curriculas.meets.index', [
                                  'academic' => $acsem->id
                              ]))
                          ])
                      }}">

                    @csrf
                    @isset($meet)
                        @method('PUT')
                    @endisset

                    {{-- Tahun Ajaran --}}
                    <x-input-group>
                        <x-label col="3" value="Tahun ajaran" />
                        <x-col size="5">
                            <strong>
                                <span class="form-control-plaintext">
                                    {{ $acsem->full_name }}
                                </span>
                            </strong>
                        </x-col>
                    </x-input-group>

                    {{-- Rombel --}}
                    <x-input-group label="Rombel" required>
                        <x-select
                            name="classroom_id"
                            placeholder="-- Pilih rombel --"
                            :value="old('classroom_id', $meet->classroom_id ?? null)"
                            :options="$acsem->classrooms->map(fn($c) => [
                                'value' => $c->id,
                                'label' => $c->full_name
                            ])"
                        />
                    </x-input-group>

                    {{-- Mapel --}}
                    <x-input-group label="Mapel" required>
                        <x-select
                            name="subject_id"
                            placeholder="-- Pilih mapel --"
                            :value="old('subject_id', $meet->subject_id ?? null)"
                            :options="$acsem->subjects->map(fn($s) => [
                                'value' => $s->id,
                                'label' => $s->name
                            ])"
                        />
                    </x-input-group>

                    {{-- Pengajar --}}
                    <x-input-group label="Pengajar" required>
                        <x-select
                            name="teacher_id"
                            placeholder="-- Pilih pengajar --"
                            :value="old('teacher_id', $meet->teacher_id ?? null)"
                            :options="$teachers->map(fn($t) => [
                                'value' => $t->id,
                                'label' => $t->user->name
                            ])"
                        />
                    </x-input-group>

                    <hr>

                    {{-- Warna --}}
                    <x-input-group label="Warna">
                        <x-input
                            type="color"
                            name="props[color]"
                            :value="old('props.color', $meet->props->color ?? '#ffffff')"
                        />
                    </x-input-group>

                    {{-- Tombol --}}
                    <x-input-group>
                        <x-col size="8" offset="3">
                            <x-btn type="submit" variant="primary">
                                {{ isset($meet) ? 'Update' : 'Simpan' }}
                            </x-btn>

                            <a class="btn btn-secondary"
                               href="{{ request('next', route('administration::curriculas.meets.index', [
                                   'academic' => $acsem->id
                               ])) }}">
                                Kembali
                            </a>
                        </x-col>
                    </x-input-group>

                </form>

            </div>
        </div>
    </div>

    {{-- ================= SIDEBAR ================= --}}
    <div class="col-md-4">

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="text-black">Tahun Ajaran</h6>
            </div>
            <div class="card-body">
                <x-input-group :isRow="true">
                    <x-col size="9">
                        @isset($acsem)
                            {{-- ========== EDIT MODE ========== --}}
                            <x-select
                                name="academic"
                                :options="[
                                    ['value' => $acsem->id, 'label' => $acsem->full_name]
                                ]"
                            />

                            {{-- agar tetap terkirim --}}
                            <input type="hidden" name="academic" value="{{ $acsem->id }}">

                        @else
                            {{-- ========== CREATE MODE ========== --}}
                            <div class="input-group w-100">
                                <x-select
                                    name="academic"
                                    :value="request('academic')"
                                    :options="$acsems->map(fn($_acsem) => [
                                        'value' => $_acsem->id,
                                        'label' => $_acsem->full_name
                                    ])"
                                />
                                <div class="input-group-append">
                                    <button class="btn btn-primary">Tetapkan</button>
                                </div>
                            </div>
                        @endisset
                    </x-col>

                    <x-col size="2">
                        <x-btn type="submit" variant="dark">
                            Terapkan
                        </x-btn>
                    </x-col>
                </x-input-group>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6>Lanjutan</h6>
            </div>

            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action text-black"
                   href="{{ route('administration::scholar.classrooms.index', [
                       'academic' => request('academic', $acsem->id)
                   ]) }}">
                    <i class="mdi mdi-account-group-outline"></i> Kelola rombel
                </a>

                <a class="list-group-item list-group-item-action text-black"
                   href="{{ route('administration::curriculas.subjects.index', [
                       'academic' => request('academic', $acsem->id)
                   ]) }}">
                    <i class="mdi mdi-book-outline"></i> Kelola mapel
                </a>

                <a class="list-group-item list-group-item-action text-black"
                   href="{{ route('administration::employees.teachers.index') }}">
                    <i class="mdi mdi-account-circle-outline"></i> Data guru
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
