@extends('layouts.horizontal-layout')

@section('title', isset($subject) ? 'Edit Mapel - ' : 'Tambah Mapel - ')
@section('titleTemplate', config('account.admin.name'))

@section('navtitle', 'Mapel')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item">
        <a href="{{ request('next', route('administration::curriculas.subjects.index')) }}">
            Mapel
        </a>
    </li>
    <li class="breadcrumb-item active">
        {{ isset($subject) ? 'Edit' : 'Tambah' }}
    </li>
@endsection

@push('nav')
    @include('administration::layouts.includes.navbar-administration')
@endpush

@push('additional-content')
    <x-sidebar-card title="Tahun Ajaran" icon="calendar_month" type="form">
        <form action="{{ route('administration::curriculas.subjects.create') }}" method="GET">
            <div class="d-flex gap-2">
                <div class="flex-grow-1">
                    {{-- Menggunakan select standar agar lebih ringan di sidebar --}}
                    <select name="academic" class="form-select form-select-sm border ps-2" style="font-size: 12px;" disabled>
                        @foreach($acsems as $item)
                            <option value="{{ $item->id }}" @selected(request('academic', $acsem->id) == $item->id)>
                                {{ $item->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm bg-gradient-dark mb-0 px-3" disabled>Terapkan</button>
            </div>
            @if(isset($acsem))
                <input type="hidden" name="academic" value="{{ request('academic', $acsem->id) }}">
            @endif
        </form>
    </x-sidebar-card>
@endpush

@include('components.navbar-admin')

@section('body-content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">

                <x-card-header type="{{ config('theme.default') }}">
                    {{ isset($subject) ? 'Edit Mapel' : 'Tambah Mapel' }}
                </x-card-header>

                <div class="card-body">

                    <form class="form-block"
                        action="{{ isset($subject)
                            ? route('administration::curriculas.subjects.update', $subject->id)
                            : route('administration::curriculas.subjects.store', [
                                'semester_id' => $acsem->id,
                                'next' => request('next', route('administration::curriculas.subjects.index', [
                                    'academic' => request('academic', $acsem->id)
                                ]))
                            ]) }}"
                        method="POST">

                        @csrf
                        @isset($subject)
                            @method('PUT')
                        @endisset

                        {{-- Tahun Ajaran --}}
                        <x-input-group>
                            <x-label value="Tahun ajaran" col="3" />
                            <x-col size="5">
                                <strong>
                                    <span class="form-control-plaintext">
                                        {{ $subject->semester->full_name ?? '' }}
                                    </span>
                                </strong>
                            </x-col>
                        </x-input-group>

                        {{-- Kode Mapel --}}
                        <x-input-group label="Kode mapel" required>
                            <x-input
                                name="kd"
                                placeholder="Kode mapel"
                                :value="old('kd', $subject->kd ?? null)"
                            />
                        </x-input-group>

                        {{-- Nama Mapel --}}
                        <x-input-group label="Nama mapel" required>
                            <x-input
                                name="name"
                                placeholder="Nama mapel"
                                :value="old('name', $subject->name ?? null)"
                            />
                        </x-input-group>

                        {{-- Kelas --}}
                        <x-input-group label="Kelas" required>
                            <x-select
                                name="level_id"
                                placeholder="-- Pilih kelas --"
                                :value="old('level_id', $subject->level_id ?? null)"
                                :options="$levels->map(fn($level) => [
                                    'value' => $level->id,
                                    'label' => $level->kd.' - '.$level->name
                                ])"
                            />
                        </x-input-group>

                        {{-- Kategori --}}
                        <x-input-group label="Kategori mapel">
                            <x-select
                                name="category_id"
                                placeholder="-- Pilih kategori --"
                                :value="old('category_id', $subject->category_id ?? null)"
                                :options="$categories->map(fn($cat) => [
                                    'value' => $cat->id,
                                    'label' => $cat->name
                                ])"
                            />
                        </x-input-group>

                        {{-- Nilai KKM --}}
                        <x-input-group label="Nilai KKM" required>
                            <x-input
                                type="number"
                                name="score_standard"
                                placeholder="Nilai KKM"
                                :value="old('score_standard', $subject->score_standard ?? null)"
                            />
                        </x-input-group>

                        {{-- Warna --}}
                        <x-input-group label="Warna">
                            <x-input
                                type="color"
                                name="color_id"
                                :value="old('color_id', $subject->color_id ?? '#ffffff')"
                            />
                        </x-input-group>

                        {{-- Tombol --}}
                        <x-input-group>
                            <x-col size="12" offset="3">
                                <x-btn type="submit" variant="success">
                                    {{ isset($subject) ? 'Update' : 'Simpan' }}
                                </x-btn>

                                <a class="btn btn-secondary"
                                    href="{{ request('next', route('administration::curriculas.subjects.index', [
                                        'academic' => isset($subject)
                                            ? $subject->semester_id
                                            : $acsem->id
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
