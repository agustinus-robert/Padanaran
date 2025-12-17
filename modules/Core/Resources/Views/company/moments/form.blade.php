@extends('layouts.horizontal-layout')

@php
    $isEdit = isset($moment);
@endphp

@section('title', ($isEdit ? 'Ubah' : 'Tambah') . ' hari libur | ')
@section('navtitle', 'Hari libur')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')

@include('components.navbar-admin')

<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-10">

        <div class="card shadow-sm">
            <div class="card-header bg-gradient-dark text-white">
                <h6 class="text-white">Hari Libur</h6>
            </div>

            <div class="card-body">

                {{-- FORM --}}
                <form
                    class="form-block"
                    method="POST"
                    action="{{ $isEdit
                        ? route('core::company.moments.update', ['moment' => $moment->id, 'next' => request('next')])
                        : route('core::company.moments.store', ['next' => request('next')])
                    }}"
                >
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Tipe --}}
                    <x-input-group :isRow="true" required>
                        <x-col size="4" xl="3">
                            <x-label value="Tipe hari libur" />
                        </x-col>
                        <x-col size="8" xl="6">
                            <x-select
                                name="type"
                                placeholder="-- Pilih tipe hari libur --"
                                :options="collect($types)->map(fn($t) => [
                                    'value' => $t->value,
                                    'label' => $t->label(),
                                ])"
                                :value="old('type', $moment->type->value ?? null)"
                                required
                            />
                        </x-col>
                    </x-input-group>

                    {{-- Nama --}}
                    <x-input-group :isRow="true" required>
                        <x-col size="4" xl="3">
                            <x-label value="Nama hari libur" />
                        </x-col>
                        <x-col size="8" xl="6">
                            <x-input
                                name="name"
                                :value="old('name', $moment->name ?? null)"
                                required
                            />
                        </x-col>
                    </x-input-group>

                    {{-- Tanggal --}}
                    <x-input-group :isRow="true">
                        <x-col size="4" xl="3">
                            <x-label value="Tanggal" />
                        </x-col>
                        <x-col size="6" xl="4">
                            <x-input
                                type="date"
                                name="date"
                                :value="old('date', $moment->date ?? null)"
                            />
                        </x-col>
                    </x-input-group>

                    {{-- Is Holiday --}}
                    <x-input-group :isRow="true">
                        <x-col size="4" xl="3">
                            <x-label value="Tetapkan sebagai libur?" />
                        </x-col>
                        <x-col size="6" xl="4" class="pt-2">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="is_holiday"
                                    name="is_holiday"
                                    value="1"
                                    @checked(old('is_holiday', $moment->is_holiday ?? false))
                                >
                                <label class="form-check-label" for="is_holiday">
                                    <strong>
                                        <span id="is_holiday-text">
                                            {{ old('is_holiday', $moment->is_holiday ?? false)
                                                ? 'Ya, tetapkan sebagai hari libur'
                                                : 'Tidak, tanggal tersebut tetap hari kerja'
                                            }}
                                        </span>
                                    </strong>
                                </label>
                            </div>
                        </x-col>
                    </x-input-group>

                    {{-- Religion --}}
                    <x-input-group :isRow="true">
                        <x-col size="4" xl="3">
                            <x-label value="Tanggal default penghitung THR?" />
                        </x-col>
                        <x-col size="6" xl="4" class="pt-2">
                            @foreach ($religions as $key => $religion)
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="religion-{{ $key }}"
                                        name="religion[]"
                                        value="{{ $religion->value }}"
                                        @checked(in_array(
                                            $religion->value,
                                            old('religion', $moment->meta?->religion ?? [])
                                        ))
                                    >
                                    <label class="form-check-label" for="religion-{{ $key }}">
                                        <strong>{{ $religion->label() }}</strong>
                                    </label>
                                </div>
                            @endforeach
                        </x-col>
                    </x-input-group>

                    {{-- Action --}}
                    <div class="row mt-4">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3 d-flex gap-2">
                            <x-btn type="submit" variant="dark">
                                <i class="mdi mdi-check"></i>
                                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
                            </x-btn>

                            <x-btn
                                variant="light"
                                href="{{ request('next', route('core::company.moments.index')) }}"
                            >
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </x-btn>
                        </div>
                    </div>

                </form>
                {{-- END FORM --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkbox = document.getElementById('is_holiday');
    const text = document.getElementById('is_holiday-text');

    if (!checkbox) return;

    checkbox.addEventListener('change', () => {
        text.innerText = checkbox.checked
            ? 'Ya, tetapkan sebagai hari libur'
            : 'Tidak, tanggal tersebut tetap hari kerja';
    });
});
</script>
@endpush
