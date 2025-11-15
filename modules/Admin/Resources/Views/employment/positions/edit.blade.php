@extends('admin::layouts.default')

@section('title', 'Ubah jabatan | ')
@section('navtitle', 'Ubah jabatan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Ubah jabatan</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk memperbarui jabatan</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('admin::employment.contract-positions.update', ['position' => $position->id, 'next' => request('next', url()->previous())]) }}" method="POST"> @csrf @method('PUT')
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama karyawan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control" value="{{ $position->employee->user->name }}" readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nomor kontrak</label>
                            <div class="col-xl-8 col-xxl-4">
                                <input type="text" class="form-control" value="{{ $position->contract->kd }}" readonly />
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama jabatan</label>
                            <div class="col-xl-8 col-xxl-5">
                                <select type="text" class="form-select @error('position_id') is-invalid @enderror" name="position_id" value="{{ old('position_id') }}" required>
                                    <option value="">-- Pilih jabatan --</option>
                                    @foreach ($departments as $department)
                                        <optgroup label="{{ $department->name }}">
                                            @foreach ($department->positions as $_position)
                                                <option value="{{ $_position->id }}" @selected(old('position_id', $position->position_id) == $_position->id)>{{ $_position->name }}</option>
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
                            <label class="col-lg-4 col-xl-3 col-form-label">Masa berlaku</label>
                            <div class="col-xl-8 col-xxl-6">
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ old('start_at', $position->start_at?->toDateTimeLocalString()) }}" required />
                                    <input type="datetime-local" class="form-control @error('en_at') is-invalid @enderror" name="end_at" value="{{ old('end_at', $position->end_at?->toDateTimeLocalString()) }}" />
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
