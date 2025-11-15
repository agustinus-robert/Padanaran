@extends('admin::layouts.default')

@section('title', 'Perjanjian kerja | ')
@section('navtitle', 'Perjanjian kerja')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('admin::system.contracts.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Buat perjanjian kerja baru</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat perjanjian kerja baru</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('admin::system.contracts.store', ['next' => request('next')]) }}" method="POST"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Kode perjanjian kerja</label>
                            <div class="col-xl-8 col-xxl-4">
                                <input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd') }}" required />
                                @error('kd')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama perjanjian kerja</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required />
                                @error('name')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Deskripsi</label>
                            <div class="col-lg-8">
                                <textarea type="text" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('admin::system.contracts.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
