@extends('layouts.horizontal-layout')

@section('title', isset($employee) ? 'Edit Karyawan | ' : 'Tambah Karyawan Baru | ')
@section('navtitle', isset($employee) ? 'Edit Karyawan' : 'Tambah Karyawan Baru')
@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
@include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
<div class="row container-fluid justify-content-center">
    @include('components.navbar-admin')

    <div class="col-xxl-8 col-xl-10">
        <div class="card mb-4 border-0 shadow-sm">
            <x-card-header type="{{ config('theme.default') }}">
                {{ isset($employee) ? 'Edit Karyawan' : 'Tambah Karyawan Baru' }}
            </x-card-header>

            <div class="card-body">
                <form class="form-block"
                      action="{{ isset($employee)
                          ? route('hrms::employment.employees.update', $employee->id)
                          : route('hrms::employment.employees.store', ['next' => request('next')]) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @isset($employee)
                        @method('PUT')
                    @endisset

                    {{-- User info / personal --}}
                    @if(request('user') == 1)
                        <x-input-group label="Nama pengguna" required>
                            <x-select-2 name="user_id" :selected="old('user_id', $employee?->user_id ?? '')" placeholder="Cari nama disini ..." />
                        </x-input-group>
                    @else
                        <x-input-group label="Nama lengkap guru" required>
                            <x-input name="name" :value="old('name', $employee?->user?->name ?? '')" placeholder="Nama guru" />
                        </x-input-group>

                        <x-input-group label="NIK">
                            <x-input type="number" name="nik" :value="old('nik', $employee?->nik ?? '')" placeholder="NIK" />
                        </x-input-group>

                        <x-input-group label="Tempat lahir">
                            <x-input name="pob" :value="old('pob', $employee?->pob ?? '')" placeholder="Tempat lahir" />
                        </x-input-group>

                        <x-input-group label="Tanggal lahir">
                            <x-input
                                type="date"
                                name="dob"
                                :value="old('dob', isset($employee) && $employee->dob ? $employee->dob->format('Y-m-d') : '')"
                                placeholder="Tanggal lahir"
                            />
                        </x-input-group>


                        <x-input-group label="Jenis kelamin">
                            <x-radio-group :required="true" name="sex" :options="\Modules\Account\Models\UserProfile::$sex" :selected="old('sex', $employee?->sex ?? -1)" />
                        </x-input-group>
                    @endif

                    <x-input-group label="Username" required>
                        <x-input name="username" :value="old('username', $employee?->user?->username ?? '')" />
                        <small class="text-muted d-block">Sandi akan diberikan otomatis dari sistem setelah menyimpan data ini</small>
                    </x-input-group>

                   <x-input-group :isRow="true" label="Nomor ponsel" required>
                        <x-col size="6">
                            <div class="d-flex gap-2">
                                <x-col size="2">
                                    <x-select
                                        name="phone_code"
                                        :value="old('phone_code', isset($employee) && $employee->user ? $employee->user->getMeta('phone_code', '62') : '62')"
                                        :options="[
                                            ['value' => '62', 'label' => '+62']
                                        ]"
                                    />
                                </x-col>
                                <x-col size="12">
                                    <x-input
                                        type="text"
                                        name="phone_number"
                                        :value="old('phone_number', isset($employee) && $employee->user ? $employee->user->getMeta('phone_number') : '')"
                                        required
                                    />
                                </x-col>
                            </div>
                        </x-col>
                    </x-input-group>


                    <x-input-group label="Tanggal bergabung" required>
                        <x-input type="datetime-local" name="joined_at" :value="old('joined_at', isset($employee) && $employee->user ? $employee?->joined_at?->toDateTimeLocalString() : '')" />
                    </x-input-group>

                    {{-- Kontrak --}}
                    <div class="row mb-3 mt-4">
                        <div class="offset-lg-4 offset-xl-3 col-xl-9 col-lg-8">
                            <div class="row">
                                <div class="flex-grow-1 col-auto">
                                    <div class="text-secondary d-flex align-items-center flex-row">
                                        <div class="me-3 text-nowrap">Kontrak kerja</div>
                                        <div class="flex-grow-1 bg-light" style="height: 1px;"></div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="skip_contract" name="contract" value="1" @checked(old('contract', 1) == 1 && !old('contract_id'))>
                                        <label class="form-check-label" for="skip_contract">Lewati langkah ini</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-input-group label="Jenis perjanjian kerja" required>
                        <x-select name="contract_id" :options="$contracts" :value="old('contract_id', $employee?->contract_id ?? '')" :disabled="old('contract') != 1" placeholder="-- Pilih jenis perjanjian kerja --" />
                    </x-input-group>

                    <x-input-group label="Nomor perjanjian kerja" required>
                        <x-input name="kd" :value="old('kd', $employee?->kd ?? '')" :disabled="old('contract') != 1" />
                    </x-input-group>

                    <x-input-group label="Masa berlaku" required>
                        <div class="input-group">
                            <x-input type="datetime-local" name="start_at" :value="old('start_at', isset($employee) && $employee->start_at ? $employee?->start_at?->toDateTimeLocalString() : '')" :disabled="old('contract') != 1" />
                            <x-input type="datetime-local" name="end_at" :value="old('end_at', isset($employee) && $employee->end_at ? $employee?->end_at?->toDateTimeLocalString() : '')" :disabled="old('contract') != 1" />
                        </div>
                    </x-input-group>

                    <x-input-group label="Dokumen perjanjian kerja" required>
                        <x-input type="file" name="contract_file" :disabled="old('contract') != 1" />
                    </x-input-group>

                    <x-input-group label="Lokasi kerja" required>
                        <x-radio-group
                            name="work_location"
                            :options="collect(\Modules\Core\Enums\WorkLocationEnum::cases())->mapWithKeys(fn($v) => [$v->value => $v->name])->toArray()"
                            :selected="old('work_location', $employee?->work_location ?? '')"
                        />
                    </x-input-group>


                   <div class="row mb-3 align-items-center">
                        <label class="col-md-3 col-form-label">Konfirmasi</label>
                        <div class="col-md-7 gap-2">
                            <input class="form-check-input" id="agreement" type="checkbox" required>
                            <label class="form-check-label mb-0" for="agreement">
                                Dengan ini saya menyatakan data di atas adalah valid
                            </label>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3 d-flex gap-2">
                            <x-btn variant="dark">
                                Simpan</x-btn>
                            <a class="btn btn-light text-dark" href="{{ request('next', route('hrms::employment.employees.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", async () => {
        const toggleContractForm = () => {
            ['[name="contract_id"]', '[name="kd"]', '[name="start_at"]', '[name="end_at"]', '[name="contract_file"]'].forEach((selector) => {
                document.querySelectorAll(selector).forEach(e => e.disabled = document.getElementById('skip_contract').checked ? true : false)
            });
        }
        document.getElementById('skip_contract').addEventListener('change', toggleContractForm);
        toggleContractForm();
    });
</script>
@endpush

@push('style')
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
@endpush

@endsection
