<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="font-size-18 mb-0">Penjualan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Penjualan</a></li>
                        <li class="breadcrumb-item active">{{ $action }} Penjualan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">{{ $action }} Penjualan</div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="col-md-12 mb-3">
                            <label><b>Reference</b></label>
                            <input type="text" class="form-control" disabled value="{{ $form['code'] }}" />
                        </div>

                        <div class="col-md-12 mb-3">
                            <label><b>Cari Barang</b></label>
                            <input type="text" wire:model.live.debounce.500ms="query" placeholder="Masukkan 3 huruf, untuk melakukan pencarian..." class="form-control" />

                            @if (strlen($query) >= 3)
                                <ul class="list-group mt-2">
                                    @forelse($results as $result)
                                        <li class="list-group-item" wire:click="addItem({{ $result->id }})" style="cursor: pointer;">
                                            {{ $result->name }} - ({{ $result->available_stock ?? 0 }})
                                        </li>
                                    @empty
                                        <li class="list-group-item">Tidak ada hasil</li>
                                    @endforelse
                                </ul>
                            @endif
                        </div>

                        <div class="mb-5">
                            <h6 class="mt-4"><b>Item Terpilih</b></h6>

                            <table class="table-bordered table-striped mt-2 table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th class="text-center" style="width: 150px;">Jumlah</th>
                                        <th class="text-end" style="width: 180px;">Harga</th>
                                        <th class="text-center" style="width: 100px;">#</th>
                                    </tr>
                                </thead>

                                @if (!empty($selectedItems) && count($selectedItems) > 0)
                                    <tbody>
                                        @foreach ($selectedItems as $index => $item)
                                            <tr>
                                                <td>{{ $item['name'] ?? 'Tidak ada nama' }}</td>
                                                <td class="text-center">
                                                    <input type="number" class="form-control form-control-sm text-center" min="1" wire:model.defer="selectedItems.{{ $index }}.qty" wire:change="updateQty({{ $index }}, $event.target.value)" />
                                                </td>
                                                <td class="text-end">
                                                    Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}
                                                    <input type="hidden" wire:model="selectedItems.{{ $index }}.price" value="{{ $item['price'] ?? 0 }}" />
                                                </td>
                                                <td class="text-center">
                                                    <button wire:click="removeItem({{ $item['id'] ?? 0 }})" class="btn btn-sm btn-danger">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @endif
                            </table>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-12">
                                    <label><b>Tanggal Penjualan</b></label>
                                    <input wire:model="inv.sale_date" type="datetime-local" class="form-control" />
                                </div>

                                {{-- <div class="col-md-4">
                                    <label><b>Warehouse</b></label>
                                    <select class="form-select" wire:model="inv.warehouse_id">
                                        <option value="">Pilih Gudang</option>
                                        @foreach ($warehouse as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label><b>Diskon</b></label>
                                <input class="form-control" type="number" wire:change="updateDiscount" wire:model="inv.discount" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label><b>PPN</b></label>
                                <input class="form-control" type="text" wire:model="inv.ppn" disabled />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label><b>Status Penjualan</b></label>
                                <select wire:model="inv.sale_status" class="form-control">
                                    <option value="">Pilih Status</option>
                                    <option value="1">Ordered</option>
                                    <option value="2">Delivered</option>
                                    <option value="3">Completed</option>
                                </select>
                            </div>

                            <div class="col-md-12 mt-2">
                                <label><b>Grand total</b></label>
                                <br />
                                <b>Rp {{ number_format($grandTotal, 2) }}</b>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-footer">
                <input type="submit" class="btn btn-primary" value="simpan" />
            </div>
        </form> <!--end::Form-->
    </div>
</div>
