@extends('core::layouts.default')

@section('title', 'Ubah kategori gaji | ')
@section('navtitle', 'Ubah kategori gaji')

@section('content')
<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-10">
        <div class="d-flex align-items-center mb-4">
            <a class="text-decoration-none" href="{{ request('next', route('core::company.salaries.slips.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
            <div class="ms-4">
                <h2 class="mb-1">Ubah kategori gaji</h2>
                <div class="text-secondary">Silakan isi formulir di bawah ini untuk memperbarui informasi slip gaji {{ $slip->name }}</div>
            </div>
        </div>
        <div class="card mb-4 border-0">
            <div class="card-body">
                <form class="form-block" action="{{ route('core::company.salaries.slips.update', ['slip' => $slip->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" for="az">Index urutan</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('az') is-invalid @enderror" name="az" value="{{ old('az', $slip->az) }}" required>
                            <div class="input-group-text">#</div>
                        </div>
                        @error('az')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="name">Nama slip</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $slip->name) }}" required>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3 row">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3">
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Perbarui</button>
                            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('core::company.salaries.categories.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection