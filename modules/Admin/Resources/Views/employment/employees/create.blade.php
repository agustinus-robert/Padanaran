@extends('admin::layouts.default')

@section('title', 'Tambah karyawan baru | ')
@section('navtitle', 'Tambah karyawan baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('admin::employment.employees.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah karyawan baru</h2>
                    <div class="text-secondary">Anda dapat menambahkan karyawan tanpa proses rekrutmen dengan mengisi formulir di bawah</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('admin::employment.employees.store', ['next' => request('next')]) }}" method="POST" enctype="multipart/form-data"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama lengkap</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required />
                                @error('name')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
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
                                    <select class="form-select bg-light @error('phone_code') is-invalid @enderror flex-grow-0" name="phone_code" style="min-width: 100px;" required></select>
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
                                            <div class="text-nowrap me-3">Kontrak kerja</div>
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
                                <select class="form-select @error('contract_id') is-invalid @enderror" name="contract_id" required @if (old('contract') != 1) disabled @endif>
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
                                    @foreach (App\Enums\WorkLocationEnum::cases() as $v)
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
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('admin::employment.employees.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            let {
                data
            } = await axios.get('{{ route('api::references.phones.index') }}');
            for (code in data.data) {
                for (index in data.data[code]) {
                    let number = data.data[code][index];
                    let option = document.createElement('option');
                    option.value = number;
                    option.innerHTML = `+${number}`;

                    if (number == '{{ old('phone_code', 62) }}') {
                        option.selected = 'selected'
                    }

                    document.querySelector('[name="phone_code"]').appendChild(option);
                }
            }

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
