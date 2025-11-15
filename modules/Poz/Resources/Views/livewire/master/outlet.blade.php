<div>
    <div class="block-rounded block"> <!--begin::Header-->
        <div class="block-content">
            <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
                <div class="row justify-content-center py-sm-3 py-md-5">
                    <div class="col-md-8">

                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input disabled wire:model="form.code" type="text" class="form-control">
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Nama Outlet</label>
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
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control" wire:model="form.document">
                            @if (!empty($form['document']) && isset($form['id']))
                                <div class="mt-3">
                                    <i class="bi bi-arrow-down-circle"></i> <a href="{{ asset($form['document']) }}">Download File</a>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            @if (isset($form['location']))
                                <a href="{{ asset('uploads/' . $form['location'] . '/' . $form['image_name']) }}">Cek Gambar</a>
                            @endif
                        </div>
                    </div>

                </div> <!--end::Body--> <!--begin::Footer-->

                <div class="block-content block-content-full block-content-sm bg-body-light">
                    <button type="submit" class="btn btn-sm btn-alt-primary"><i class="fa fa-check me-1 opacity-50"></i> Submit</button>
                </div>
            </form> <!--end::Form-->
        </div>
    </div>
</div>
