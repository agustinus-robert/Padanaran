@extends('hrms::layouts.default')

@section('title', 'Tambah template gaji | ')
@section('navtitle', 'Tambah template gaji')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::payroll.templates.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Tambah template gaji</h2>
            <div class="text-secondary">Silakan isi formulir di bawah untuk menambahkan data template gaji karyawan</div>
        </div>
    </div>
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form class="form-block" action="{{ route('hrms::payroll.templates.store', ['employee' => $employee->id, 'next' => request('next')]) }}" method="POST"> @csrf
                <div class="row mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Nama karyawan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-4">
                        <input type="text" class="form-control" readonly disabled value="{{ $employee->user->name }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label required">Template gaji</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <div class="card @error('template_id') border-danger mb-1 @enderror mb-0">
                            <div class="overflow-auto rounded" style="max-height: 300px;">
                                <label class="card-body border-secondary d-flex align-items-center border-bottom py-2">
                                    <input class="form-check-input me-3" type="radio" name="template_id" data-components="[]" data-addon-text="" checked onchange="setNameValueFromSelectedTemplate(event.currentTarget)">
                                    <div>
                                        <div class="fw-bold mb-0">Tanpa template</div>
                                    </div>
                                </label>
                                @foreach ($templates as $template)
                                    <label class="card-body border-secondary d-flex align-items-center @if (!$loop->last) border-bottom @endif py-2">
                                        <input class="form-check-input me-3" type="radio" name="template_id" data-addon-text="{{ $template->name }}" data-components="{{ json_encode($template->components) }}" data-prefix="{{ $template->meta?->prefix ?? '' }}" value="{{ $template->id }}" onchange="setNameValueFromSelectedTemplate(event.currentTarget)">
                                        <div>
                                            <div class="fw-bold mb-0">{{ $template->name }}</div>
                                            <div class="small text-muted">Dibuat {{ $template->created_at->diffForHumans() }} dengan {{ count($template->components) }} komponen</div>
                                        </div>
                                    </label>
                                @endforeach
                                @if (!is_null($activeTemplate))
                                    <label class="card-body border-secondary d-flex align-items-center border-top py-2">
                                        <input class="form-check-input me-3" type="radio" name="template_id" data-components="{{ json_encode($activeTemplate->items) }}" data-prefix="{{ $activeTemplate->prefix ?? '' }}" value="{{ $activeTemplate->cmp_template_id }}" data-addon-text="{{ $activeTemplate->prefix }}" onchange="setNameValueFromSelectedTemplate(event.currentTarget)">
                                        <div>
                                            <div class="fw-bold mb-0">{{ $activeTemplate->name }}</div>
                                            <div class="small text-muted">Dibuat {{ $activeTemplate->created_at->diffForHumans() }} dengan {{ count($activeTemplate->items) }} komponen</div>
                                        </div>
                                    </label>
                                @endif
                                @if (!is_null($lastTemplate))
                                    <label class="card-body border-secondary d-flex align-items-center border-top py-2">
                                        <input class="form-check-input me-3" type="radio" name="template_id" data-components="{{ json_encode($lastTemplate->items) }}" data-prefix="{{ $lastTemplate->prefix }}" value="{{ $lastTemplate->cmp_template_id }}" data-addon-text="{{ $lastTemplate->prefix }}" onchange="setNameValueFromSelectedTemplate(event.currentTarget)">
                                        <div>
                                            <div class="fw-bold mb-0">{{ $lastTemplate->name }}</div>
                                            <div class="small text-muted">Dibuat {{ $lastTemplate->created_at->diffForHumans() }} dengan {{ count($lastTemplate->items) }} komponen</div>
                                        </div>
                                    </label>
                                @endif
                            </div>
                        </div>
                        @error('template_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Nama</label>
                    <div class="col-lg-9 col-xl-9 col-xxl-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Prefix</label>
                    <div class="col-lg-9 col-xl-9 col-xxl-6">
                        <input type="text" class="form-control @error('prefix') is-invalid @enderror" name="prefix" value="{{ old('prefix') }}">
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Masa berlaku</label>
                    <div class="col-lg-9 col-xl-9 col-xxl-6">
                        <div class="input-group">
                            <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ old('start_at', $employee->contract->start_at->format('Y') < date('Y') ? date('Y-01-01 00:00:00') : $employee->contract->start_at) }}" required="">
                            <input type="datetime-local" class="form-control @error('end_at') is-invalid @enderror" name="end_at" value="{{ old('end_at', $employee->contract->end_at ? ($employee->contract->end_at->format('Y') > date('Y') ? date('Y-12-31 23:59:59') : $employee->contract->end_at) : date('Y-12-31 23:59:59')) }}" required="">
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
                                        <tbody class="items-tbody" data-slip-az="{{ $loop->parent->iteration }}" data-slip-name="{{ $slip->name }}" data-ctg-az="{{ $loop->iteration }}" data-ctg-name="{{ $category->name }}">
                                            <tr class="items-form-index">
                                                <td class="td-hide-on-add">
                                                    @if ($loop->first)
                                                        {{ $slip->name }}
                                                    @endif
                                                </td>
                                                <td class="td-hide-on-add">{{ $category->name }}</td>
                                                <td style="min-width: 120px;">
                                                    <input type="hidden" data-name="slip_az" value="{{ $loop->parent->iteration }}">
                                                    <input type="hidden" data-name="slip_name" value="{{ $slip->name }}">
                                                    <input type="hidden" data-name="ctg_az" value="{{ $loop->iteration }}">
                                                    <input type="hidden" data-name="ctg_name" value="{{ $category->name }}">
                                                    <input type="hidden" data-name="name">
                                                    <select class="form-select" data-name="component_id" onchange="renderUnitComponent(event.currentTarget)">
                                                        <option value="" data-disabled="1">-- Pilih komponen --</option>
                                                        @foreach ($category->components as $component)
                                                            <option value="{{ $component->id }}" data-unit="{{ $component->unit?->label() }}" data-default="{{ $component->meta->default ?? null }}" data-description="{{ $component->meta->description ?? null }}" data-disabled="{{ $component->unit?->disabledState() }}">{{ $component->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td style="min-width: 120px;">
                                                    <div class="input-group">
                                                        <input type="number" min="0" data-name="amount" class="form-control" disabled required>
                                                        <div class="input-group-text d-none"></div>
                                                    </div>
                                                </td>
                                                <td style="min-width: 120px;">
                                                    <input type="text" data-name="description" class="form-control" disabled>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-light text-danger rounded-circle items-btn-add px-2 py-1" onclick="addRow(event.currentTarget)"><i class="mdi mdi-plus"></i></button>
                                                    <button type="button" class="btn btn-secondary rounded-circle items-btn-remove d-none px-2 py-1" onclick="removeRow(event.currentTarget)"><i class="mdi mdi-minus"></i></button>
                                                </td>
                                            </tr>
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
                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('hrms::payroll.templates.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
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
        const setNameValueFromSelectedTemplate = (el) => {
            document.querySelector('[name="name"]').value = `${el.dataset.addonText} ${@json(date('Y'))}`.replace(/ +(?= )/g, '');
            document.querySelector('[name="prefix"]').value = el.dataset.addonText;
            renderComponentsFromSelectedTemplate();
        };

        const employeeSalaryTemplates = @json($employee->salaryTemplates);
        const defaultComponent = @json($defaultComponent);
        const settings = @json($settings);

        const renderComponentsFromSelectedTemplate = () => {
            const template = document.querySelector('[name="template_id"]:checked').dataset.components;
            Array.from(document.querySelectorAll('[data-name="component_id"]')).forEach(el => (el.value = ''));
            Array.from(document.querySelectorAll('tbody.items-tbody')).forEach((tbody) => {
                Array.from(tbody.querySelectorAll('.items-form-index')).forEach((el, index) => !el.value && index > 0 ? removeRow(el) : false)
            });
            if (JSON.parse(template).length) {
                JSON.parse(template).forEach((component, i) => {
                    let tbody = document.querySelector(`[data-slip-az="${component.slip_az}"][data-ctg-az="${component.ctg_az}"]`);
                    if (tbody) {
                        let select, amount, row = tbody.querySelector('.items-form-index:last-child');
                        if (select = row.querySelector(`[data-name="component_id"]`)) {
                            select.value = component.component_id;
                            renderUnitComponent(select);
                            if (select.value == defaultComponent.id) {
                                select.parentNode.nextElementSibling.querySelector('[data-name="amount"]').addEventListener('keyup', function(e) {
                                    newComponentRender(e);
                                })
                            }
                        }
                        if (amount = row.querySelector('[data-name="amount"]')) {
                            amount.value = component.amount;
                        }
                        if (component.reference) {
                            let reference = component.reference.split('#')
                            let _template;
                            let _item;
                            if (_template = employeeSalaryTemplates.filter(t => t.cmp_template_id == reference[0])) {
                                if (_template.length && (_item = _template[0].items.filter(i => i.component_id == reference[1]))) {
                                    if (_item[0]) {
                                        amount.value = _item[0].amount
                                    }
                                }
                            }
                        }
                        addRow(select);
                    }
                })
            }
            Array.from(document.querySelectorAll('tbody.items-tbody')).forEach((tbody) => {
                Array.from(tbody.querySelectorAll('.items-form-index')).forEach((el, index) => {
                    if (!el.querySelector('[data-name="component_id"]').value) {
                        renderUnitComponent(el.querySelector('[data-name="component_id"]'));
                        if (index > 0) {
                            removeRow(el)
                        }
                    }
                })
            })
        }

        const addRow = (element) => {
            let tr = element.closest('.items-form-index');
            let el = tr.cloneNode(true)
            el.classList.remove('has-add-button');
            Array.from(el.querySelectorAll('.td-hide-on-add')).map((el) => el.innerHTML = '')
            tr.parentNode.insertAdjacentHTML('beforeend', `<tr class="items-form-index">${el.innerHTML}</tr>`);
            Array.from(tr.parentNode.children).forEach((el, i) => {
                if (i > 0 && !el.classList.contains('has-add-button')) {
                    el.querySelector('.items-btn-remove').classList.remove('d-none');
                    el.querySelector('.items-btn-add').classList.add('d-none');
                }
                if (i == tr.parentNode.children.length - 1) {
                    el.querySelector('[data-name="component_id"]').selectedIndex = 0;
                    renderUnitComponent(el.querySelector('[data-name="component_id"]'));
                }
            });
            renderNameAttribute();
        }

        const removeRow = (el) => {
            el.closest('tr').remove()
            renderNameAttribute();
        }

        const renderNameAttribute = () => {
            Array.from(document.querySelectorAll('.items-form-index')).map((tr, index) => {
                Array.from(tr.querySelectorAll('[data-name]')).map(input => {
                    if (input.dataset.name) {
                        input.name = `items[${index}][${input.dataset.name}]`;
                    }
                })
            })
        }

        const renderUnitComponent = (el) => {
            let checked = el.querySelector(':checked');
            let row = el.closest('.items-form-index');

            if (checked) {
                if (checked.dataset.unit) {
                    let unit = row.querySelector('.input-group-text');
                    unit.classList.remove('d-none');
                    unit.innerHTML = checked.dataset.unit;
                } else {
                    let p = getPrimarySalary();
                    for (let index = 0; index < settings.length; index++) {
                        if (checked && checked.value == settings[index].meta.component) {
                            let l = eval(settings[index].meta.calculation);
                            row.querySelector('[data-name="amount"]').value = Math.round(parseFloat(l));
                        }
                    }
                }
            } else {
                row.querySelector('.input-group-text').classList.add('d-none');
            }

            let description = (checked && checked.dataset.description);
            row.querySelector('[data-name="description"]').value = description;

            let name = (checked && checked.value) ? checked.text : '';
            row.querySelector('[data-name="name"]').value = name;

            row.querySelector('[data-name="amount"]').value = '';

            if (disabled = (checked && checked.dataset.disabled)) {
                row.querySelector('[data-name="description"]').value = '';
            }

            row.querySelector('[data-name="amount"]').toggleAttribute('disabled', disabled);
            row.querySelector('[data-name="description"]').toggleAttribute('disabled', disabled);

        }

        // Function get primary salary
        function getPrimarySalary() {
            let amt = '';
            let primarySlip = @json($primarySlip);
            let parent = document.querySelector(`[data-ctg-name="${primarySlip.name}"]`);
            Array.from(parent.querySelectorAll('.items-form-index')).map((tr, index) => {
                Array.from(tr.querySelectorAll('[data-name="component_id"]')).map((select, i) => {
                    Array.from(select.selectedOptions).filter(el => el.text == 'Gaji Pokok').map((opt, k) => {
                        amt = parseFloat(opt.parentNode.closest('.items-form-index').querySelector('[data-name="amount"]').value);
                    })
                })
            });
            return amt;
        }

        const newComponentRender = (e) => {
            let cmptid = @json($cmptid);
            let secondarySlip = @json($secondarySlip);
            let secondaryParent = document.querySelector(`[data-ctg-name="${secondarySlip.name}"]`);
            Array.from(secondaryParent.querySelectorAll('.items-form-index')).map((tr, index) => {
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
