<div>
    <form wire:submit.prevent="save">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Rincian Penawaran</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="outletId">Outlet</label>
                            <select id="outletId" class="form-control" name="outletId" wire:model="form.outletId">
                                <option value="">Pilih Outlet</option>
                    
                                @foreach ($outlets as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                @endforeach
                            </select>
                            @error('form.outletId') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="payment_on">Pembayaran</label>
                            <select id="payment_on" class="form-control" name="pay" wire:model="form.payment_on">
                                <option value="">Pilih Siklus Pembayaran</option>

                                @php
                                    use Modules\Core\Enums\SupplierPaymentEnum;
                                    $enumPayment = SupplierPaymentEnum::cases();
                                @endphp

                                @foreach($enumPayment as $pay)
                                    <option value="{{ $pay->value }}">{{ ucfirst(strtolower($pay->name)) }}</option>
                                @endforeach
                            </select>
                            @error('form.payment_on') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Daftar Penawaran Produk</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Harga (Per Barang)</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $index => $row)
                                <tr wire:key="row-{{ $index }}">
                                    <td>
                                        <input type="text" wire:model="rows.{{ $index }}.name" class="form-control">

                                        @error("rows.$index.name")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" wire:model="rows.{{ $index }}.price" class="form-control">
                                        
                                        @error("rows.$index.price")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="file" wire:model="rows.{{ $index }}.file" class="form-control">
                                        
                                        @if(isset($row['image_name']) && $row['image_name'])
                                            <div class="mt-1">
                                                <a href="{{ isset($row['location']) ? asset('uploads'.'/'.$row['location'] . '/' . $row['image_name']) : '#' }}" target="_blank">
                                                    {{ $row['image_name'] }}
                                                </a>
                                            </div>
                                        @endif

                                        @error("rows.$index.file")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="text-danger"
                                            wire:click="removeRow({{ $index }})">
                                            <i class="mdi mdi-trash-can-outline"></i> 
                                    </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tombol kiri & kanan sejajar -->
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-primary" wire:click="addRow">
                        Tambah Baris <i class="mdi mdi-plus"></i>
                    </button>
                    <button type="button" class="btn btn-success" wire:click="save">
                        Simpan <i class="mdi mdi-content-save-outline"></i>
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>