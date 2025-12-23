@extends('counseling::layouts.default')

@section('title', 'Ubah kategori kasus - ')

@section('body-content')
<div class="row d-flex justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h2 class="mb-4">
            <a class="text-decoration-none small" href="{{ request('next', route('counseling::manage.cases.categories.index')) }}">
                <i class="mdi mdi-arrow-left-circle-outline"></i>
            </a>
            Ubah kategori kasus
        </h2>

        <div class="card">
            <div class="card-body">
                <form class="form-block" action="{{ route('counseling::manage.cases.categories.update', ['category' => $category->id, 'next' => request('next', route('counseling::manage.cases.categories.index'))]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Kategori --}}
                    <x-input-group label="Nama kategori" :isRow="false" required>
                        <x-input
                            name="name"
                            :value="old('name', $category->name)"
                            :error="$errors->has('name')"
                            autocomplete="off"
                        />
                        @error('name')
                            <small class="invalid-feedback d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    <p class="text-muted">
                        Perubahan nama akan diterapkan ke seluruh deskripsi kasus yang berkategori <strong>{{ $category->name }}</strong>
                    </p>

                    {{-- Tombol Submit --}}
                    <x-input-group :isRow="false">
                        <x-button type="submit" class="btn-primary" :label="'Simpan'" />
                        <a class="btn btn-secondary" href="{{ request('next', route('counseling::manage.cases.categories.index')) }}">Kembali</a>
                    </x-input-group>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
