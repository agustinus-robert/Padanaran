<div>
    <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">{{$action}} Gudang</div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
            <div class="card-body">
                <div class="mb-3"> 
                    <label class="form-label">Code</label> 
                    <input disabled wire:model="form.code" type="text" class="form-control">
                </div>

                <div class="mb-3"> 
                    <label class="form-label">Nama Gudang</label> 
                    <input type="text" class="form-control" wire:model="form.name">
                    @error('form.name')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3"> 
                    <label class="form-label">Lokasi</label> 
                    <input type="text" class="form-control" wire:model="form.location">
                    @error('form.location')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3"> 
                    <label class="form-label">Nomor Telepon</label> 
                    <input type="text" class="form-control" wire:model="form.phone">
                </div>

                <div class="mb-3"> 
                    <label class="form-label">Email</label> 
                    <input type="text" class="form-control" wire:model="form.email">
                </div>
                
            </div> <!--end::Body--> <!--begin::Footer-->
            <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
        </form> <!--end::Form-->
    </div>
</div>