@extends('finance::layouts.default')

@section('title', 'Ubah NPWP | ')

@section('navtitle', 'Ubah NPWP')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', url()->previous()) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Ubah NPWP</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat NPWP</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-plus"></i> Ubah NPWP
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-block" enctype="multipart/form-data" action="{{ route('finance::tax.employeetaxs.update', ['next' => request('next'), 'employeetax' => $employee->id]) }}" method="POST"> @csrf @method('PUT')
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama karyawan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select type="text" class="form-select @error('user_id') is-invalid @enderror" name="user_id" value="{{ old('user_id') }}" required>
                                    <option value="{{ $employee->user->id }}">{{ $employee->user->name }}</option>
                                </select>
                                @error('empl_id')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">No. NPWP</label>
                            <div class="col-lg-8 col-xl-9">
                                <input type="text" name="tax_number" class="form-control" value="{{ old('tax_number', $employee->user->getMeta('tax_number')) }}" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Alamat sesuai NPWP</label>
                            <div class="col-lg-8 col-xl-9">
                                <textarea name="tax_address" type="text" class="form-control">{{ old('tax_address', $employee->user->getMeta('tax_address')) }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Lampiran NPWP</label>
                            <div class="col-md-8">
                                <div class="tg-steps-outwork-attachment">
                                    <input class="form-control @error('files') is-invalid @enderror" name="files" type="file" id="upload-input" accept="image/*,application/pdf">
                                    <small class="text-muted">Berkas berupa .jpg, .png atau .pdf maksimal berukuran 2mb</small>
                                    @error('files')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
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
