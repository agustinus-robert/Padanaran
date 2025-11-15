@extends('core::layouts.default')

@section('title', 'Aset | Tambah ruang')
@section('navtitle', 'Tambah ruang')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-8">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('core::company.assets.rooms.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah ruang</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk menambah ruang</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-plus"></i> Tambah ruang baru</div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('core::company.assets.rooms.store', ['next' => route('core::company.assets.rooms.index')]) }}" method="post"> @csrf
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 form-label required" for="building_id">Gedung</label>
                            <div class="col-xl-8 col-xxl-6">
                                <select name="building_id" id="building_id" class="form-select">
                                    <option value="">--Pilih gedung--</option>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}" @selected(old('building_id') == $building->id)>{{ $building->name }}</option>
                                    @endforeach
                                </select>
                                @error('building_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 form-label required" for="kd">Kode</label>
                            <div class="col-xl-4 col-xxl-4">
                                <input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd') }}" required>
                                @error('kd')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 form-label required" for="name">Nama ruang</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 form-label" for="name">Kapasitas</label>
                            <div class="col-xl-3 col-xxl-3">
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" name="capacity" value="{{ old('capacity') }}">
                                @error('capacity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 form-label" for="name">Ukuran ruang</label>
                            <div class="col-xl-4 col-xxl-4">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('lenght') is-invalid @enderror text-end" name="lenght" value="{{ old('lenght') }}" />
                                    <div class="input-group-text">{{ 'x' }}</div>
                                    <input type="number" class="form-control @error('width') is-invalid @enderror text-end" name="width" value="{{ old('width') }}" />
                                </div>
                                @if ($errors->has('lenght', 'width'))
                                    <small class="text-danger d-block"> {{ $errors->first('lenght') ?: $errors->first('width') }} </small>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <div class="form-check @error('rentable') is-invalid @enderror mb-3">
                                    <input class="form-check-input" id="rent" name="rentable" value="1" type="checkbox">
                                    <label class="form-check-label" for="rent">Ruang ini dapat dipinjam</label>
                                </div>
                                @error('capacity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush
