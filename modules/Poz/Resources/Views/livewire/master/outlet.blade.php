<div> 
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <div class="d-flex align-items-center">
                <div class="avatar-xs me-3">
                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-18">
                        <i class="bx bx-store-alt"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h5 class="font-size-15 mb-1">Informasi Detail Outlet</h5>
                    <p class="text-muted mb-0 font-size-13">Pastikan data yang dimasukkan sudah sesuai dengan dokumen resmi.</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <form wire:submit="save" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label text-muted">Kode Outlet</label>
                            <div class="col-sm-9">
                                <input disabled wire:model="form.code" type="text" class="form-control bg-light border-dashed fw-medium" placeholder="Auto-generated">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nama Outlet <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('form.name') is-invalid @enderror" wire:model="form.name" placeholder="Masukkan nama outlet">
                                @error('form.name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('form.description') is-invalid @enderror" wire:model="form.description" rows="5" placeholder="Berikan keterangan singkat mengenai lokasi atau operasional outlet..."></textarea>
                                @error('form.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 border-start-lg">
                        <div class="ps-lg-3">
                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>Lampiran Dokumen</span>
                                    @if (!empty($form['document']) && isset($form['id']))
                                        <a href="{{ asset($form['document']) }}" target="_blank" class="badge bg-info-subtle text-info font-size-11">
                                            <i class="bx bx-link-external me-1"></i>Lihat File
                                        </a>
                                    @endif
                                </label>
                                <input type="file" class="form-control" wire:model="form.document">
                                <small class="text-muted mt-1 d-block font-size-11 italic">Format: PDF, JPG, PNG (Maks. 2MB)</small>
                            </div>

                            @if (isset($form['location']))
                            <div class="mt-4">
                                <label class="form-label text-muted font-size-13">Preview Foto Outlet</label>
                                <div class="position-relative border rounded p-1 bg-light shadow-sm">
                                    <img src="{{ asset('uploads/' . $form['location'] . '/' . $form['image_name']) }}" 
                                         class="img-fluid rounded w-100" 
                                         style="max-height: 200px; object-fit: cover;" 
                                         alt="Preview">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <a href="{{ asset('uploads/' . $form['location'] . '/' . $form['image_name']) }}" target="_blank" class="btn btn-sm btn-dark opacity-75 shadow">
                                            <i class="bx bx-fullscreen"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="mt-4 border border-dashed rounded p-5 text-center bg-light shadow-inner">
                                <i class="bx bx-image-add display-4 text-muted opacity-50"></i>
                                <p class="text-muted small mb-0">Belum ada foto yang diunggah</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-3 border-top d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-soft-secondary px-4" onclick="history.back()">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </button>
                    <div>
                        <button type="submit" class="btn btn-primary px-5 shadow-lg fw-bold">
                            <i class="bx bx-check-double me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .border-dashed { border-style: dashed !important; }
        .bg-primary-subtle { background-color: rgba(85, 110, 230, 0.25) !important; }
        .bg-info-subtle { background-color: rgba(80, 165, 241, 0.25) !important; }
        @media (min-width: 992px) {
            .border-start-lg { border-left: 1px solid #eff2f7 !important; }
        }
        .italic { font-style: italic; }
    </style>
</div>