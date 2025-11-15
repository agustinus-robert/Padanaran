@extends('finance::layouts.default')

@section('title', 'Ubah template gaji | ')
@section('navtitle', 'Ubah template gaji')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::payroll.templates.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Ubah template gaji</h2>
            <div class="text-secondary">Silakan isi formulir di bawah untuk mengubah data template gaji karyawan</div>
        </div>
    </div>
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form class="form-block" action="{{ route('finance::payroll.templates.update', ['template' => $template->id, 'employee' => $template->empl_id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
                <div class="row mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Nama karyawan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-4">
                        <input type="hidden" class="form-control" value="{{ $template->empl_id }}" disabled readonly>
                        <input type="text" class="form-control" value="{{ $template->employee->user->name }}" disabled readonly>
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Nama</label>
                    <div class="col-lg-9 col-xl-9 col-xxl-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $template->name) }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Prefix</label>
                    <div class="col-lg-9 col-xl-9 col-xxl-6">
                        <input type="text" class="form-control @error('prefix') is-invalid @enderror" name="prefix" value="{{ old('prefix', $template->prefix) }}">
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Masa berlaku</label>
                    <div class="col-lg-9 col-xl-9 col-xxl-6">
                        <div class="input-group">
                            <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ $template->start_at->format('Y-m-d H:i:s') }}" required="">
                            <input type="datetime-local" class="form-control @error('end_at') is-invalid @enderror" name="end_at" value="{{ $template->end_at->format('Y-m-d H:i:s') }}" required="">
                        </div>
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
                                        <th width="50"></th>
                                    </tr>
                                </thead>
                                @foreach ($slips as $slip)
                                    @foreach ($slip->categories as $category)
                                        <tbody data-slip-name="{{ $slip->name }}" data-slip-ctgname="{{ $category->name }}">
                                            @foreach ($template->items->filter(fn($item) => $item->ctg_az == $loop->iteration && $item->slip_az == $loop->parent->iteration) as $stg_component)
                                                <tr class="form-index has-add-button">
                                                    <td class="td-hide-on-add">
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
                                                        <input type="hidden" data-name="name" value="{{ optional($stg_component)['name'] }}">
                                                        <select class="form-select" data-name="component_id" onchange="renderUnitComponent(event.currentTarget)">
                                                            <option value="" data-disabled="1">-- Pilih template --</option>
                                                            @foreach ($category->components as $component)
                                                                <option value="{{ $component->id }}" data-unit="{{ $component->unit?->label() }}" data-stg-default="{{ optional($stg_component)['amount'] }}" data-default="{{ $component->meta->default ?? null }}" data-template-default="{{ $template->items->filter(fn($item) => $item->component_id == $component->id)->first()['amount'] ?? null }}" data-description="{{ $component->meta->description ?? null }}"
                                                                    data-disabled="{{ $component->unit?->disabledState() }}" @selected(optional($stg_component)['component_id'] == $component->id)>{{ $component->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        <div class="input-group">
                                                            <input type="number" min="0" data-name="amount" class="form-control" required @if (is_null(optional($stg_component)['amount'])) disabled @endif value="{{ optional($stg_component)['amount'] }}">
                                                            <div class="input-group-text d-none"></div>
                                                        </div>
                                                    </td>
                                                    <td style="min-width: 120px;">
                                                        <input type="text" data-name="description" class="form-control" disabled>
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
                        <div class="form-check mb-3">
                            <input class="form-check-input" id="agreement" type="checkbox" required>
                            <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                        </div>
                        <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::payroll.templates.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
@endpush

@push('scripts')
    <script>
        let overtimeSalary = @JSON($overtimeSalary);
        let primarySlip = @JSON($primarySlip);
        let secondarySlip = @JSON($secondarySlip);
        let defaultComponent = @JSON($defaultComponent);
        let settings = @JSON($settings);
        let cmptid = @JSON($cmptid);

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
            let row = el.closest('.form-index');

            if (checked && checked.dataset.unit) {
                let unit = el.parentNode.nextElementSibling.querySelector('.input-group-text');
                unit.classList.remove('d-none');
                unit.innerHTML = checked.dataset.unit;
            } else {
                el.parentNode.nextElementSibling.querySelector('.input-group-text').classList.add('d-none');
            }

            let def = (checked && (checked.dataset.templateDefault || checked.dataset.default));
            el.parentNode.nextElementSibling.querySelector('input[type="number"]').value = def;

            let description = (checked && checked.dataset.description);
            el.parentNode.nextElementSibling.nextElementSibling.querySelector('input[type="text"]').value = description;

            let name = (checked && checked.text);
            el.parentNode.querySelector('[data-name="name"]').value = name || ''

            if (disabled = (checked && checked.dataset.disabled)) {
                Array.from(el.parentNode.parentNode.querySelectorAll('input:not([type="hidden"])')).forEach(el => (el.value = ''));
            }

            if (checked) {
                let p = getPrimarySalary();
                for (let index = 0; index < settings.length; index++) {
                    if (checked && checked.value == settings[index].meta.component) {
                        let l = eval(settings[index].meta.calculation);
                        row.querySelector('[data-name="amount"]').value = Math.round(parseFloat(l));
                    }
                }
            }
            Array.from(el.parentNode.parentNode.querySelectorAll('input:not([type="hidden"])')).forEach(el => el.toggleAttribute('disabled', disabled));
        }

        // Function get primary salary
        function getPrimarySalary() {
            let amt = '';
            let parent = document.querySelector(`[data-slip-ctgname="${primarySlip.name}"]`);
            Array.from(parent.querySelectorAll('.form-index')).map((tr, index) => {
                Array.from(tr.querySelectorAll('[data-name="component_id"]')).map((select, i) => {
                    Array.from(select.selectedOptions).filter(el => el.text == `${defaultComponent.name}` && el.value == `${defaultComponent.id}`).map((opt, k) => {
                        amt = parseFloat(opt.parentNode.closest('.form-index').querySelector('[data-name="amount"]').value);
                    })
                })
            });
            return amt;
        }

        const newComponentRender = (e) => {
            let secondaryParent = document.querySelector(`[data-slip-ctgname="${secondarySlip.name}"]`);
            Array.from(secondaryParent.querySelectorAll('.form-index')).map((tr, index) => {
                Array.from(tr.querySelectorAll('[data-name="component_id"]')).filter(sl => cmptid.includes(sl.value)).map((select, i) => {
                    let p = e.currentTarget.value;
                    for (let index = 0; index < settings.length; index++) {
                        if (select.value == settings[index].meta.component) {
                            let l = eval(settings[index].meta.calculation);
                            select.parentElement.nextElementSibling.querySelector('[data-name="amount"]').value = Math.round(parseFloat(l));
                        }
                    }
                })
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            Array.from(document.querySelectorAll('[data-name="component_id"]')).forEach(el => {
                if (el.value) {
                    renderUnitComponent(el);
                }
                if (el.value == defaultComponent.id) {
                    el.parentNode.nextElementSibling.querySelector('[data-name="amount"]').addEventListener('keyup', function(e) {
                        newComponentRender(e);
                    })
                }
            });
            renderNameAttribute();
        })
    </script>
@endpush
