@extends('core::layouts.default')

@section('title', 'Kategori cuti | ')
@section('navtitle', 'Kategori cuti')

@section('content')
<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-10">
        <div class="d-flex align-items-center mb-4">
            <a class="text-decoration-none" href="{{ request('next', route('core::company.services.vacation-categories.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
            <div class="ms-4">
                <h2 class="mb-1">Ubah kategori cuti</h2>
                <div class="text-secondary">Silakan isi formulir di bawah ini untuk mengubah kategori cuti</div>
            </div>
        </div>
        <div class="card mb-4 border-0">
            <div class="card-body">
                <form class="form-block" action="{{ route('core::company.services.vacation-categories.update', ['category' => $category->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
                    <div class="mb-3 row required">
                        <label class="col-lg-4 col-xl-3 col-form-label">Nama kategori cuti</label>
                        <div class="col-xl-8 col-xxl-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $category->name) }}" required/>
                            @error('name')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row required">
                        <label class="col-lg-4 col-xl-3 col-form-label">Tipe cuti</label>
                        <div class="col-xl-8 col-xxl-6">
                            <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                @forelse($types as $_type)
                                    @if($loop->first)
                                        <option value="">-- Pilih tipe cuti --</option>
                                    @endif
                                    <option value="{{ $_type->value }}" @selected(old('type', $category->type->value) == $_type->value)>{{ $_type->label() }}</option>
                                @empty
                                    <option value="">Tidak ada parent</option>
                                @endforelse
                            </select>
                            @error('type')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-4 col-xl-3 col-form-label">Kuota</label>
                        <div class="col-xl-6 col-xxl-4">
                            <div class="input-group">
                                <input type="number" class="form-control @error('quota') is-invalid @enderror" name="quota" value="{{ old('quota', $category->meta?->quota ?? '') }}" max="366"/>
                                <div class="input-group-text">hari</div>
                            </div>
                            <small class="text-muted">Kosongkan jika tidak ada batasan untuk kuota</small>
                            @error('quota')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-4 col-lg-3">Jenis inputan</label>
                        <div class="col-md-4">
                            @foreach (['options', 'range'] as $v)
                                <div class="form-check">
                                    <input class="form-check-input only_one" type="radio" id="fields_{{ $v }}" value="{{ $v }}" name="fields" @if(old('fields', $category->meta?->fields ?? -1) == $v) checked @endif  required>
                                    <label class="form-check-label" for="fields_{{ $v }}"><code>{{ $v }}</code></label>
                                </div>
                            @endforeach
                            @error('fields')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-4 col-lg-3">Mode freelance?</label>
                        <div class="col-md-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="as_freelance" value="1" name="as_freelance" @if(old('as_freelance', $category->meta?->as_freelance ?? false)) checked @endif>
                                <label class="form-check-label" for="as_freelance"><span id="as_freelance-text">@if(old('as_freelance', $category->meta?->as_freelance ?? false)) Ya, kategori ini menyediakan mode freelance @else Tidak, kategori ini tidak menyediakan mode freelance @endif</span></label>
                            </div>
                            @error('as_freelance')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3">
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            <a class="btn btn-ghost-light text-dark" href="{{ route('core::company.services.vacation-categories.store', ['next' => request('next')]) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            document.querySelector('#as_freelance').addEventListener('change', (e) => {
                document.querySelector('#as_freelance-text').innerHTML = e.target.checked ? 'Ya, kategori ini menyediakan mode freelance' : 'Tidak, kategori ini tidak menyediakan mode freelance'
            });
        });
    </script>
@endpush