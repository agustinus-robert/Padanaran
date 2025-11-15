@extends('docs::layouts.master')

@section('title', 'Detail dokumen | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('docs::home')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">{{ $doc->type->label() }}</h2>
                    <div class="text-muted">Berikut adalah informasi detail dokumen {{ strtolower($doc->label) }}</div>
                </div>
            </div>
            @if ($doc->trashed())
                <div class="alert alert-danger border-0">
                    <strong>Perhatian!</strong> Pengajuan ini telah dihapus, Anda tidak lagi dapat mengelola pengajuan ini.
                </div>
            @endif
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail dokumen</div>
                </div>
                <div class="card-body border-top">
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">Tanggal pembuatan</div>
                            <div class="fw-bold"> {{ $doc->created_at->formatLocalized('%A, %d %B %Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Kategori dokumen</div>
                            <div class="fw-bold"> {{ $doc->type->label() }}</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Nama dokumen</div>
                        <div class="fw-bold">{{ $doc->label ?: '-' }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Deskripsi/catatan/alasan</div>
                        <div class="fw-bold">{{ $doc->meta?->description ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="small text-muted mb-1">Lampiran</div>
                        <a href="{{ route('docs::manage.documents.download', ['document' => $doc->id]) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i> Lihat lampiran</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
