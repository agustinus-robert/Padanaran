@extends('hrms::layouts.default')

@section('title', 'Tambah karyawan baru | ')
@section('navtitle', 'Tambah karyawan baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('hrms::employment.employees.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah karyawan baru</h2>
                    <div class="text-secondary">Anda dapat menambahkan karyawan tanpa proses rekrutmen dengan mengisi formulir di bawah</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('hrms::employment.employees.store', ['next' => request('next')]) }}" method="POST" enctype="multipart/form-data"> @csrf
                        <div class="form-group required row mb-3">
                            <label for="acdmc_id" class="col-md-3 col-form-label">Tahun ajaran masuk</label>
                            <div class="col-md-5">
                                <select class="form-control @error('acdmc_id') is-invalid @enderror" name="acdmc_id" required id="acdmc_id">
                                    @foreach ($acdmcs as $acdmc)
                                        <option value="{{ $acdmc->id }}" @if (old('acdmc_id') == $acdmc->id) selected @endif>{{ $acdmc->name }}</option>
                                    @endforeach
                                </select>
                                @error('acdmc_id')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group required row mb-3">
                            <label for="nip" class="col-md-3 col-form-label">NIP</label>
                            <div class="col-md-4">
                                <input type="number" name="nip" required class="form-control @error('nip') is-invalid @enderror" id="nip" placeholder="NIP" value="{{ old('nip') }}">
                                @error('nip')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="nuptk" class="col-md-3 col-form-label">NUPTK</label>
                            <div class="col-md-4">
                                <input type="number" name="nuptk" class="form-control @error('nuptk') is-invalid @enderror" id="nuptk" placeholder="NUPTK" value="{{ old('nuptk') }}">
                                @error('nuptk')
                                    <small class="invalid-feedback"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        @if (request('user') == 1)
                            <div class="form-group required row">
                                <label for="user_id" class="col-md-3 col-form-label">Nama pengguna</label>
                                <div class="col-md-7">
                                    <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required data-placeholder="Cari nama disini ...">
                                    </select>
                                    @error('user_id')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="form-group required row mb-3">
                                <label for="name" class="col-md-3 col-form-label">Nama lengkap guru</label>
                                <div class="col-md-7">
                                    <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama guru" value="{{ old('name') }}">
                                    @error('name')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="nik" class="col-md-3 col-form-label">NIK</label>
                                <div class="col-md-6">
                                    <input type="number" name="nik" class="form-control @error('nik') is-invalid @enderror" id="nik" placeholder="NIK" value="{{ old('nik') }}">
                                    @error('nik')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="pob" class="col-md-3 col-form-label">Tempat lahir</label>
                                <div class="col-md-5">
                                    <input type="text" name="pob" class="form-control @error('pob') is-invalid @enderror" id="pob" placeholder="Tempat lahir" value="{{ old('pob') }}">
                                    @error('pob')
                                        <small class="invalid-feedback"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="dob" class="col-md-3 col-form-label">Tanggal lahir</label>
                                <div class="col-md-5">
                                    <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" id="dob" placeholder="Tanggal lahir" value="{{ old('dob') }}" data-mask="00-00-0000">
                                    {{-- <small class="form-text text-muted">Format hh-bb-tttt (ex: 23-02-2001)</small> --}}
                                    @error('dob')
                                        <small class="text-danger"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="sex" class="col-md-3 col-form-label">Jenis kelamin</label>
                                <div class="col-md-5">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @foreach (\Modules\Account\Models\UserProfile::$sex as $k => $v)
                                            <label class="btn btn-outline-secondary active">
                                                <input type="radio" name="sex" value="{{ $k }}" autocomplete="off" @if (old('sex', -1) == $k) checked @endif> {{ $v }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('sex')
                                        <small class="text-danger"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                        @endif
                        {{-- <div class="form-group row">
                            <label for="entered_at" class="col-md-3 col-form-label">Tanggal masuk</label>
                            <div class="col-md-5">
                                <input type="text" name="entered_at" class="form-control @error('entered_at') is-invalid @enderror" id="entered_at" placeholder="Tanggal masuk" value="{{ old('entered_at') }}" data-mask="00-00-0000">
                                <small class="form-text text-muted">Format hh-bb-tttt (ex: 23-02-2001)</small>
                                @error('entered_at')
                                    <small class="text-danger"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div> --}}

                        {{-- <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama lengkap</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required />
                                @error('name')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Username</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required />
                                <small class="text-muted d-block">Sandi akan diberikan otomatis dari sistem setelah menyimpan data ini</small>
                                @error('username')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nomor ponsel</label>
                            <div class="col-xl-6 col-xxl-5">
                                <div class="input-group d-flex">
                                    <select class="bg-light @error('phone_code') is-invalid @enderror form-select flex-grow-0" name="phone_code" style="min-width: 100px;" required>
                                        <option value="62">+62</option>
                                    </select>
                                    <input type="number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required data-mask="62#">
                                </div>
                                @error('phone_code')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                                @error('phone_number')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label required">Tanggal bergabung</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="datetime-local" class="form-control @error('joined_at') is-invalid @enderror" name="joined_at" value="{{ old('joined_at') }}" required>
                                @error('joined_at')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
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
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Jenis perjanjian kerja</label>
                            <div class="col-xl-6 col-xxl-5">
                                <select class="@error('contract_id') is-invalid @enderror form-select" name="contract_id" required @if (old('contract') != 1) disabled @endif>
                                    <option value="">-- Pilih jenis perjanjian kerja --</option>
                                    @foreach ($contracts as $contract)
                                        <option value="{{ $contract->id }}" @selected($contract->id == old('contract_id'))>{{ $contract->name }}</option>
                                    @endforeach
                                </select>
                                @error('contract_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nomor perjanjian kerja</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd') }}" required @if (old('contract') != 1) disabled @endif />
                                @error('kd')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Masa berlaku</label>
                            <div class="col-xl-7 col-xxl-5">
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ old('start_at') }}" required @if (old('contract') != 1) disabled @endif />
                                    <input type="datetime-local" class="form-control @error('en_at') is-invalid @enderror" name="end_at" value="{{ old('end_at') }}" required @if (old('contract') != 1) disabled @endif />
                                </div>
                                @if ($errors->has('start_at', 'end_at'))
                                    <small class="text-danger d-block"> {{ $errors->first('start_at') ?: $errors->first('end_at') }} </small>
                                @endif
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Dokumen perjanjian kerja</label>
                            <div class="col-xl-8 col-xxl-6">
                                <div class="mb-1">
                                    <input class="form-control" name="contract_file" type="file" id="upload-input" accept="application/pdf" required>
                                </div>
                                @error('contract_file')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Lokasi kerja</label>
                            <div class="col-md-4">
                                <div class="btn-group">
                                    @foreach (Modules\Core\Enums\WorkLocationEnum::cases() as $v)
                                        <input class="btn-check" type="radio" id="work_location{{ $v->value }}" name="work_location" value="{{ $v->value }}" required autocomplete="off" @checked(old('work_location') == $v->value)>
                                        <label class="btn btn-outline-secondary text-dark" for="work_location{{ $v->value }}">{{ $v->name }}</label>
                                    @endforeach
                                </div>
                                @error('work_location')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="agreement" type="checkbox" required>
                                    <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                                </div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('hrms::employment.employees.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            $(document).ready(function() {
                $('[name="user_id"]').select2({
                    minimumInputLength: 3,
                    theme: 'bootstrap5',
                    ajax: {
                        url: '{{ route('api.getUsers') }}',
                        dataType: 'json',
                        delay: 500,
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", async () => {
                // let {
                //     data
                // } = await axios.get('{{ route('api::references.phones.index') }}');
                // for (code in data.data) {
                //     for (index in data.data[code]) {
                //         let number = data.data[code][index];
                //         let option = document.createElement('option');
                //         option.value = number;
                //         option.innerHTML = `+${number}`;

                //         if (number == '{{ old('phone_code', 62) }}') {
                //             option.selected = 'selected'
                //         }

                //         document.querySelector('[name="phone_code"]').appendChild(option);
                //     }
                // }

                const toggleContractForm = () => {
                    ['[name="contract_id"]', '[name="kd"]', '[name="start_at"]', '[name="end_at"]', '[name="contract_file"]', '[name="work_location"]'].forEach((v) => {
                        if (document.querySelector(v)) {
                            document.querySelectorAll(v).forEach((e) => {
                                e.disabled = document.getElementById('skip_contract').checked ? 'disabled' : ''
                            })
                        }
                    })
                }

                document.getElementById('skip_contract').addEventListener('change', toggleContractForm)

                toggleContractForm();
            });
        </script>
    @endpush

    @push('style')
        <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
    @endpush
@endsection
