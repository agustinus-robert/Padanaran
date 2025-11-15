<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="font-size-18 mb-0">Brand</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Brand</a></li>
                        <li class="breadcrumb-item active">{{ $action }} Brand</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">{{ $action }} Brand</div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input disabled wire:model="form.code" type="text" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Brand</label>
                    <input type="text" class="form-control" wire:model="form.name">
                    @error('form.name')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" wire:model="form.description"></textarea>
                    @error('form.description')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">File</label>
                    <input type="file" class="form-control" wire:model="form.document">
                    @if (!empty($form['document']) && isset($form['id']))
                        <div class="mt-3">
                            <i class="bi bi-arrow-down-circle"></i> <a href="{{ asset($form['document']) }}">Download File</a>
                        </div>
                    @endif
                </div>

            </div> <!--end::Body--> <!--begin::Footer-->
            <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
        </form> <!--end::Form-->
    </div>
</div>
