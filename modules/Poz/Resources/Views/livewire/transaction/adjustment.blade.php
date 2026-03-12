<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Adjustment</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Persediaan</a></li>
                    <li class="breadcrumb-item active text-secondary">{{ $action }} Adjustment</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <h5 class="card-title mb-0 fw-bold text-dark">{{ $action }} Stok Adjustment</h5>
        </div>
        
        <form wire:submit="save" enctype="multipart/form-data"> 
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Supplier</label>
                        <select class="form-select border-light shadow-none @error('form.supplier_id') is-invalid @enderror" 
                                wire:model="form.supplier_id" wire:change="showProduct($event.target.value)">
                            <option value="">Pilih Supplier</option>
                            @foreach ($supplier as $supp)
                                <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                            @endforeach
                        </select>
                        @error('form.supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Product</label>
                        <select class="form-select border-light shadow-none @error('form.product_id') is-invalid @enderror" 
                                wire:model="form.product_id" wire:change="showShift($event.target.value)">
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('form.product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Shift</label>
                        <select class="form-select border-light shadow-none @error('form.shift') is-invalid @enderror" wire:model="form.shift">
                            <option value="">Pilih Shift</option>
                            @if(count($shift))
                                @php $timeMap = ['morning' => 1, 'afternoon' => 2, 'evening' => 3]; @endphp
                                @foreach($shift as $val)
                                    <option value="{{ $timeMap[$val->time] ?? '' }}">{{ ucfirst($val->time) }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('form.shift')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4 p-3 rounded-3 bg-light">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Jenis Penyesuaian</label>
                        <select class="form-select border-0 shadow-none @error('form.status') is-invalid @enderror" wire:model="form.status">
                            <option value="">Pilih Arah Stok</option>
                            <option value="plus" class="text-success">➕ Tambah (Plus)</option>
                            <option value="minus" class="text-danger">➖ Kurang (Minus)</option>
                        </select>
                        @error('form.status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Kuantitas (Qty)</label>
                        <input type="number" class="form-control border-0 shadow-none @error('form.qty') is-invalid @enderror" 
                               wire:model="form.qty" placeholder="0">
                        @error('form.qty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Kondisi Produk <span class="fw-normal text-muted">(Opsional)</span></label>
                        <select class="form-select border-0 shadow-none" wire:model="form.product_status">
                            <option value="">Normal</option>
                            <option value="1">Produk Rusak</option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-secondary">Catatan Penyesuaian <span class="fw-normal text-muted">(Opsional)</span></label>
                    <textarea class="form-control border-light shadow-none @error('form.note') is-invalid @enderror" 
                              wire:model="form.note" rows="2" placeholder="Alasan penyesuaian stok..."></textarea>
                    @error('form.note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer bg-white p-4 text-end border-top-0">
                <button type="button" class="btn btn-link text-muted text-decoration-none me-2">Batal</button>
                <button type="submit" class="btn btn-primary px-5 shadow-sm">
                    Simpan Adjustment
                </button>
            </div>
        </form>
    </div>
</div>