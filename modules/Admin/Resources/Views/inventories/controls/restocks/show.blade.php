@extends('asset::layouts.default')

@section('title', 'Aset | Restok')

@section('navtitle', 'Restok inventaris')

@section('content')
<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-8">
        <div class="d-flex align-items-center mb-4">
            <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.controls.restocks.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
            <div class="ms-4">
                <h2 class="mb-1">Restok inventaris</h2>
                <div class="text-secondary">Silakan isi formulir di bawah ini untuk mengubah kuantitas inventaris</div>
            </div>
        </div>
        <div class="card border-0">
            <div class="card-body"><i class="mdi mdi-plus"></i> Restok inventaris</div>
            <div class="card-body border-top">
                <form class="form-block" action="{{ route('asset::inventories.controls.restocks.update', ['item' => $item->id, 'next' => request('next', route('asset::inventories.controls.restocks.index'))]) }}" method="POST" enctype="multipart/form-data"> 
                    @csrf @method('PUT')
                    <div class="mb-3 required">
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
                    <div class="mb-3 required">
                        <label class="form-label">Tindakan</label>
                        @foreach($actions as $action)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" id="action{{ $action->value }}" value="{{ $action->value }}" @checked(old('action', ($item->action?->value)) == $action->value) required>
                            <label class="form-check-label" for="action{{ $action->value }}">
                                {{ $action->label() }}
                            </label>
                        </div>
                        @endforeach  
                    </div>
                    <div class="mb-3 required">
                        <label class="form-label">Kuantitas</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" min="1" value="{{ old('quantity', $item->quantity) }}" required>
                        @error('quantity')
                        <small class="text-danger d-block"> {{ $message }} </small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
                        @error('description')
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
