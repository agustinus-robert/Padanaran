@extends('hrms::layouts.default')

@section('title', 'Buat perjanjian kerja baru | ')
@section('navtitle', 'Buat perjanjian kerja baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('hrms::employment.contracts.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Buat adendum perjanjian kerja</h2>
                    <div class="text-secondary">Anda dapat menambahkan adendum perjanjian kerja dengan mengisi formulir di bawah</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Adendum perjanjian kerja <strong>{{ $contract->employee->user->name }}</strong> {{ $contract->kd }}
                </div>
                <form class="form-block" action="{{ route('hrms::employment.contracts.addendum.store', ['contract' => $contract->id, 'next' => request('next')]) }}" method="POST" enctype="multipart/form-data"> @csrf
                    <div class="card-body">
                        <div class="builder-form">
                            <div class="row required mb-3">
                                <label class="col-lg-4 col-xl-3 col-form-label">Nomor adendum</label>
                                <div class="col-xl-8 col-xxl-6">
                                    <input type="text" class="form-control @error('addendum_kd') is-invalid @enderror" name="addendum_kd" value="{{ old('addendum_kd') }}" required />
                                    @error('addendum_kd')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row required mb-3">
                                <label class="col-lg-4 col-xl-3 col-form-label">Jenis adendum perjanjian kerja</label>
                                <div class="col-xl-6 col-xxl-5">
                                    <select class="form-select @error('contract_id') is-invalid @enderror" name="contract_id" required>
                                        <option value="">-- Pilih jenis perjanjian kerja --</option>
                                        @foreach ($cmpcontracts as $cmpcontract)
                                            <option value="{{ $cmpcontract->id }}" @selected($cmpcontract->id == old('contract_id', $contract->contract_id))>{{ $cmpcontract->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('contract_id')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-4 col-xl-3 col-form-label">Nama jabatan</label>
                                <div class="col-xl-8 col-xxl-5">
                                    <select type="text" class="form-select @error('position_id') is-invalid @enderror" name="position_id" value="{{ old('position_id') }}">
                                        <option value="">-- Pilih jabatan --</option>
                                        @foreach ($departments as $department)
                                            <optgroup label="{{ $department->name }}">
                                                @foreach ($department->positions as $position)
                                                    <option value="{{ $position->id }}" @selected(old('position_id'))>{{ $position->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-4 col-xl-3 col-form-label">Masa berlaku adendum</label>
                                <div class="col-xl-8 col-xxl-6">
                                    <div class="input-group">
                                        <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ old('start_at') }}" required />
                                        <input type="datetime-local" class="form-control @error('en_at') is-invalid @enderror" name="end_at" value="{{ old('end_at') }}" />
                                    </div>
                                    @if ($errors->has('start_at', 'end_at'))
                                        <small class="text-danger d-block"> {{ $errors->first('start_at') ?: $errors->first('end_at') }} </small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-4 col-xl-3 col-form-label">Dokumen adendum</label>
                                <div class="col-xl-8 col-xxl-6">
                                    <div class="mb-1">
                                        <input class="form-control" name="addendum_file" type="file" id="upload-input" accept="application/pdf">
                                    </div>
                                    @error('addendum_file')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Lokasi kerja</label>
                                <div class="col-md-4">
                                    <div class="btn-group">
                                        @foreach (Modules\Core\Enums\WorkLocationEnum::cases() as $v)
                                            <input class="btn-check" type="radio" id="work_location{{ $v->value }}" name="work_location" value="{{ $v->value }}" required autocomplete="off" @checked(old('work_location', $contract->work_location->value) == $v->value)>
                                            <label class="btn btn-outline-secondary text-dark" for="work_location{{ $v->value }}">{{ $v->name }}</label>
                                        @endforeach
                                    </div>
                                    @error('work_location')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="agreement" type="checkbox" required>
                                    <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between gy-2 mb-3">
                            <div class="col-sm-6 order-sm-first">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('hrms::employment.employees.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
