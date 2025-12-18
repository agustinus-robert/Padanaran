@extends('layouts.horizontal-layout')

@section('title', isset($contract->id) ? 'Ubah perjanjian kerja' : 'Buat perjanjian kerja | ')
@section('navtitle', isset($contract->id) ? 'Ubah perjanjian kerja' : 'Buat perjanjian kerja')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
@include('hrms::layouts.includes.navbar-hrms')
@endpush

@php
    $contract = $contract ?? new \Modules\HRMS\Models\EmployeeContract();
@endphp

@section('body-content')
    @include('components.navbar-admin')

    <div class="row container-fluid justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="card mb-4 border-0 shadow-sm">
                <x-card-header type="{{ config('theme.default') }}">
                    {{ isset($contract->id) ? 'Edit Perjanjian Kerja' : 'Tambah Perjanjian Kerja Baru' }}
                </x-card-header>

                <div class="card-body">
                    <form class="form-block"
                        action="{{ isset($contract->id)
                                    ? route('hrms::employment.contracts.update', ['contract' => $contract->id, 'next' => request('next')])
                                    : route('hrms::employment.contracts.store', ['next' => request('next')]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($contract->id)
                            @method('PUT')
                        @endisset

                        {{-- Nama Karyawan --}}
                        <x-input-group required>
                            <x-label>Nama karyawan</x-label>
                            <x-select
                                name="employee_id"
                                :value="old('employee_id', $contract->employee_id)"
                                :options="isset($employee) ? [['value' => $employee->id, 'label' => $employee->user->name]] : []"
                                placeholder="-- Pilih karyawan --"
                                required
                            />
                            @error('employee_id') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Jenis Perjanjian Kerja --}}
                        <x-input-group required>
                            <x-label>Jenis perjanjian kerja</x-label>
                            <x-select
                                name="contract_id"
                                :value="old('contract_id', $contract->contract_id)"
                                :options="isset($cmpcontracts) ? $cmpcontracts->map(fn($c) => ['value' => $c->id, 'label' => $c->name])->toArray() : []"
                                placeholder="-- Pilih jenis perjanjian kerja --"
                                required
                            />
                            @error('contract_id') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Nomor Perjanjian Kerja --}}
                        <x-input-group required>
                            <x-label>Nomor perjanjian kerja</x-label>
                            <x-input name="kd" :value="old('kd', $contract->kd)" required />
                            @error('kd') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Masa Berlaku --}}
                        <x-input-group :isRow="true" required>
                            <x-label>Masa berlaku</x-label>
                            <x-col size="6">
                                <x-input type="datetime-local" name="start_at" :value="old('start_at', $contract->start_at?->format('Y-m-d\TH:i'))" required />
                            </x-col>
                            <x-col size="6">
                                <x-input type="datetime-local" name="end_at" :value="old('end_at', $contract->end_at?->format('Y-m-d\TH:i'))" />
                            </x-col>
                            @if ($errors->has('start_at', 'end_at'))
                                <x-error>{{ $errors->first('start_at') ?: $errors->first('end_at') }}</x-error>
                            @endif
                        </x-input-group>

                        {{-- Dokumen --}}
                        <x-input-group :isRow="true">
                            <x-label>Dokumen perjanjian kerja</x-label>
                            <x-input-file name="contract_file" accept="application/pdf" />
                            @error('contract_file') <x-error>{{ $message }}</x-error> @enderror
                        </x-input-group>

                        {{-- Lokasi Kerja --}}
                        <x-input-group :isRow="true" required>
                            <x-label>Lokasi kerja</x-label>
                            <x-radio-group
                                name="work_location"
                                :options="collect(\Modules\Core\Enums\WorkLocationEnum::cases())->mapWithKeys(fn($v) => [$v->value => $v->name])->toArray()"
                                :selected="old('work_location', $contract->work_location)"
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
                            <x-btn variant="dark"> Simpan</x-btn>
                            <a class="btn btn-light text-dark" href="{{ request('next', route('hrms::employment.employees.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", async () => {
    new TomSelect('[name="employee_id"]', {
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        load: function(q, callback) {
            fetch('{{ route('api::hrms.employees.search') }}?q=' + encodeURIComponent(q))
                .then(response => response.json())
                .then(json => callback(json.employees))
                .catch(() => callback());
        }
    });
});
</script>
@endpush
