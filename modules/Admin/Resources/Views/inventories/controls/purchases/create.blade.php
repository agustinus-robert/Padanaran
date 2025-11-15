@extends('asset::layouts.default')

@section('title', 'Aset | Pembelian')

@section('navtitle', 'Pembelian inventaris')

@section('content')
<div class="row justify-content-center">
    <div class="col-xxl-12 col-xl-12">
        <div class="d-flex align-items-center mb-4">
            <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.items.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
            <div class="ms-4">
                <h2 class="mb-1">Pembelian inventaris</h2>
                <div class="text-secondary">Silakan isi formulir di bawah ini untuk mengubah pembelian inventaris</div>
            </div>
        </div>
        <div class="card border-0">
            <div class="card-body"><i class="mdi mdi-plus"></i> Pembelian inventaris</div>
            <div class="card-body border-top">
                <form class="form-block" action="{{ route('asset::inventories.controls.purchases.store', ['item' => $proposal->id, 'next' => request('next', route('asset::inventories.proposals.index'))]) }}" method="POST" enctype="multipart/form-data"> @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Pengajuan</label>
                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $proposal->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="1" readonly>{{ $proposal->description }}</textarea>
                        @error('description')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="items-body" class="form-label">Daftar barang</label>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Lokasi/pengguna</th>
                                        <th>Pembelian</th>
                                        <th>Harga</th>
                                        <th>Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proposal->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->placeable_name }}</td>
                                        <td><input type="datetime-local" class="form-control" name="items[{{ $item->id }}][bought_at]" value="{{ old('bought_at', $item->bought_at) }}" required></td>
                                        <td><input type="number" class="form-control" name="items[{{ $item->id }}][bought_price]" min="0" value="{{ old('bought_price', $item->bought_price) }}" required></td>
                                        <td>
                                            <select name="items[{{ $item->id }}][condition]" id="condition" class="form-select" required>
                                                <option value=""></option>
                                                @foreach($conditions as $condition)
                                                    <option value="{{ $condition->value }}" @selected(old('condition', $item->condition?->value) == $condition->value)>{{ $condition->label() }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Keterangan</td>
                                        <td colspan="4"><textarea name="items[{{ $item->id }}][description]" id="description" class="form-control"></textarea></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Lampiran</td>
                                        <td colspan="4">
                                            <div class="input-group me-2">
                                                <input type="file" class="form-control @error('files.0') is-invalid @enderror" name="files[]" accept="image/jpeg,image/png,application/pdf"/>
                                                <button type="button" class="files-clear btn btn-light text-dark"><i class="mdi mdi-close"></i></button>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger"><i class="mdi mdi-check"></i> Perbarui</button>   
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.files-clear').forEach(function(item) {
                item.addEventListener('click', (el) => {
                    let input = el.currentTarget.parentNode.children[0];
                    try{
                        input.value = '';
                        if(input.value){
                            input.type = "text";
                            input.type = "file";
                        }
                    }catch(e){}
                });
            });
        });
    </script>
@endpush