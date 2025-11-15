<div>
    <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">{{$action}} Kasir</div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
            <div class="card-body">
                <div class="mb-3"> 
                    <label class="form-label">Nama Kasir</label> 
                    <input type="text" class="form-control" wire:model="form.name">
                    @error('form.name')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3"> 
                    <label class="form-label">Username</label> 
                    <input type="text" class="form-control" wire:model="form.username"></textarea>
                    @error('form.username')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3"> 
                    <label class="form-label">Password</label> 
                    <input type="password" class="form-control" wire:model="form.password"></textarea>
                    @error('form.password')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3"> 
                    <label class="form-label">Email</label> 
                    <input type="text" class="form-control" wire:model="form.email_address"></textarea>
                    @error('form.email_address')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3"> 
                    <label class="form-label">Outlet</label> 
                    <select class="form-select" wire:model="form.outlet_id">
                        <option value="">Pilih Outlet</option>
                        @foreach($outlet as $key => $value)
                            <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>

                    @error('form.outlet_id')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3"> 
                    <label class="form-label">File</label> 
                    <input type="file" class="form-control" wire:model="form.document">
                    @if(!empty($form['document']) && isset($form['id']))
                        <div class="mt-3">
                            <i class="bi bi-arrow-down-circle"></i> <a href="{{asset($form['document'])}}">Download File</a>
                        </div>
                    @endif
                </div>
                
            </div> <!--end::Body--> <!--begin::Footer-->
            <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
        </form> <!--end::Form-->
    </div>
</div>