@extends('docs::layouts.master')

@section('title', 'Ubah dokumen | ')

@section('navtitle', 'Ubah dokumen')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-xxl-9">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('docs::manage.documents.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Ubah dokumen</h2>
                    <div class="text-secondary">Silakan isi form di bawah ini untuk menambahkan dokumen baru.</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Form ubah dokumen.
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('docs::manage.documents.update', ['document' => $doc->id, 'next' => request('next', route('docs::manage.documents.index'))]) }}" method="POST" enctype="multipart/form-data"> @csrf @method('PUT')
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama dokumen</label>
                            <div class="col-xl-8 col-xxl-9">
                                <input type="text" class="form-control @error('label') is-invalid @enderror" name="label" value="{{ old('label', $doc->label) }}" required />
                                @error('label')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Kategori dokumen</label>
                            <div class="col-lg-8 col-xl-9">
                                <div class="border-1 @error('type') is-invalid @enderror rounded border"">
                                    @foreach ($types as $type)
                                        <div class="ms-2 mb-2 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="type" id="type-{{ $type->value }}" value="{{ $type->value }}" @checked($type->value == $doc->type->value)>
                                                <label class="form-check-label" for="type-{{ $type->value }}">
                                                    Dokumen {{ $type->label() }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('type')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Deskripsi</label>
                            <div class="col-lg-8 col-xl-9">
                                <textarea cols="6" class="form-control @error('description') is-invalid @enderror" name="description">{{ $doc->meta?->description ?? '' }}</textarea>
                                @error('description')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Konten</label>
                            <div class="col-lg-8 col-xl-9">
                                <textarea cols="6" class="form-control @error('content') is-invalid @enderror" id="input-form-content" name="content">{{ $doc->meta?->content ?? '' }}</textarea>
                                @error('content')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Lampiran</label>
                            <div class="col-lg-8 col-xl-9">
                                <input class="form-control @error('files') is-invalid @enderror" name="files" type="file" id="upload-input" accept="application/pdf" value="{{ $doc->path ?? '' }}">
                                <small class="text-muted">Berkas berupa .pdf maksimal berukuran 2mb</small>
                                @error('files')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 pt-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('docs::manage.documents.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea#input-form-content',
            height: "480",
            paste_data_images: true,
            relative_urls: false,
            plugins: 'autosave autoresize print preview paste searchreplace code fullscreen image link media table charmap hr pagebreak advlist lists wordcount imagetools noneditable charmap',
            menubar: false,
            toolbar: "formatselect bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | indent outdent preview",
            fontsize_formats: '8pt 11pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
        });
    </script>
@endpush
