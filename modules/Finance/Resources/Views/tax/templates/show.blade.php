@extends('finance::layouts.default')

@section('title', 'Ubah template PPh21 | ')
@section('navtitle', 'Ubah template PPh21')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('finance::tax.templates.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Ubah template gaji</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah untuk mengubah data template gaji perusahaan</div>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <form class="form-block form-confirm" action="{{ route('finance::tax.templates.update', ['template' => $template->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
                        <div class="row required mb-3">
                            <label class="col-lg-3 col-xl-2 col-form-label">Nama</label>
                            <div class="col-lg-9 col-xl-9 col-xxl-6">
                                <input type="text" class="form-control @error('key') is-invalid @enderror" name="key" value="{{ old('key', $template->key) }}">
                            </div>
                        </div>
                        <div class="row required mb-3">
                            <label class="col-lg-3 col-xl-2 col-form-label">Komponen gaji</label>
                            <div class="col-xl-10">
                                <div class="table-responsive rounded border">
                                    <table class="table-hover mb-0 table">
                                        <thead>
                                            <tr>
                                                <th nowrap class="pt-2">Slip</th>
                                                <th nowrap class="pt-2">Kategori</th>
                                                <th nowrap class="pt-2">Komponen</th>
                                                <th class="pt-2">Nominal</th>
                                                <th class="pt-2">Deskripsi</th>
                                                <th class="pt-2">Disetahunkan</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        @foreach ($slips as $slip)
                                            @foreach ($slip->categories as $category)
                                                <tbody>
                                                    @foreach ($components->filter(fn($item) => $item['ctg_az'] == $loop->iteration && $item['slip_az'] == $loop->parent->iteration) as $stg_component)
                                                        <tr class="form-index has-add-button">
                                                            <td class="td-hide-on-add" nowrap>
                                                                @if ($loop->parent->first && $loop->first)
                                                                    {{ $slip->name }}
                                                                @endif
                                                            </td>
                                                            <td class="td-hide-on-add">
                                                                @if ($loop->first)
                                                                    {{ $category->name }}
                                                                @endif
                                                            </td>
                                                            <td style="min-width: 120px;">
                                                                <input type="hidden" data-name="slip_az" value="{{ $loop->parent->parent->iteration }}">
                                                                <input type="hidden" data-name="slip_name" value="{{ $slip->name }}">
                                                                <input type="hidden" data-name="ctg_az" value="{{ $loop->parent->iteration }}">
                                                                <input type="hidden" data-name="ctg_name" value="{{ $category->name }}">
                                                                <input type="hidden" data-name="name" value="{{ optional($stg_component)->name }}">
                                                                <select class="form-select" data-name="component_id" onchange="renderUnitComponent(event.currentTarget)">
                                                                    <option value="" data-disabled="1">-- Pilih komponen --</option>
                                                                    @foreach ($category->components as $component)
                                                                        <option value="{{ $component->id }}" data-unit="{{ $component->unit?->label() }}" data-stg-default="{{ optional($stg_component)->amount }}" data-default="{{ $component->meta->default ?? null }}" data-description="{{ $component->meta->description ?? null }}" data-disabled="{{ $component->unit?->disabledState() }}" @selected(optional($stg_component)['component_id'] == $component->id)>{{ $component->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td style="min-width: 120px;">
                                                                <div class="input-group">
                                                                    <input type="number" min="0" data-name="amount" class="form-control text-end" value="0" required>
                                                                    <div class="input-group-text d-none"></div>
                                                                </div>
                                                            </td>
                                                            <td style="min-width: 100px;">
                                                                <input type="text" data-name="description" class="form-control" disabled>
                                                            </td>
                                                            <td style="min-width: 120px;" class="text-center">
                                                                <div class="input-group py-1 px-2">
                                                                    <label class="input-group-text">
                                                                        <input class="form-check-input mt-0" data-name="multiplier" type="checkbox" value="1" checked>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-light text-danger rounded-circle btn-add @if (!$loop->first) d-none @endif px-2 py-1" onclick="addRow(event)"><i class="mdi mdi-plus"></i></button>
                                                                <button type="button" class="btn btn-secondary rounded-circle btn-remove @if ($loop->first) d-none @endif px-2 py-1" onclick="removeRow(event)"><i class="mdi mdi-minus"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            @endforeach
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-9 offset-lg-3 offset-xl-2">
                                <div class="card card-body border">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input" id="as_template" name="as_template" type="checkbox" value="1">
                                        <label class="form-check-label ms-3" for="as_template">
                                            <div><strong>Jadikan sebagai template default</strong></div>
                                            <div class="text-muted">Jika dicentang, maka penambahan komponen gaji karyawan selanjutnya akan menggunakan detail yang sama.</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="agreement" type="checkbox" required>
                                    <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                                </div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Perbarui</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::tax.templates.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const addRow = (e) => {
            let tr = e.currentTarget.parentNode.parentNode;
            let el = tr.cloneNode(true)
            el.classList.remove('has-add-button');
            Array.from(el.querySelectorAll('.td-hide-on-add')).map((el) => el.innerHTML = '')
            tr.parentNode.insertAdjacentHTML('beforeend', `<tr class="form-index">${el.innerHTML}</tr>`);
            Array.from(tr.parentNode.children).forEach((el, i) => {
                if (i > 0 && !el.classList.contains('has-add-button')) {
                    el.querySelector('.btn-remove').classList.remove('d-none');
                    el.querySelector('.btn-add').classList.add('d-none');
                }
                if (i == tr.parentNode.children.length - 1) {
                    el.querySelector('[data-name="component_id"]').selectedIndex = 0;
                    renderUnitComponent(el.querySelector('[data-name="component_id"]'));
                }
            });
            renderNameAttribute();
        }

        const removeRow = (e) => {
            e.target.parentNode.closest('tr').remove()
            renderNameAttribute();
        }

        const renderNameAttribute = () => {
            Array.from(document.querySelectorAll('.form-index')).map((tr, index) => {
                Array.from(tr.querySelectorAll('select,input')).map(input => {
                    if (input.dataset.name) {
                        input.name = `items[${index}][${input.dataset.name}]`;
                    }
                })
            })
        }

        const renderUnitComponent = (el) => {
            let checked = el.querySelector(':checked');
            if (checked && checked.dataset.unit) {
                let unit = el.parentNode.nextElementSibling.querySelector('.input-group-text');
                unit.classList.remove('d-none');
                unit.innerHTML = checked.dataset.unit;
            } else {
                el.parentNode.nextElementSibling.querySelector('.input-group-text').classList.add('d-none');
            }

            let def = (checked && (checked.dataset.stgDefault || checked.dataset.default));
            el.parentNode.nextElementSibling.querySelector('input[type="number"]').value = 0;

            let description = (checked && checked.dataset.description);
            el.parentNode.nextElementSibling.nextElementSibling.querySelector('input[type="text"]').value = description;

            let name = (checked && checked.text);
            el.parentNode.querySelector('[data-name="name"]').value = name || ''

            if (disabled = (checked && checked.dataset.disabled)) {
                Array.from(el.parentNode.parentNode.querySelectorAll('input:not([type="hidden"])')).forEach(el => (el.value = ''));
            }
            Array.from(el.parentNode.parentNode.querySelectorAll('input:not([type="hidden"])')).forEach(el => el.toggleAttribute('disabled', disabled));
        }

        document.addEventListener('DOMContentLoaded', () => {
            Array.from(document.querySelectorAll('[data-name="component_id"]')).forEach(el => {
                if (el.value) {
                    renderUnitComponent(el);
                }
            });
            renderNameAttribute();
        })
    </script>
@endpush
