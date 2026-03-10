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

@php
        $academicId = request('academic', $acsem->id ?? null);
        $extraMenus = [
            [
                'label' => 'Kelola Rombel',
                'route' => route('administration::scholar.classrooms.index', ['academic' => $academicId]),
                'icon' => 'groups',
                'icon_class' => 'text-primary'
            ],
            [
                'label' => 'Kelola Mapel',
                'route' => route('administration::curriculas.subjects.index', ['academic' => $academicId]),
                'icon' => 'menu_book',
                'icon_class' => 'text-info'
            ],
            [
                'label' => 'Data Guru',
                'route' => route('administration::employees.teachers.index'),
                'icon' => 'person_pin',
                'icon_class' => 'text-warning'
            ],
        ];
    @endphp

@push('additional-content')
    <x-sidebar-card title="Tahun Ajaran" icon="calendar_month" type="form">
        <form action="{{ isset($acsem) ? '#' : route('administration::curriculas.meets.index') }}" method="GET">
            <div class="d-flex gap-2">
                <div class="flex-grow-1">
                    @isset($acsem)
                        {{-- ========== EDIT MODE ========== --}}
                        <select class="form-select form-select-sm border ps-2" style="font-size: 12px;" disabled>
                            <option value="{{ $acsem->id }}">{{ $acsem->full_name }}</option>
                        </select>
                        <input type="hidden" name="academic" value="{{ $acsem->id }}">
                    @else
                        {{-- ========== CREATE MODE ========== --}}
                        <select name="academic" class="form-select form-select-sm border ps-2" style="font-size: 12px;">
                            @foreach($acsems as $_acsem)
                                <option value="{{ $_acsem->id }}" @selected(request('academic') == $_acsem->id)>
                                    {{ $_acsem->full_name }}
                                </option>
                            @endforeach
                        </select>
                    @endisset
                </div>
                
                {{-- Tombol hanya aktif jika tidak dalam mode edit (atau sesuai kebutuhan) --}}
                <button type="submit" class="btn btn-sm bg-gradient-dark mb-0 px-3" {{ isset($acsem) ? 'disabled' : '' }}>
                    Set
                </button>
            </div>
        </form>
    </x-sidebar-card>

    {{-- Menu Lanjutan --}}

    <x-sidebar-card title="Lanjutan" icon="settings_input_component" :items="$extraMenus" />
@endpush

@section('body-content')

@include('components.navbar-admin')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <x-card-header type="{{ config('theme.default') }}">
                    {{ isset($meet) ? 'Edit Pertemuan' : 'Tambah Pertemuan' }}
                </x-card-header>

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
    </div>
</div>
@endsection
