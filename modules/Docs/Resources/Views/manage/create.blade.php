@extends('docs::layouts.master')

@section('title', 'Tambah dokumen | ')

@section('navtitle', 'Tambah dokumen')

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-12 col-xl-9 col-xxl-9">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('docs::manage.documents.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah dokumen</h2>
                    <div class="text-secondary">Silakan isi form di bawah ini untuk menambahkan dokumen baru.</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Form tambah dokumen.
                </div>
                <div class="card-body">
                    <form class="form-block" action="{{ route('docs::manage.documents.store', ['next' => request('next', route('docs::manage.documents.index'))]) }}" method="POST" enctype="multipart/form-data"> @csrf
                        <div class="required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Nama dokumen</label>
                            <input type="text" class="form-control @error('label') is-invalid @enderror" name="label" value="{{ old('label') }}" required />
                            @error('label')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Kategori dokumen</label>
                            <div class="border-1 @error('type') is-invalid @enderror rounded border">
                                <div class="list-group list-group-flush">
                                    @foreach ($types as $type)
                                        <label class="list-group-item d-flex align-items-center">
                                            <input class="form-check-input me-3" type="radio" name="type" id="type-{{ $type->value }}" value="{{ $type->value }}" required>
                                            <div>
                                                <div class="fw-bold mb-0">{{ $type->label() }}</div>
                                                <div class="small text-muted">silakan klik pilihan ini untuk membuat dokumen {{ $type->label() }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('type')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Deskripsi</label>
                            <textarea cols="6" class="form-control @error('description') is-invalid @enderror" name="description"></textarea>
                            @error('description')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="col-lg-4 col-xl-3 col-form-label">Konten</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="input-form-content" name="content"></textarea>
                            @error('content')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Lampiran</label>
                            <input class="form-control @error('files') is-invalid @enderror" name="files" type="file" id="upload-input" accept="application/pdf">
                            <small class="text-muted">Berkas berupa .pdf maksimal berukuran 2mb</small>
                            @error('files')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
