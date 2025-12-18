@extends('layouts.horizontal-layout')

@section('title', isset($addendum) ? 'Ubah Adendum | ' : 'Buat Adendum | ')
@section('navtitle', isset($addendum) ? 'Ubah Adendum' : 'Buat Adendum')

@push('nav')
@include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
    @include('components.navbar-admin')
    <div class="row container-fluid justify-content-center">
        <div class="col-xxl-8 col-xl-10">

            <div class="card mb-4 border-0 shadow-sm">
                <x-card-header type="{{ config('theme.default') }}">
                    Adendum perjanjian kerja <strong>{{ $contract->employee->user->name }}</strong> {{ $contract->kd }}
                </x-card-header>

                <div class="card-body">
                    <form
                        class="form-block"
                        action="{{ isset($addendum) ? route('hrms::employment.contracts.addendum.update', ['contract' => $contract->id, 'addendum' => $addendum->id, 'next' => request('next')]) : route('hrms::employment.contracts.addendum.store', ['contract' => $contract->id, 'next' => request('next')]) }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        @if(isset($addendum)) @method('PUT') @endif

                        {{-- Nomor Adendum --}}
                        <x-input-group required>
                            <x-label>Nomor adendum</x-label>
                            <x-input name="addendum_kd" :value="old('addendum_kd', $addendum->addendum_kd ?? '')" required />
                            @error('addendum_kd') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Jenis Adendum --}}
                        <x-input-group required>
                            <x-label>Jenis adendum perjanjian kerja</x-label>
                            <x-select
                                name="contract_id"
                                :value="old('contract_id', $addendum->contract_id ?? $contract->contract_id)"
                                :options="$cmpcontracts->map(fn($c) => ['value' => $c->id, 'label' => $c->name])->toArray()"
                                placeholder="-- Pilih jenis perjanjian kerja --"
                                required
                            />
                            @error('contract_id') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Jabatan --}}
                        <x-input-group>
                            <x-label>Nama jabatan</x-label>
                            <x-select
                                name="position_id"
                                :value="old('position_id', $addendum->position_id ?? null)"
                                :options="$departments->flatMap(fn($d) => $d->positions->map(fn($p) => ['value' => $p->id, 'label' => $d->name . ' - ' . $p->name]))->toArray()"
                                placeholder="-- Pilih jabatan --"
                            />
                            @error('position_id') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Masa Berlaku Adendum --}}
                        <x-input-group :isRow="true" required>
                            <x-label>Masa berlaku adendum</x-label>
                            <x-col size="6">
                                <x-input type="datetime-local" name="start_at" :value="old('start_at', isset($addendum) && $addendum->start_at ? $addendum->start_at->format('Y-m-d\TH:i') : '')" required />
                            </x-col>
                            <x-col size="6">
                                <x-input type="datetime-local" name="end_at" :value="old('end_at', isset($addendum) && $addendum->end_at ? $addendum->end_at->format('Y-m-d\TH:i') : '')" />
                            </x-col>
                            @if ($errors->has('start_at', 'end_at'))
                                <x-error>{{ $errors->first('start_at') ?: $errors->first('end_at') }}</x-error>
                            @endif
                        </x-input-group>

                        {{-- Dokumen Adendum --}}
                        <x-input-group :isRow="true">
                            <x-label>Dokumen adendum</x-label>
                            <x-input-file name="addendum_file" accept="application/pdf" />
                            @error('addendum_file') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Lokasi Kerja --}}
                        <x-input-group :isRow="true" required>
                            <x-label>Lokasi kerja</x-label>
                            <x-radio-group
                                name="work_location"
                                :options="collect(\Modules\Core\Enums\WorkLocationEnum::cases())->mapWithKeys(fn($v) => [$v->value => $v->name])->toArray()"
                                :selected="old('work_location', $addendum->work_location->value ?? $contract->work_location->value)"
                            />
                            @error('work_location') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Konfirmasi --}}
                        <x-input-group :isRow="true" required>
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-check-input" id="agreement" type="checkbox" required>
                                <label class="form-check-label mb-0" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                            </div>
                        </x-input-group>

                        {{-- Tombol --}}
                        <div class="mt-3">
                            <x-btn type="dark"><i class="mdi mdi-check"></i> {{ isset($addendum) ? 'Update' : 'Simpan' }}</x-btn>
                            <a class="btn btn-light text-dark" href="{{ request('next', route('hrms::employment.contracts.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
