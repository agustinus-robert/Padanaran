<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Brand</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">{{ $action }} Brand</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="card-title mb-4 fw-bold">{{ $action }} Brand</h5>
            
            <form wire:submit="save" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Code</label>
                        <input disabled wire:model="form.code" type="text" class="form-control bg-light border-0">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-secondary">Nama Brand</label>
                        <input type="text" class="form-control @error('form.name') is-invalid @enderror" wire:model="form.name">
                        @error('form.name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Description</label>
                        <textarea class="form-control @error('form.description') is-invalid @enderror" wire:model="form.description" rows="3"></textarea>
                        @error('form.description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">File</label>
                        <input type="file" class="form-control" wire:model="form.document">
                        
                        @if (!empty($form['document']) && isset($form['id']))
                            <div class="mt-2">
                                <a href="{{ asset($form['document']) }}" class="text-primary small text-decoration-none">
                                    <i class="bi bi-file-earmark-arrow-down"></i> Download current file
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="my-4 text-light">

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-4">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>