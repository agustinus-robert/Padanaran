<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="font-size-18 mb-0">Adjustment</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Adjustment</a></li>
                        <li class="breadcrumb-item active">{{ $action }} Adjustment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="card-primary card-outline card mb-4"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">{{ $action }} Adjustment</div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" wire:model="form.supplier_id" wire:change="showProduct($event.target.value)">
                                <option value="">Pilih Supplier</option>
                                @foreach ($supplier as $supp)
                                    <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                                @endforeach
                            </select>
                            @error('form.supplier_id')
                                <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select class="form-select" wire:model="form.product_id" wire:change="showShift($event.target.value)">
                                <option value="">Pilih Produk</option>
                                @if(count($products) > 0)
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('form.product_id')
                                <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Shift</label>
                            <select class="form-select" wire:model="form.shift">
                                <option value="">Pilih Shift</option>
                                @if(count($shift))
                                    @php
                                        $timeMap = ['morning' => 1, 'afternoon' => 2, 'evening' => 3];
                                    @endphp

                                    @foreach($shift as $val)
                                        <option value="{{ $timeMap[$val->time] ?? '' }}">{{$val->time}}</option>
                                    @endforeach
                                @endif
                            </select>

                            @error('form.shift')
                                <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Penyesuaian Produk</label>
                            <select class="form-select" wire:model="form.status">
                                <option value="">Pilih Status</option>
                                <option value="plus">Plus</option>
                                <option value="minus">Minus</option>
                            </select>
                            @error('form.status')
                                <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                            @enderror
                        </div>
                     </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Qty</label>
                            <input class="form-control" type="number" wire:model="form.qty" />
                            @error('form.qty')
                                <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status <b>(Optional)</b></label>
                            <select class="form-select" wire:model="form.product_status" name="productStatus">
                                <option value="">Pilih</option>
                                <option value="1">Produk Rusak</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Produk <b>(Opsional)</b></label>
                        <textarea class="form-control" wire:model="form.note"></textarea>
                        @error('form.note')
                            <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div> <!--end::Body--> <!--begin::Footer-->
            <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
        </form> <!--end::Form-->
    </div>
</div>
