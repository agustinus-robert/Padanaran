<div>
    <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
        <div class="card-header">
            <div class="card-title">{{$action}} Transfer</div>
        </div> <!--end::Header--> <!--begin::Form-->
        <form wire:submit="save" enctype="multipart/form-data"> <!--begin::Body-->
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label><b>Reference</b></label>
                            <input type="text" class="form-control" disabled wire:model="{{$form['code']}}" value="{{$form['code']}}" />
                        </div>

                        <div class="col-md-4">
                            <label><b>Tanggal Transfer</b></label>
                            <input wire:model="inv.transfer_date" type="datetime-local" class="form-control" />
                        </div>

                        <div class="col-md-4">
                            <label><b>Status Transfer</b></label>
                            <select wire:model="inv.transfer_status" class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="1">Order</option>
                                <option value="2">Delivered</option>
                                <option value="3">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="row">
                        <div class="col-md-6">
                            <label><b>Gudang Awal</b></label>
                            <select class="form-select" wire:model="inv.transfer_from_warehouse">
                                <option value="">Pilih Gudang</option>
                                @foreach($warehouse as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label><b>Gudang Akhir</b></label>
                            <select class="form-select" wire:model="inv.transfer_to_warehouse">
                                <option value="">Pilih Gudang</option>
                                @foreach($warehouse as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">   
                    <input 
                            type="text" 
                            wire:model.live.debounce.500ms="query" 
                            placeholder="Masukkan 3 huruf, untuk melakukan pencarian..." 
                            class="form-control" />

                        @if(strlen($query) >= 3)
                            <ul class="list-group mt-2">
                                @forelse($results as $result)
                                     <li class="list-group-item" wire:click="addItem({{ $result['id'] }})" style="cursor: pointer;">
                                        {{ $result['name'] }} <!-- Sesuaikan field yang ingin ditampilkan -->
                                    </li>
                                @empty
                                    <li class="list-group-item">Tidak ada hasil</li>
                                @endforelse
                            </ul>
                        @endif
                </div>

                <div class="mb-5"> 
                    <h6 class="mt-4"><b>Item Terpilih</b></h6>
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jumlah Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        @if(count($selectedItems) > 0)
                            <tbody>
                                @foreach($selectedItems as $index => $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td><input 
                                            type="number" 
                                            class="form-control" 
                                            wire:model.lazy="selectedItems.{{ $index }}.qty" 
                                            min="1"
                                            wire:change="updateQty({{ $index }}, $event.target.value)"
                                        /></td>
                                        <td><button wire:click="removeItem({{ $item['id'] }})" class="btn btn-danger">Hapus</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card-footer">
                <input type="submit" class="btn btn-primary" value="simpan" />
            </div>
        </form> <!--end::Form-->
    </div>
</div>