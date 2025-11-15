@extends('core::layouts.default')

@section('title', 'Aset | Tambah gedung')
@section('navtitle', 'Tambah gedung')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('core::company.assets.buildings.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah gedung</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk menambah gedung</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-plus"></i> Tambah gedung baru</div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('core::company.assets.buildings.store', ['next' => url()->full()]) }}" method="post"> @csrf
                        <div class="mb-3">
                            <label class="form-label required" for="kd">Kode</label>
                            <input type="text" class="form-control @error('kd') is-invalid @enderror" name="kd" value="{{ old('kd') }}" required>
                            @error('kd')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="name">Nama gedung</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="address_primary">Alamat</label>
                            <input type="text" class="form-control @error('address_primary') is-invalid @enderror" name="address_primary" value="{{ old('address_primary') }}" required>
                            @error('address_primary')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="address_secondary">Kecamatan</label>
                            <input type="text" class="form-control @error('address_secondary') is-invalid @enderror" name="address_secondary" value="{{ old('address_secondary') }}" required>
                            @error('address_secondary')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="address_city">Kota/Kabupaten</label>
                            <input type="text" class="form-control @error('address_city') is-invalid @enderror" name="address_city" value="{{ old('address_city') }}" required>
                            @error('address_city')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="state_id">Provinsi</label>
                            <input type="text" class="form-control @error('state_id') is-invalid @enderror" name="state_id" value="{{ old('state_id') }}" required>
                            @error('state_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
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
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
    <style type="text/css">
        .ts-wrapper {
            padding: 0 !important;
        }

        .ts-control {
            border: 1px solid hsla(0, 0%, 82%, .2) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        new TomSelect('[name="state_id"]', {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            placeholder: 'Cari provinsi disini ...',
            load: function(q, callback) {
                fetch('{{ route('api::references.country-states.search') }}?q=' + encodeURIComponent(q))
                    .then(response => response.json())
                    .then(json => {
                        callback(json.data);
                    }).catch(() => {
                        callback();
                    });
            }
        });
    </script>
@endpush
