<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="font-size-18 mb-0">Produk</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Produk</a></li>
                        <li class="breadcrumb-item active">{{ $action }} Produk</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if ($action == 'direction')
        <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input disabled wire:model="form.code" type="text" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipe</label>
                    <select class="form-control" wire:model="form.type">
                        <option value="">Pilih Tipe</option>
                        <option value="1">Standard</option>
                    </select>
                    @error('form.type')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" wire:model="form.name">
                    @error('form.name')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Barcode</label>
                    <select class="form-control" wire:model="form.barcode">
                        <option value="">Pilih Barcode</option>
                        <option value="1">Barcode 1</option>
                    </select>
                    @error('form.barcode')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <select class="form-control" wire:model="form.brand_id">
                        <option value="">Pilih Brand</option>
                        @foreach ($brand as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
                    @error('form.brand_id')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-control" wire:model="form.category_id" wire:change="sub_category_changed($event.target.value)">
                        <option value="">Pilih Kategori</option>
                        @foreach ($category as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
                    @error('form.category_id')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                @if ($categoryHasSub == 1 && count($subCategory) > 0)
                    <div class="mb-3">
                        <label class="form-label">Sub Kategori</label>
                        <select class="form-control" wire:model="form.sub_category_id">
                            <option value="">Pilih Sub Kategori</option>
                            @foreach ($subCategory as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                        @error('form.sub_category_id')
                            <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Harga Awal</label>
                    <input type="text" wire:model="form.wholesale" class="form-control" />
                    @error('form.wholesale')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input type="text" wire:model="form.price" class="form-control" />
                    @error('form.price')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Peringatan QTY</label>
                    <input type="number" wire:model="form.alert_qty" class="form-control" />
                    @error('form.alert_qty')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Supplier</label>
                    <select class="form-select" wire:model="sch.supplier">
                        <option value="">Pilih Supplier</option>
                        @foreach($supplier as $sup)
                            <option value="{{ $sup->id }}">{{$sup->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Shift</label>
                    <select class="form-select" wire:model="sch.shifts">
                        <option value="">Pilih Shift</option>
                        <option value="morning">Morning</option>
                        <option value="afternoon">Afternoon</option>
                        <option value="evening">Evening</option>
                    </select>
                    @error('form.unit_id')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Qty</label>
                    <input class="form-control" type="text" wire:model="sch.qty" />
                    @error('form.qty')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- <input type="hidden" wire:model.defer="sch.is_direct" value="1" /> --}}


                <div class="mb-3">
                    <label class="form-label">Pajak</label>
                    <select class="form-control" wire:model="form.tax_rate_id">
                        <option value="">Pilih pajak</option>
                        @foreach ($tax as $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
                    @error('form.tax_rate_id')
                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit</label>
                    <select class="form-control" wire:model="form.unit_id">
                        <option value="">Pilih unit</option>
                        <option value="1">PCS</option>
                    </select>
                    @error('form.unit_id')
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
            <div class="card-footer">
                <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">Submit</button>
            </div> <!--end::Footer-->

            <div wire:loading class="mt-2" style="display: {{ $loading ? 'block' : 'none' }};">
                Processing your request...
            </div>
        </form> <!--end::Form-->
    @else
        <div class="card-primary card-outline card mb-4"> <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">{{ $action }} Product</div>
            </div> <!--end::Header--> <!--begin::Form-->
            <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Code</label>
                                <input disabled wire:model="form.code" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" wire:model="form.name">
                                @error('form.name')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Brand</label>
                                <select class="form-select" wire:model="form.brand_id">
                                    <option value="">Pilih Brand</option>
                                    @foreach ($brand as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('form.brand_id')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" wire:model="form.category_id" wire:change="sub_category_changed($event.target.value)">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($category as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('form.category_id')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            @if ($categoryHasSub == 1 && count($subCategory) > 0)
                                <div class="mb-3">
                                    <label class="form-label">Sub Kategori</label>
                                    <select class="form-control" wire:model="form.sub_category_id">
                                        <option value="">Pilih Sub Kategori</option>
                                        @foreach ($subCategory as $key => $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.sub_category_id')
                                        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Harga Awal</label>
                                <input type="number" wire:model="form.wholesale" class="form-control" />
                                @error('form.wholesale')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Harga Jual</label>
                                <input type="number" wire:model="form.price" class="form-control" />
                                @error('form.price')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pajak</label>
                                <select class="form-select" wire:model="form.tax_rate_id">
                                    <option value="">Pilih pajak</option>
                                    @foreach ($tax as $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('form.tax_rate_id')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <select class="form-select" wire:model="form.unit_id">
                                    <option value="">Pilih unit</option>
                                    @foreach ($unit as $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('form.unit_id')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tipe</label>
                                <select class="form-select" wire:model="form.type">
                                    <option value="">Pilih Tipe</option>
                                    <option value="1">Standard</option>
                                </select>
                                @error('form.type')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <select class="form-select" wire:model="form.barcode">
                                    <option value="">Pilih Barcode</option>
                                    <option value="1">Barcode 1</option>
                                </select>
                                @error('form.barcode')
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

                            <div class="mb-3">
                                <label class="form-label">Peringatan QTY</label>
                                <input type="number" wire:model="form.alert_qty" class="form-control" />
                                @error('form.alert_qty')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div> <!--end::Body--> <!--begin::Footer-->
                <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
            </form> <!--end::Form-->
        </div>
    @endif
</div>
