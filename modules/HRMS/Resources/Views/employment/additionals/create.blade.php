@extends('hrms::layouts.default')

@section('title', 'Tambah pekerjaan | ')
@section('navtitle', 'Tambah pekerjaan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-10 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah pekerjaan tambahan baru</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat pekerjaan tambahan baru</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('hrms::employment.employees.additional.store', ['employee' => $employee->id, 'next' => request('next')]) }}" method="POST"> @csrf
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama karyawan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control" value="{{ $employee->user->name }}" readonly />
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Pekerjaan tambahan</label>
                            <div class="col-xl-8 col-xxl-5">
                                <select type="text" class="form-select @error('additional_possition') is-invalid @enderror" name="additional_possition" value="{{ old('additional_possition') }}" required>
                                    <option value="">-- Pilih jabatan --</option>
                                    @foreach ($departments as $department)
                                        <optgroup label="{{ $department->name }}">
                                            @foreach ($department->positions as $_position)
                                                <option value="{{ $_position->id }}" @selected(old('additional_possition') == $_position->id)>{{ $_position->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('position_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Hari kerja</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select name="additional_days" id="additional_days" class="form-select">
                                    <option value="">-- Pilih hari --</option>
                                    <optgroup label="Hari kerja">
                                        <option value="1">Senin</option>
                                        <option value="2">Selasa</option>
                                        <option value="3">Rabu</option>
                                        <option value="4">Kamis</option>
                                        <option value="5">Jumat</option>
                                    <optgroup label="Weekend">
                                        <option value="6">Sabtu</option>
                                        <option value="0">Minggu</option>
                                </select>
                                @error('additional_days')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Masa berlaku</label>
                            <div class="col-xl-8 col-xxl-6">
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" required />
                                    <input type="datetime-local" class="form-control @error('en_at') is-invalid @enderror" name="end_at" />
                                </div>
                                <div><small class="text-muted d-block"> Masa berlaku mengikuti masa perjanjian kerja yang dipilih </small></div>
                                @if ($errors->has('start_at', 'end_at'))
                                    <small class="text-danger d-block"> {{ $errors->first('start_at') ?: $errors->first('end_at') }} </small>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
