@extends('asset::layouts.default')

@section('title', 'Aset | Pembelian')

@section('navtitle', 'Pembelian inventaris')

@section('content')
<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-8">
        <div class="d-flex align-items-center mb-4">
            <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.controls.purchases.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
            <div class="ms-4">
                <h2 class="mb-1">Pembelian inventaris</h2>
                <div class="text-secondary">Silakan isi formulir di bawah ini untuk mengisi data pembelian inventaris</div>
            </div>
        </div>
        <div class="card border-0">
            <div class="card-body"><i class="mdi mdi-plus"></i> Pembelian inventaris</div>
            <div class="card-body border-top">
                <form class="form-block" action="{{ route('asset::inventories.controls.purchases.update', ['item' => $item->id, 'next' => request('next', route('asset::inventories.controls.restocks.index'))]) }}" method="POST" enctype="multipart/form-data"> 
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama barang</label>
                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $item->name }}">
                    </div>
                    <div class="mb-3 required">
                        <label class="form-label">Lokasi</label>
                        <div class="row gy-2 gx-2">
                            <div class="col-xl-4 col-md-5">
                                <select class="form-select @error('placeable_type') is-invalid @enderror" name="placeable_type" required>
                                    <option>-- Pilih --</option>
                                    @foreach($placeables as $placeable)
                                    @php($ids = (new ($placeable->instance()))->with($placeable->relations())->where($placeable->conditions())->get())
                                    @if($placeable->value == 3)
                                    @php($employees = $ids)
                                    @endif
                                    <option value="{{ $placeable->value }}" data-placeable="{{ $ids->pluck($placeable->getter(), 'id') }}" @selected(old('placeable_type', $item->placeable_type) == $placeable->instance())>{{ $placeable->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-8 col-md-7">
                                <select class="form-select @error('placeable_id') is-invalid @enderror" name="placeable_id" required>
                                    <option>-- Pilih --</option>
                                </select>
                            </div>
                        </div>
                        @error('placeable_type')
                        <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                        @error('placeable_id')
                        <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Harga</label>
                            <div class="input-group">
                                <div class="input-group-text">Rp</div>
                                <input type="number" class="form-control @error('bought_price') is-invalid @enderror" name="bought_price" value="{{ old('bought_price', $item->bought_price) }}" required>
                            </div>
                            @error('bought_price')
                            <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Tanggal pembelian</label>
                            <input type="datetime-local" class="form-control @error('bought_at') is-invalid @enderror" name="bought_at" value="{{ old('bought_at', $item->bought_at) }}" required>
                            @error('bought_at')
                            <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Keterangan</label>
                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $item->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor inventaris</label>
                        <input type="text" class="form-control @error('meta[inv_num]') is-invalid @enderror" name="meta[inv_num]" value="{{ old('meta[inv_num]') }}">
                        @error('meta[inv_num]')
                            <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lampiran/nota pembelian</label>
                        <div id="files">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="input-group me-2">
                                    <input type="file" class="form-control @error('files.0') is-invalid @enderror" name="files[]" accept="image/jpeg,image/png,application/pdf"/>
                                    <button id="files-clear" type="button" class="btn btn-light text-dark"><i class="mdi mdi-close"></i></button>
                                </div>
                                <button type="button" class="btn btn-light text-danger px-2 py-1 rounded-circle btn-add"><i class="mdi mdi-plus"></i></button>
                            </div>
                        </div>
                        <small class="form-text">Maksimal berkas adalah 2MB, berkas dapat berupa jpeg, jpg, png, atau pdf</small>
                        @error('files.*')
                        <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger"><i class="mdi mdi-check"></i> Perbarui</button>   
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
<style type="text/css">
    .ts-wrapper {
        padding: 0 !important;
    }
    .ts-control{
        border: 1px solid hsla(0,0%,82%,.2) !important;
    }
</style>
@endpush

@push('scripts')
    <script>
        let placeableId = {{ json_encode($item->placeable_id) }};

        console.log(placeableId);

        let removeAttachment = (el) => {
            el.currentTarget.parentNode.remove();
            document.querySelector('#files .btn-add').classList.toggle('disabled', document.getElementById('files').querySelectorAll('input').length > 3);
        }

        window.addEventListener('DOMContentLoaded', () => {
            let listPlaceableId = () => {
                let c = '';
                [].slice.call(document.querySelectorAll('[name="placeable_type"] option:checked')).map((select) => {
                    let c = '';
                    if(select.dataset.placeable) {
                        let placeable = JSON.parse(select.dataset.placeable);
                        for (i in placeable) {
                            c += '<option value="' + i + '" ' + ((placeableId == i) ? ' selected' : '') + '>' + placeable[i] + '</option>';
                        }
                    }
                    document.querySelector('[name="placeable_id"]').innerHTML = c.length ? c : '<option value>-- Pilih --</option>'
                })
            }

            [].slice.call(document.querySelectorAll('[name="placeable_type"]')).map((el) => {
                el.addEventListener('change', listPlaceableId)
            });
            listPlaceableId();

            document.querySelector('#files .btn-add').addEventListener('click', (e) => {
                if(document.getElementById('files').querySelectorAll('input').length < 3) {
                    document.getElementById('files').insertAdjacentHTML('beforeend', document.getElementById('files-template').innerHTML);
                    e.currentTarget.classList.toggle('disabled', document.getElementById('files').querySelectorAll('input').length == 3)
                }
            });

            document.getElementById('files-clear').addEventListener('click', (el) => {
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
    </script>
@endpush
