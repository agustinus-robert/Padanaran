@extends('layouts.horizontal-layout')

@section('title', isset($position) ? 'Ubah Jabatan | ' : 'Buat Jabatan | ')
@section('navtitle', isset($position) ? 'Ubah Jabatan' : 'Buat Jabatan')

@push('nav')
@include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid justify-content-center">
        <div class="col-xxl-8 col-xl-10">

            <div class="card mb-4 border-0 shadow-sm">
                <x-card-header type="{{ config('theme.default') }}">
                    {{ isset($position->id) ? 'Edit Jabatan' : 'Tambah Jabatan' }}
                </x-card-header>

                <div class="card-body">
                    <form
                        class="form-block"
                        action="{{ isset($position) ? route('hrms::employment.contract-positions.update', ['position' => $position->id, 'next' => request('next', url()->previous())]) : route('hrms::employment.contracts.positions.store', ['contract' => $contract->id, 'next' => request('next')]) }}"
                        method="POST"
                    >
                        @csrf
                        @if(isset($position)) @method('PUT') @endif

                        {{-- Nama Karyawan --}}
                        <x-input-group>
                            <x-label>Nama karyawan</x-label>
                            <x-input type="text" :value="$position->employee->user->name ?? ''" />
                        </x-input-group>

                        {{-- Nomor Kontrak --}}
                        <x-input-group>
                            <x-label>Nomor kontrak</x-label>
                            <x-input type="text" :value="$position->contract->kd ?? ''" />
                        </x-input-group>

                        {{-- Jabatan --}}
                        <x-input-group required>
                            <x-label>Nama jabatan</x-label>
                            <x-select
                                name="position_id"
                                :value="old('position_id', $position->position_id ?? '')"
                                :options="$departments->flatMap(fn($d) => $d->positions->map(fn($p) => ['value' => $p->id, 'label' => $d->name . ' - ' . $p->name]))->toArray()"
                                placeholder="-- Pilih jabatan --"
                                required
                            />
                            @error('position_id') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Masa Berlaku --}}
                        <x-input-group :isRow="true" required>
                            <x-label>Masa berlaku</x-label>
                            <x-col size="6">
                                <x-input type="datetime-local" name="start_at" :value="old('start_at', isset($position) && $position->start_at ? $position->start_at->format('Y-m-d\TH:i') : '')" required />
                            </x-col>
                            <x-col size="6">
                                <x-input type="datetime-local" name="end_at" :value="old('end_at', isset($position) && $position->end_at ? $position->end_at->format('Y-m-d\TH:i') : '')" />
                            </x-col>
                            @if ($errors->has('start_at', 'end_at'))
                                <x-error>{{ $errors->first('start_at') ?: $errors->first('end_at') }}</x-error>
                            @endif
                            <small class="text-muted d-block">Masa berlaku mengikuti masa perjanjian kerja yang dipilih</small>
                        </x-input-group>

                        {{-- Tombol --}}
                        <div class="mt-3">
                            <x-btn type="submit" variant="dark">
                                <i class="mdi mdi-check"></i> {{ isset($position) ? 'Update' : 'Simpan' }}
                            </x-btn>
                            <a class="btn btn-light text-dark" href="{{ request('next', url()->previous()) }}">
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
