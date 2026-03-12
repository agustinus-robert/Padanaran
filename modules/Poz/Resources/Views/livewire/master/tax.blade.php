<div>
    <div class="row mb-4 align-items-center">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold mb-1">Pajak</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                        <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Master</a></li>
                        <li class="breadcrumb-item active text-secondary">{{ $action }} Pajak</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <h5 class="card-title mb-0 fw-bold text-dark">{{ $action }} Pajak</h5>
        </div>
        
        <form wire:submit="save"> 
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Code</label>
                        <input disabled wire:model="form.code" type="text" class="form-control bg-light border-0">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-secondary">Nama Pajak</label>
                        <input type="text" class="form-control @error('form.name') is-invalid @enderror" 
                               wire:model="form.name" placeholder="Contoh: PPN 11%">
                        @error('form.name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-secondary">Aktifkan Pada</label>
                        <select class="form-select @error('form.actived_on') is-invalid @enderror" wire:model="form.actived_on">
                            <option value="">Pilih Penempatan Pajak</option>
                            <option value="1">Produk</option>
                            <option value="2">Penjualan / Penjualan POS</option>
                        </select>
                        @error('form.actived_on')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Tarif (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control @error('form.rate') is-invalid @enderror" 
                                   wire:model="form.rate" placeholder="0">
                            <span class="input-group-text bg-white text-secondary">%</span>
                        </div>
                        @error('form.rate')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light p-4 text-end border-top-0">
                <button type="button" class="btn btn-link text-muted text-decoration-none me-2">Batal</button>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    Simpan Data Pajak
                </button>
            </div>
        </form>
    </div>
</div>