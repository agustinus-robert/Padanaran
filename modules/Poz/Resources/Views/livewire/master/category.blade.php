<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Kategori</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Kategori</a></li>
                    <li class="breadcrumb-item active text-secondary">{{ $action }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="card-title mb-4 fw-bold text-dark">{{ $action }} Kategori</h5>
            
            <form wire:submit="save" enctype="multipart/form-data"> 
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Code</label>
                                <input disabled wire:model="form.code" type="text" class="form-control bg-light border-0">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Nama Kategori</label>
                                <input type="text" class="form-control @error('form.name') is-invalid @enderror" 
                                    wire:change="categoryChanged($event.target.value)" wire:model="form.name">
                                @error('form.name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Induk Kategori</label>
                                <select wire:model="form.parent_id" class="form-select border-light shadow-none">
                                    <option value="">Pilih Induk Kategori (Opsional)</option>
                                    @foreach ($category as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted small mt-1">
                                    <i class="bi bi-info-circle"></i> Biarkan kosong jika tidak memiliki induk.
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Description</label>
                                <textarea class="form-control @error('form.description') is-invalid @enderror" 
                                    wire:model="form.description" rows="3"></textarea>
                                @error('form.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Upload Gambar</label>
                                <input type="file" class="form-control" wire:model="form.image" accept="image/*" onchange="previewImage(event)" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4" wire:ignore>
                        <label class="form-label small fw-bold text-secondary d-block">Preview Gambar</label>
                        <div id="preview-container" class="d-flex justify-content-center align-items-center rounded border bg-light position-relative" 
                            style="height: 240px; border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important;">
                            
                            <div id="placeholder-content" class="text-center text-muted">
                                <i id="cover-icon" class="bi bi-image" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="small mb-0">No image selected</p>
                            </div>
                            
                            <img id="cover-preview" src="#" alt="Preview" class="d-none rounded shadow-sm" 
                                style="max-width: 90%; max-height: 90%; object-fit: contain; z-index: 2;">
                        </div>
                    </div>
                </div>

                <hr class="my-4 text-light">

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-4 border">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Kategori</button>
                </div>
            </form> 
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('cover-preview');
                var placeholder = document.getElementById('placeholder-content');
                output.src = reader.result;
                output.classList.remove('d-none');
                placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</div>