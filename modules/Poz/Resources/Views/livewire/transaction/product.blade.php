<div>
    <div class="row mb-4 align-items-center">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold mb-1">Produk</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Inventori</a></li>
                        <li class="breadcrumb-item active text-primary">{{ $action }} Produk</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <h5 class="card-title mb-0 fw-bold">{{ $action == 'direction' ? 'Direct Production / Entry' : $action . ' Produk' }}</h5>
        </div>

        <form wire:submit="save" enctype="multipart/form-data">
            <div class="card-body p-4">
                <div class="row g-4">
                    
                    @if ($action == 'direction')
                        <div class="col-md-7 border-end">
                            <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem;">Informasi Dasar</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Code</label>
                                    <input disabled wire:model="form.code" type="text" class="form-control bg-light">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-bold">Nama Produk</label>
                                    <input type="text" class="form-control" wire:model="form.name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Kategori</label>
                                    <select class="form-select" wire:model="form.category_id" wire:change="sub_category_changed($event.target.value)">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($category as $value) <option value="{{ $value->id }}">{{ $value->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Sub Kategori</label>
                                    <select class="form-select" wire:model="form.sub_category_id" {{ $categoryHasSub != 1 ? 'disabled' : '' }}>
                                        <option value="">Pilih Sub</option>
                                        @foreach ($subCategory as $value) <option value="{{ $value->id }}">{{ $value->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Harga Jual</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Rp</span>
                                        <input type="number" wire:model="form.price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Unit</label>
                                    <select class="form-select" wire:model="form.unit_id">
                                        <option value="">Pilih</option>
                                        <option value="1">PCS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem;">Input Stok & Batch</h6>
                            <div class="bg-light p-3 rounded-3">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Supplier</label>
                                    <select class="form-select border-0 shadow-none" wire:model="sch.supplier">
                                        <option value="">Pilih Supplier</option>
                                        @foreach($supplier as $sup) <option value="{{ $sup->id }}">{{$sup->name}}</option> @endforeach
                                    </select>
                                </div>
                                <div class="row g-2">
                                    <div class="col-7">
                                        <label class="form-label small fw-bold">Shift</label>
                                        <select class="form-select border-0 shadow-none" wire:model="sch.shifts">
                                            <option value="">Pilih</option>
                                            <option value="morning">Morning</option>
                                            <option value="afternoon">Afternoon</option>
                                            <option value="evening">Evening</option>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label class="form-label small fw-bold">Qty Input</label>
                                        <input class="form-control border-0 shadow-none" type="number" wire:model="sch.qty" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Code</label>
                                    <input disabled wire:model="form.code" type="text" class="form-control bg-light">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-bold">Nama Produk</label>
                                    <input type="text" class="form-control @error('form.name') is-invalid @enderror" wire:model="form.name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Brand</label>
                                    <select class="form-select" wire:model="form.brand_id">
                                        <option value="">Pilih Brand</option>
                                        @foreach ($brand as $value) <option value="{{ $value->id }}">{{ $value->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Kategori</label>
                                    <select class="form-select" wire:model="form.category_id" wire:change="sub_category_changed($event.target.value)">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($category as $value) <option value="{{ $value->id }}">{{ $value->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-primary text-decoration-underline">Harga Awal (Modal)</label>
                                    <input type="number" wire:model="form.wholesale" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Harga Jual</label>
                                    <input type="number" wire:model="form.price" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="mb-3 text-center">
                                    <label class="form-label small fw-bold d-block text-start">File / Gambar Produk</label>
                                    <input type="file" class="form-control form-control-sm" wire:model="form.document">
                                    @if (!empty($form['document']) && isset($form['id']))
                                        <a href="{{ asset($form['document']) }}" class="btn btn-sm btn-outline-primary mt-2 w-100">
                                            <i class="bi bi-download"></i> Lihat File
                                        </a>
                                    @endif
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Barcode Tipe</label>
                                    <select class="form-select form-select-sm" wire:model="form.barcode">
                                        <option value="1">Barcode 1</option>
                                    </select>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold">Pajak</label>
                                    <select class="form-select form-select-sm" wire:model="form.tax_rate_id">
                                        <option value="">Tanpa Pajak</option>
                                        @foreach ($tax as $value) <option value="{{ $value->id }}">{{ $value->name }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <div class="card-footer bg-white p-4 border-top-0 d-flex align-items-center justify-content-between">
                <div>
                    <div wire:loading class="spinner-border spinner-border-sm text-primary me-2"></div>
                    <span wire:loading class="small text-muted">Memproses data...</span>
                </div>
                <div>
                    <button type="button" class="btn btn-link text-decoration-none text-muted me-3">Batal</button>
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary px-5 shadow-sm">
                        Simpan Produk
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>