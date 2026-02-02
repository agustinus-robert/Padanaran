@extends('layouts.horizontal-layout')

@section('title', 'Ubah deskripsi kasus - ')

@section('navtitle', 'Deskripsi Khasus')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@section('body-content')
<div class="row d-flex justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h2 class="mb-4">
            <a class="text-decoration-none small" href="{{ request('next', route('counseling::manage.cases.descriptions.index')) }}">
                <i class="mdi mdi-arrow-left-circle-outline"></i>
            </a>
            Ubah deskripsi kasus
        </h2>

        <div class="card">
            <div class="card-body">
                <form class="form-block" action="{{ route('counseling::manage.cases.descriptions.update', ['description' => $description->id, 'next' => request('next', route('counseling::manage.cases.descriptions.index'))]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Kategori --}}
                    <x-input-group label="Kategori" :isRow="false" required>
                        <x-select
                            name="ctg_id"
                            :options="$categories->mapWithKeys(fn($c) => [$c->id => $c->name])"
                            :selected="old('ctg_id', $description->ctg_id)"
                            :error="$errors->has('ctg_id')"
                            placeholder="-- Pilih --"
                        />
                        @error('ctg_id')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    {{-- Deskripsi --}}
                    <x-input-group label="Deskripsi" :isRow="false" required>
                        <x-input
                            name="name"
                            type="text"
                            :value="old('name', $description->name)"
                            :error="$errors->has('name')"
                            autocomplete="off"
                        />
                        @error('name')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    {{-- Poin --}}
                    <x-input-group label="Poin" :isRow="false" required>
                        <x-input
                            name="point"
                            type="number"
                            :value="old('point', $description->point)"
                            :error="$errors->has('point')"
                            class="w-50"
                            autocomplete="off"
                        />
                        @error('point')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </x-input-group>

                    <p class="text-muted">Perubahan nama tidak berpengaruh terhadap riwayat kasus siswa</p>

                    {{-- Submit --}}
                    <x-button type="submit" variant="primary">Simpan</x-button>
                    <a class="btn btn-secondary" href="{{ request('next', route('counseling::manage.cases.descriptions.index')) }}">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
