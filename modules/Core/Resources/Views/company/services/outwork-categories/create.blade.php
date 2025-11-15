@extends('core::layouts.default')

@section('title', 'Kategori kegiatan lainnya | ')
@section('navtitle', 'Kategori kegiatan lainnya')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('core::company.services.outwork-categories.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Buat kategori kegiatan lainnya</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk membuat kategori kegiatan lainnya</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('core::company.services.outwork-categories.store', ['next' => request('next')]) }}" method="POST"> @csrf
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama kategori</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required />
                                @error('name')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Keterangan</label>
                            <div class="col-xl-8 col-xxl-6">
                                <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" required />
                                @error('description')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Tarif</label>
                            <div class="col-xl-8 col-xxl-8">
                                <div class="input-group">
                                    <div class="input-group-text" data-bs-toggle="tooltip" data-bs-title="Di luar jam kerja"><i class="mdi mdi-timer-off-outline text-danger"></i></div>
                                    <input placeholder="Di luar jam kerja" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" />
                                    <div class="input-group-text" data-bs-toggle="tooltip" data-bs-title="Di luar jam kerja"><i class="text-success mdi mdi-timer-outline"></i></div>
                                    <input placeholder="Di jam kerja" type="number" class="form-control @error('in_working_hours_price') is-invalid @enderror" name="in_working_hours_price" value="{{ old('in_working_hours_price') }}" />
                                </div>
                                @error('price')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Inputan waktu</label>
                            <div class="col-md-4">
                                @foreach (['prepareable' => 'Persiapan kegiatan', 'fixed' => 'Tarif flat'] as $v => $description)
                                    <div class="form-check">
                                        <input class="form-check-input only_one" type="checkbox" id="meta{{ $v }}" value="1" name="meta[{{ $v }}]" @if (old('meta.$v') == 1) checked @endif>
                                        <label class="form-check-label" for="meta{{ $v }}"><code>{{ $v }}</code> <br> <small class="text-muted">{{ $description }}</small></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ route('core::company.services.outwork-categories.index', ['next' => request('next')]) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
