@extends('asset::layouts.default')

@section('title', 'Aset | Reparasi')

@section('navtitle', 'Reparasi inventaris')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-8">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.controls.repairs.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Reparasi inventaris</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk mengubah kuantitas inventaris</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-plus"></i> Reparasi inventaris</div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('asset::inventories.controls.repairs.store', ['next' => request('next', route('asset::inventories.controls.repairs.index'))]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="item_id" class="form-label">Nama barang</label>
                            <select name="item_id" id="item_id" class="form-select item @error('item_id') is-invalid @enderror" onchange="triggerChange(event)">
                                <option value=""></option>
                                @foreach ($items as $key => $value)
                                    <option value="{{ $value->id }}" data-type="{{ \Modules\Core\Enums\PlaceableTypeEnum::forceTryFrom($value->placeable_type)->value }}" data-id="{{ $value->placeable_id }}">{{ $value->name }} - {{ !is_null($value->placeable?->name) ? $value->placeable?->name : $value->placeable?->user->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="required mb-4">
                            <label class="form-label">Lokasi</label>
                            <div class="row gy-2 gx-2">
                                <div class="col-xl-4 col-md-5">
                                    <select class="form-select @error('placeable_type') is-invalid @enderror" name="placeable_type" required>
                                        <option>-- Pilih --</option>
                                        @foreach ($placeables as $placeable)
                                            @php($ids = (new ($placeable->instance())())->with($placeable->relations())->where($placeable->conditions())->get())
                                            @if ($placeable->value == 3)
                                                @php($employees = $ids)
                                            @endif
                                            <option value="{{ $placeable->value }}" data-placeable="{{ $ids->pluck($placeable->getter(), 'id') }}" @selected(old('placeable_type') == $placeable->instance())>{{ $placeable->label() }}</option>
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
                        <div class="mb-4">
                            <label class="form-label">Kondisi setelah perbaikan</label>
                            @foreach ($conditions as $condition)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" id="condition{{ $condition->value }}" value="{{ $condition->value }}" @checked(old('condition') == $condition->value) required>
                                    <label class="form-check-label" for="condition{{ $condition->value }}">
                                        {{ $condition->label() }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Tindakan perbaikan</label>
                            <div class="row">
                                <div class="col-6">
                                    @foreach ($actions as $action)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="action" id="action{{ $action->value }}" value="{{ $action->value }}" @checked(old('action') == $action->value) required>
                                            <label class="form-check-label" for="action{{ $action->value }}">
                                                {{ $action->label() }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Catatan selama perbaikan</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Tindakan selama perbaikan</label>
                            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                            @error('description')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger"><i class="mdi mdi-check"></i> Simpan</button>
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

        .ts-control {
            border: 1px solid hsla(0, 0%, 82%, .2) !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea#content',
            height: "480",
            paste_data_images: true,
            relative_urls: false,
            plugins: 'autosave autoresize print preview paste searchreplace code fullscreen image link media table charmap hr pagebreak advlist lists wordcount imagetools noneditable charmap',
            menubar: false,
            toolbar: "formatselect bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | indent outdent preview",
            fontsize_formats: '8pt 11pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
        });
    </script>
    <script>
        let placeableId = @JSON($item->placeable_id ?? 0);

        const triggerChange = (e) => {
            let type = e.currentTarget.options[event.target.selectedIndex].dataset.type;
            let id = e.currentTarget.options[event.target.selectedIndex].dataset.id;
            document.querySelector('[name="placeable_type"]').value = type;
            document.querySelector('[name="placeable_type"]').dispatchEvent(new Event('change'));
            console.log(id);
            document.querySelector('[name="placeable_id"]').value = id;
        }

        const listPlaceableId = () => {
            let c = '';
            [].slice.call(document.querySelectorAll('[name="placeable_type"] option:checked')).map((select) => {
                let c = '';
                if (select.dataset.placeable) {
                    let placeable = JSON.parse(select.dataset.placeable);
                    for (i in placeable) {
                        c += '<option value="' + i + '" ' + ((placeableId == i) ? ' selected' : '') + '>' + placeable[i] + '</option>';
                    }
                }
                document.querySelector('[name="placeable_id"]').innerHTML = c.length ? c : '<option value>-- Pilih --</option>'
            })
        }

        new TomSelect('.item', {
            persist: false,
            searchField: 'text',
            placeholder: 'Cari barang di sini...',
        });

        window.addEventListener('DOMContentLoaded', () => {
            [].slice.call(document.querySelectorAll('[name="placeable_type"]')).map((el) => {
                el.addEventListener('change', listPlaceableId)
            });
        });
    </script>
@endpush
