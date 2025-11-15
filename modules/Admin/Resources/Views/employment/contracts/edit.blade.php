@extends('admin::layouts.default')

@section('title', 'Ubah perjanjian kerja | ')
@section('navtitle', 'Ubah perjanjian kerja')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('admin::employment.contracts.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Buat perjanjian kerja baru</h2>
                    <div class="text-secondary">Anda dapat menambahkan perjanjian kerja dengan mengisi formulir di bawah</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('admin::employment.contracts.update', ['contract' => $contract->id, 'next' => request('next')]) }}" method="POST" enctype="multipart/form-data"> @csrf @method('PUT')
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama karyawan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select class="form-select @error('employee_id') is-invalid @enderror" name="employee_id" required>
                                    @isset($employee)
                                        <option value="{{ $employee->id }}" selected>{{ $employee->user->name }}</option>
                                    @endisset
                                </select>
                                @error('employee_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Jenis perjanjian kerja</label>
                            <div class="col-xl-6 col-xxl-5">
                                <select class="form-select @error('contract_id') is-invalid @enderror" name="contract_id" required>
                                    <option value="">-- Pilih jenis perjanjian kerja --</option>
                                    @foreach ($cmpcontracts as $cmpcontract)
                                        <option value="{{ $cmpcontract->id }}" @selected($cmpcontract->id == $contract->contract_id)>{{ $cmpcontract->name }}</option>
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
                                <input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd', $contract->kd) }}" required />
                                @error('kd')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Masa berlaku</label>
                            <div class="col-xl-8 col-xxl-6">
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ old('start_at', $contract->start_at) }}" required />
                                    <input type="datetime-local" class="form-control @error('en_at') is-invalid @enderror" name="end_at" value="{{ old('end_at', $contract->end_at) }}" />
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
                                    <input class="form-control" name="contract_file" type="file" id="upload-input" accept="application/pdf">
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
                                        <input class="btn-check" type="radio" id="work_location{{ $v->value }}" name="work_location" value="{{ $v->value }}" required autocomplete="off" @checked($contract->work_location == $v->value)>
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
                    fetch('{{ route('api::admin.employees.search') }}?q=' + encodeURIComponent(q))
                        .then(response => response.json())
                        .then(json => {
                            callback(json.employees);
                        }).catch(() => {
                            callback();
                        });
                }
            });
        });
    </script>
@endpush
