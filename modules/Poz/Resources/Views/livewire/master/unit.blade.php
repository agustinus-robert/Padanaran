<div>
    <div class="row mb-4 align-items-center">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold mb-1">Unit</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Master Data</a></li>
                        <li class="breadcrumb-item active text-primary">{{ $action }} Unit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <h5 class="card-title mb-0 fw-bold">{{ $action }} Unit</h5>
        </div>
        
        <form wire:submit="save"> 
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Code</label>
                        <input disabled wire:model="form.code" type="text" class="form-control bg-light border-0 py-2">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-secondary">Nama Satuan</label>
                        <input type="text" class="form-control py-2 @error('form.name') is-invalid @enderror" 
                               wire:model="form.name" placeholder="Contoh: Pcs, Kg, Box">
                        @error('form.name')
                            <div class="invalid-feedback mt-2">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light p-4 text-end border-top-0">
                <button type="button" class="btn btn-link text-muted text-decoration-none me-2">Batal</button>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Unit
                </button>
            </div>
        </form>
    </div>
</div>