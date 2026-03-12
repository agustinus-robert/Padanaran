@extends('layouts.horizontal-layout')

@section('title', isset($template) ? 'Ubah template gaji | ' : 'Buat template gaji | ')
@section('navtitle', isset($template) ? 'Ubah template gaji' : 'Buat template gaji')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@section('body-content')

@include('components.navbar-admin')
<div class="container-fluid row justify-content-center">
    <div class="col-md-11">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-gradient-dark text-white">
                <h6 class="text-white">{{ isset($template) ? 'Ubah' : 'Tambah' }} Template gaji</h6>
            </div>

            <div class="card-body">
                <form class="form-block"
                      action="{{ isset($template) ? route('core::company.salaries.templates.update', ['template' => $template->id, 'next' => request('next')]) : route('core::company.salaries.templates.store', ['next' => request('next')]) }}"
                      method="POST">
                    @csrf
                    @if(isset($template)) @method('PUT') @endif

                    {{-- Nama Template --}}
                    <x-input-group :isRow="true" required>
                        <x-label value="Nama" />
                        <x-col size="12">
                            <x-input
                                type="text"
                                name="name"
                                :value="old('name', $template->name ?? '')"
                                required
                                @class(['is-invalid' => $errors->has('name')])
                            />
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-col>
                    </x-input-group>

                    {{-- Komponen Gaji --}}
                    <x-input-group :isRow="true" required>
                        <x-label value="Komponen gaji" />
                        <x-col size="12">
                            <div class="table-responsive rounded border">
                                <table class="table-hover mb-0 table">
                                    <thead>
                                        <tr>
                                            <th nowrap class="pt-2">Slip</th>
                                            <th nowrap class="pt-2">Kategori</th>
                                            <th nowrap class="pt-2">Komponen</th>
                                            <th class="pt-2">Nominal</th>
                                            <th class="pt-2">Deskripsi</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    @foreach ($slips as $slip)

                                        @php
                                            $slipShown = false;
                                        @endphp

                                        @foreach ($slip->categories as $category)
                                            <tbody>
                                                @php
                                                    $stg_components = $components?->filter(fn($item) =>
                                                        $item->ctg_az == $loop->iteration && $item->slip_az == $loop->parent->iteration
                                                    ) ?? collect();

                                                    $categoryShown = false;
                                                @endphp

                                                @forelse($stg_components as $stg_component)
                                                    <tr class="form-index has-add-button">
                                                        <td class="td-hide-on-add">
                                                            @if (!$slipShown)
                                                                {{ $slip->name }}
                                                                @php $slipShown = true; @endphp
                                                            @endif
                                                        </td>
                                                        <td class="td-hide-on-add">
                                                            @if (!$categoryShown)
                                                                {{ $category->name }}
                                                                @php $categoryShown = true; @endphp
                                                            @endif
                                                        </td>
                                                        <td style="min-width:120px;">
                                                            <input type="hidden" data-name="slip_az" value="{{ $loop->parent->parent->iteration }}">
                                                            <input type="hidden" data-name="slip_name" value="{{ $slip->name }}">
                                                            <input type="hidden" data-name="ctg_az" value="{{ $loop->parent->iteration }}">
                                                            <input type="hidden" data-name="ctg_name" value="{{ $category->name }}">
                                                            <input type="hidden" data-name="name" value="{{ optional($stg_component)->name }}">

                                                            <x-select
                                                                data-name="component_id"
                                                                :options="$category->components->map(fn($c) => [
                                                                    'value' => $c->id,
                                                                    'label' => $c->name,
                                                                    'unit' => $c->unit?->label(),
                                                                    'stg_default' => optional($stg_component)->amount,
                                                                    'default' => $c->meta->default ?? null,
                                                                    'description' => $c->meta->description ?? null,
                                                                    'disabled' => $c->unit?->disabledState(),
                                                                ])"
                                                                :value="optional($stg_component)->component_id"
                                                                onchange="renderUnitComponent(event.currentTarget)"
                                                            />
                                                        </td>
                                                        <td style="min-width:120px;">
                                                            <div class="input-group">
                                                                <x-input
                                                                    type="number"
                                                                    min="0"
                                                                    data-name="amount"
                                                                    :value="optional($stg_component)->amount"
                                                                    required
                                                                    :disabled="is_null(optional($stg_component)->amount)"
                                                                />
                                                                <div class="input-group-text d-none"></div>
                                                            </div>
                                                        </td>
                                                        <td style="min-width:120px;">
                                                            <x-input data-name="description" disabled />
                                                        </td>
                                                        <td>
                                                            <x-btn
                                                                type="light"
                                                                color="danger"
                                                                :start="'<i class=\'mdi mdi-plus\'></i>'"
                                                                :class="['btn-add', 'd-none' => !$loop->first]"
                                                                onclick="addRow(event)">
                                                            </x-btn>

                                                            <x-btn
                                                                type="secondary"
                                                                :start="'<i class=\'mdi mdi-minus\'></i>'"
                                                                :class="['btn-remove', 'd-none' => $loop->first]"
                                                                onclick="removeRow(event)">
                                                            </x-btn>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="form-index has-add-button">
                                                        <td>{{ !$slipShown ? $slip->name : '' }}</td>
                                                        <td>{{ $category->name }}</td>
                                                        <td style="min-width:120px;">
                                                            <x-select
                                                                data-name="component_id"
                                                                :options="$category->components->map(fn($c) => [
                                                                    'value' => $c->id,
                                                                    'label' => $c->name,
                                                                    'unit' => $c->unit?->label(),
                                                                    'default' => $c->meta->default ?? null,
                                                                    'description' => $c->meta->description ?? null,
                                                                    // 'disabled' => $c->unit?->disabledState(),
                                                                ])"
                                                                placeholder="-- Pilih komponen --"
                                                                onchange="renderUnitComponent(event.currentTarget)"
                                                            />
                                                        </td>
                                                        <td>
                                                            <x-input type="number" min="0" data-name="amount" disabled />
                                                            <div class="input-group-text d-none"></div>
                                                        </td>
                                                        <td>
                                                            <x-input type="text" data-name="description" disabled />
                                                        </td>
                                                        <td>
                                                            <x-btn variant="info" type="light" color="danger" class="btn-add px-2 py-1" :start="'<i class=\'mdi mdi-plus\'></i>'" onclick="addRow(event)"><span class="material-symbols-rounded">add</span></x-btn>
                                                            <x-btn variant="danger" type="secondary" class="btn-remove d-none px-2 py-1" :start="'<i class=\'mdi mdi-minus\'></i>'" onclick="removeRow(event)"><span class="material-symbols-rounded">remove</span></x-btn>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        @endforeach
                                    @endforeach
                                </table>
                            </div>
                        </x-col>
                    </x-input-group>

                    {{-- Template Options --}}
                    <div class="row mx-auto">
                        <x-col size="8">
                            <div class="row mb-3">
                                    <div class="card card-body border">

                                        {{-- Template default biasa --}}
                                        <div class="form-check d-flex align-items-center mb-2">
                                            <input
                                                class="form-check-input"
                                                id="as_template"
                                                name="as_template"
                                                type="checkbox"
                                                value="1"
                                                {{ old('as_template', $template->as_template ?? false) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label ms-3" for="as_template">
                                                <div><strong>Jadikan sebagai template default</strong></div>
                                                <div class="text-muted">
                                                    Jika dicentang, maka penambahan komponen gaji karyawan selanjutnya akan menggunakan detail yang sama.
                                                </div>
                                            </label>
                                        </div>

                                        {{-- Template hari raya --}}
                                        <div class="form-check d-flex align-items-center mb-2">
                                            <input
                                                class="form-check-input"
                                                id="as_template_feastday"
                                                name="as_template_feastday"
                                                type="checkbox"
                                                value="1"
                                                {{ old('as_template_feastday', $template->as_template_feastday ?? false) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label ms-3" for="as_template_feastday">
                                                <div><strong>Jadikan sebagai template default hari raya</strong></div>
                                                <div class="text-muted">
                                                    Jika dicentang, maka penambahan komponen tunjangan hari raya karyawan selanjutnya akan menggunakan detail yang sama.
                                                </div>
                                            </label>
                                        </div>

                                        {{-- Template gaji ke 13 --}}
                                        <div class="form-check d-flex align-items-center mb-2">
                                            <input
                                                class="form-check-input"
                                                id="as_template_postyear"
                                                name="as_template_postyear"
                                                type="checkbox"
                                                value="1"
                                                {{ old('as_template_postyear', $template->as_template_postyear ?? false) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label ms-3" for="as_template_postyear">
                                                <div><strong>Jadikan sebagai template default gaji ke 13</strong></div>
                                                <div class="text-muted">
                                                    Jika dicentang, maka penambahan komponen gaji ke 13 karyawan selanjutnya akan menggunakan detail yang sama.
                                                </div>
                                            </label>
                                        </div>

                                    </div>

                                    {{-- Agreement --}}
                                    <div class="form-check mb-3 mt-3">
                                        <input class="form-check-input" id="agreement" type="checkbox" required>
                                        <label class="form-check-label" for="agreement">
                                            Dengan ini saya menyatakan data di atas adalah valid
                                        </label>
                                    </div>
                            </div>
                        </x-col>
                    </div>

                     <x-input-group>
                        <x-col size="12" offset="3">
                            <x-btn type="submit" variant="success">
                                {{ isset($meet) ? 'Update' : 'Simpan' }}
                            </x-btn>

                            <a class="btn btn-secondary"
                            href="{{ request('next', route('core::company.salaries.templates.index')) }}">
                                Kembali
                            </a>
                        </x-col>
                    </x-input-group>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
@endpush

@push('scripts')
    <script>
        const addRow = (e) => {
            let tr = e.currentTarget.closest('tr');
            let clone = tr.cloneNode(true);
            clone.classList.remove('has-add-button');

            Array.from(clone.querySelectorAll('.td-hide-on-add')).forEach(td => td.innerHTML = '');

            // Reset select
            let select = clone.querySelector('[data-name="component_id"]');
            if (select) select.selectedIndex = 0;

            // Reset inputs
            let amountInput = clone.querySelector('input[data-name="amount"]');
            if (amountInput) {
                amountInput.value = 0;
                amountInput.disabled = true; // disable sampai pilih komponen
            }

            let descInput = clone.querySelector('input[data-name="description"]');
            if (descInput) descInput.value = '';
            if (descInput) descInput.disabled = true;

            // Insert clone
            tr.parentNode.appendChild(clone);

            // Atur tombol add/remove
            let rows = tr.parentNode.querySelectorAll('.form-index');
            rows.forEach((row, i) => {
                let btnAdd = row.querySelector('.btn-add');
                let btnRemove = row.querySelector('.btn-remove');
                if (i === rows.length - 1) {
                    btnAdd.classList.remove('d-none');
                } else {
                    btnAdd.classList.add('d-none');
                    btnRemove.classList.remove('d-none');
                }
            });

            renderNameAttribute();
        };

        const removeRow = (e) => {
            e.currentTarget.closest('tr').remove();
            renderNameAttribute();
        };

        const renderNameAttribute = () => {
            document.querySelectorAll('.form-index').forEach((tr, index) => {
                tr.querySelectorAll('select,input').forEach(el => {
                    if (el.dataset.name) {
                        el.name = `items[${index}][${el.dataset.name}]`;
                    }
                });
            });
        };

        const renderUnitComponent = (el) => {
            let option = el.options[el.selectedIndex]; // select option
            if (!option) return;

            // Unit
            let unitEl = el.closest('td').nextElementSibling.querySelector('.input-group-text');
            if (option.dataset.unit) {
                unitEl.classList.remove('d-none');
                unitEl.innerHTML = option.dataset.unit;
            } else {
                unitEl.classList.add('d-none');
            }

            // Amount
            let amountInput = el.closest('td').nextElementSibling.querySelector('input[data-name="amount"]');
            if (amountInput) {
                let def = option.dataset.stgDefault || option.dataset.default || 0;
                amountInput.value = def;
                amountInput.disabled = option.dataset.disabled === '1';
            }

            // Description
            let descInput = el.closest('td').nextElementSibling.nextElementSibling.querySelector('input[data-name="description"]');
            if (descInput) {
                descInput.value = option.dataset.description || '';
                descInput.disabled = true;
            }

            // Hidden name
            let nameInput = el.closest('td').querySelector('input[data-name="name"]');
            if (nameInput) nameInput.value = option.text || '';
        };

        // Onload
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-name="component_id"]').forEach(el => {
                if (el.value) renderUnitComponent(el);
            });
            renderNameAttribute();
        });

    </script>
@endpush
