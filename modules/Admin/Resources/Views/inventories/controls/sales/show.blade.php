@extends('asset::layouts.default')

@section('title', 'Aset | Penjualan')

@section('navtitle', 'Penjualan inventaris')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-8">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.controls.sales.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Penjualan inventaris</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk mengisi data penjualan inventaris</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-plus"></i> Penjualan inventaris</div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('asset::inventories.controls.sales.update', ['sale' => $sale->id, 'next' => request('next', route('asset::inventories.controls.restocks.index'))]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Judul</label>
                            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $sale->name }}">
                            @error('name')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="items-body" class="form-label">Daftar barang</label>
                            <div id="items-body table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="3%" class="text-center"></th>
                                            <th>Inventaris</th>
                                            <th width="15%">Harga</th>
                                            <th width="10%">Jumlah</th>
                                            <th width="20%">Total</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-body">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label">Total pengajuan</label>
                            <input name="total" id="total" class="form-control total @error('total') is-invalid @enderror" rows="1" value="{{ $sale->meta->total }}" required>
                            @error('total')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"" rows="1" value="{{ $sale->meta->description ?? '' }}" required></textarea>
                            @error('description')
                                <small class="text-danger d-block"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="text-muted mb-2">Mohon diperhatikan, untuk barang yang mempunyai nomor inventaris sendiri, harap dimasukan satu persatu.</div>
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
    <template id="items-template">
        <tr class="has-add-btn form-index">
            <td class="text-center">
                <div class="number text-muted py-2"></div>
            </td>
            <td>
                <select type="text" class="form-control ctg" data-name="inventory_id"></select>
            </td>
            <td><input type="number" class="form-control price" data-name="price" onkeyup="initCalculation(event)"></td>
            <td><input type="number" class="form-control qty" data-name="qty" value="1" readonly></td>
            <td><input type="number" class="form-control subtotal" data-name="subtotal"></td>
            <td>
                <button type="button" class="btn btn-light text-danger rounded-circle btn-add px-2 py-1" onclick="addRow(event)"><i class="mdi mdi-plus"></i></button>
                <button type="button" class="btn btn-secondary rounded-circle btn-remove d-none px-2 py-1" onclick="removeRow(event)"><i class="mdi mdi-minus"></i></button>
            </td>
        </tr>
    </template>
    <script>
        let NUM = 0;
        let MAX = 50;
        let items = {!! json_encode($sale->meta->items) !!}
        let desc = {!! json_encode($sale->meta->description) !!}

        const debounce = (callback) => {
            let t;
            return (a) => {
                clearTimeout(t);
                t = setTimeout(() => callback(a), 600);
            };
        };

        const removeRow = (el) => {
            NUM--;
            el.target.parentNode.closest('tr').remove()
            renderNameAttribute();
            renderCalculation();
        }

        const renderNameAttribute = () => {
            [...document.querySelectorAll('.form-index')].map((tr, index) => {
                [...tr.querySelectorAll('select,input')].map(input => {
                    if (input.dataset.name) {
                        input.name = `items[${index}][${input.dataset.name}]`;
                    }
                });
                [...tr.querySelectorAll('.number')].map(e => {
                    e.innerHTML = index + 1;
                });
            });
        }

        const renderCalculation = () => {
            [...document.querySelectorAll('.form-index')].map((tr, index) => {
                let p = parseFloat(tr.querySelector('.price').value);
                let q = parseFloat(tr.querySelector('.qty').value);
                tr.querySelector('.subtotal').value = Math.floor(p * q);
            });
            calculateAll();
        }

        const calculateAll = debounce(() => {
            let totalAll = [...document.querySelectorAll('.subtotal')].map(el => parseFloat(el.value || 0)).reduce((x, y) => x + y);
            document.querySelector('[name="total"]').value = totalAll;
        });

        const renderTomSelect = (el) => {
            new TomSelect(el, {
                persist: false,
                createOnBlur: true,
                create: true,
                maxItems: 1,
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                placeholder: 'Inventaris',
                // selected_items: selected,
                load: function(q, callback) {
                    fetch('{{ route('api::asset.inventories') }}?q=' + encodeURIComponent(q))
                        .then(response => response.json())
                        .then(json => {
                            callback(json.data);
                        })
                        .catch(() => {
                            callback();
                        });
                }
            });
        }

        const addRow = (e, data) => {
            let tbody = document.getElementById('items-body');
            let template = document.getElementById('items-template');
            if (document.getElementById('items-body').querySelectorAll('.form-index').length < MAX) {
                if (document.getElementById('items-body').querySelectorAll('.form-index').length == 0) {
                    tbody.insertAdjacentHTML('beforeend', template.innerHTML);
                } else {
                    let newTemplate = template.cloneNode(true);
                    newTemplate.classList.remove('has-add-button')
                    tbody.insertAdjacentHTML('beforeend', newTemplate.innerHTML);
                }
                let ctg = tbody.children[tbody.children.length - 1].querySelector('.ctg');
                ctg.insertAdjacentHTML('beforeend', `<option value="${data?.inventory_id}">${data?.inv_name}</option>`)
                ctg.value = data?.inventory_id || '';
                tbody.children[tbody.children.length - 1].querySelector('.price').value = parseFloat(data?.price || 0);
                renderNameAttribute();
                renderTomSelect(`[name="items[${NUM}][inventory_id]"]`);
                if (e?.currentTarget) e.currentTarget.classList.toggle('disabled', document.getElementById('items-body').querySelectorAll('.items-row').length == MAX);
                document.querySelector('[name="description"]').value = desc;
                NUM++;
            }
            [...document.querySelectorAll('.form-index')].forEach((el, i) => {
                if (i > 0 && !el.classList.contains('has-add-button')) {
                    el.querySelector('.btn-remove').classList.remove('d-none');
                    el.querySelector('.btn-add').classList.add('d-none');
                }
            });
        }

        const initCalculation = debounce((e) => {
            renderCalculation();
        });

        document.addEventListener('DOMContentLoaded', () => {
            items.forEach(item => {
                addRow({}, item)
            })
            renderNameAttribute();
            renderCalculation();
        });
    </script>
@endpush
