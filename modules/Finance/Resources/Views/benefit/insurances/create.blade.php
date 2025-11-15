@extends('finance::layouts.default')

@section('title', 'Tambah asuransi | ')
@section('navtitle', 'Tambah asuransi')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::benefit.insurances.registrations.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Tambah asuransi</h2>
            <div class="text-secondary">Silakan isi formulir di bawah untuk menambahkan data asuransi karyawan</div>
        </div>
    </div>
    <div class="card mb-4 border-0">
        <div class="card-body">
            <form id="form-add-insurances" class="form-block" action="{{ route('finance::benefit.insurances.registrations.store', ['employee' => $employee->id, 'next' => request('next')]) }}" method="POST"> @csrf
                <div class="row required mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Nama karyawan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-4">
                        <input type="text" class="form-control" value="{{ $employee->user->name }}" readonly>
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Pilih template</label>
                    <div class="col-lg-3">
                        <select class="form-select" onchange="renderTemplate(this)">
                            <option value="">Tanpa template</option>
                            <optgroup label="BPJS Kesehatan"></optgroup>
                            <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Kelas 1">
                                <option data-select='[{"category":1,"meta":{"group":"Kelas 1","membership":"Penerima Upah"}}]'>&nbsp;&nbsp;&nbsp;&nbsp;Kes Kelas 1</option>
                                <option data-select='[{"category":1,"meta":{"group":"Kelas 1","membership":"Penerima Upah"}},{"category":2,"meta":{"services":"Jaminan Hari Tua"}},{"category":2,"meta":{"services":"Jaminan Kecelakaan"}},{"category":2,"meta":{"services":"Jaminan Kematian"}}]'>&nbsp;&nbsp;&nbsp;&nbsp;Kes Kelas 1 + 3TK</option>
                                <option data-select='[{"category":1,"meta":{"group":"Kelas 1","membership":"Penerima Upah"}},{"category":2,"meta":{"services":"Jaminan Hari Tua"}},{"category":2,"meta":{"services":"Jaminan Kecelakaan"}},{"category":2,"meta":{"services":"Jaminan Kematian"}},{"category":2,"meta":{"services":"Jaminan Pensiun"}}]'>&nbsp;&nbsp;&nbsp;&nbsp;Kes Kelas 1 + 3TK + TK Pensiun</option>
                            </optgroup>
                            <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Kelas 2">
                                <option data-select='[{"category":1,"meta":{"group":"Kelas 2","membership":"Penerima Upah"}}]'>&nbsp;&nbsp;&nbsp;&nbsp;Kes Kelas 2</option>
                                <option data-select='[{"category":1,"meta":{"group":"Kelas 2","membership":"Penerima Upah"}},{"category":2,"meta":{"services":"Jaminan Hari Tua"}},{"category":2,"meta":{"services":"Jaminan Kecelakaan"}},{"category":2,"meta":{"services":"Jaminan Kematian"}}]'>&nbsp;&nbsp;&nbsp;&nbsp;Kes Kelas 2 + 3TK</option>
                                <option data-select='[{"category":1,"meta":{"group":"Kelas 2","membership":"Penerima Upah"}},{"category":2,"meta":{"services":"Jaminan Hari Tua"}},{"category":2,"meta":{"services":"Jaminan Kecelakaan"}},{"category":2,"meta":{"services":"Jaminan Kematian"}},{"category":2,"meta":{"services":"Jaminan Pensiun"}}]'>&nbsp;&nbsp;&nbsp;&nbsp;Kes Kelas 2 + 3TK + TK Pensiun</option>
                            </optgroup>
                            <optgroup label="BPJS Ketenagakerjaan">
                                <option data-select='[{"category":2,"meta":{"services":"Jaminan Hari Tua"}},{"category":2,"meta":{"services":"Jaminan Kecelakaan"}},{"category":2,"meta":{"services":"Jaminan Kematian"}}]'>3TK</option>
                                <option data-select='[{"category":2,"meta":{"services":"Jaminan Hari Tua"}},{"category":2,"meta":{"services":"Jaminan Kecelakaan"}},{"category":2,"meta":{"services":"Jaminan Kematian"}},{"category":2,"meta":{"services":"Jaminan Pensiun"}}]'>3TK + Pensiun</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="row required mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label">Kategori asuransi</label>
                    <div class="col-xl-10">
                        <div class="table-responsive rounded border">
                            <table class="table-hover mb-0 table">
                                <thead>
                                    <tr>
                                        <th nowrap class="pt-2">Kategori</th>
                                        <th class="pt-2">Form</th>
                                        <th class="pt-2">Iuran perusahaan</th>
                                        <th class="pt-2">Iuran karyawan</th>
                                        <th width="50"></th>
                                    </tr>
                                </thead>
                                <tbody id="categories-tbody">
                                    <tr id="categories-template">
                                        <td>
                                            <select class="form-select category-select" required onchange="renderFormMeta(this)">
                                                <option value="">-- Pilih kategori --</option>
                                                @foreach ($insurances as $insurance)
                                                    <option value="{{ $insurance->id }}" data-form="{{ $insurance->meta }}">{{ $insurance->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="max-width: 240px; width: 240px;">
                                            <div class="d-grid form-conditions gap-2"></div>
                                            <input type="hidden" class="form-calculate-id form-index" readonly data-name="price_id">
                                        </td>
                                        <td style="max-width: 240px;">
                                            <div class="form-calculate-cmp">
                                                <div class="input-group form-calculate mb-2">
                                                    <input type="number" class="form-control form-calculate-price form-index" min="0" step="0.01" data-name="meta.cmp_price" onkeyup="calculatePrice()">
                                                    <div class="input-group-text">%</div>
                                                    <input type="number" class="form-control form-calculate-factor form-index" data-name="meta.cmp_factor" onkeyup="calculatePrice()">
                                                </div>
                                            </div>
                                            <div>
                                                <input type="number" class="form-control form-calculate-result form-calculate-result form-index" data-name="cmp_price" value="0" readonly>
                                            </div>
                                        </td>
                                        <td style="max-width: 240px;">
                                            <div class="form-calculate-empl">
                                                <div class="input-group form-calculate mb-2">
                                                    <input type="number" class="form-control form-calculate-price form-index" min="0" step="0.01" data-name="meta.empl_price" onkeyup="calculatePrice()">
                                                    <div class="input-group-text">%</div>
                                                    <input type="number" class="form-control form-calculate-factor form-index" data-name="meta.empl_factor" onkeyup="calculatePrice()">
                                                </div>
                                            </div>
                                            <div>
                                                <input type="number" class="form-control form-calculate-result form-index" data-name="empl_price" value="0" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-light btn-delete text-danger d-none" onclick="removeRow(event)"><i class="mdi mdi-trash-can-outline"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="p-2">
                                <button id="categories-add" type="button" class="btn btn-light text-danger"><i class="mdi mdi-plus-circle-outline"></i> Tambah kategori baru</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-9 offset-lg-3 offset-xl-2">
                        <div class="form-check mb-3">
                            <input class="form-check-input" id="agreement" type="checkbox" required>
                            <label class="form-check-label" for="agreement">Dengan ini saya menyatakan data di atas adalah valid</label>
                        </div>
                        <button id="form-add-insurances-submit" class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::benefit.insurances.registrations.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const salaries = @json($employee->salaryTemplate->items->pluck('amount', 'component.kd'));
        const maxSalary = {{ $max_salary }}

        const renderTemplate = (el) => {
            [...document.querySelectorAll('.btn-delete:not(.d-none)')].forEach((el) => el.click())
            document.querySelector('.category-select').value = '';
            document.querySelector('.form-conditions').innerHTML = '';
            [...document.querySelectorAll('#categories-tbody [type="number"]')].forEach((el) => (el.value = el.readOnly ? 0 : null));
            let select = el.querySelector(':checked').dataset.select;
            if (select) {
                let template = JSON.parse(select);
                template.forEach((c, i) => {
                    if (i > 0) addRow();
                    let row = document.querySelector(`#categories-tbody tr:nth-child(${i + 1})`);
                    row.querySelector('.category-select').value = c.category;
                    renderFormMeta(row.querySelector('.category-select'));
                    if (c.meta && Object.values(c.meta)) {
                        Object.keys(c.meta).forEach((k) => {
                            row.querySelector(`[data-name="meta.${k}"]`).value = c.meta[k];
                            renderCalculation(row.querySelector(`[data-name="meta.${k}"]`));
                        })
                    }
                })
            }
            renderNameAttribute();
        };

        const addRow = () => {
            let tr = document.querySelector('#categories-template').innerHTML;
            let tbody = document.querySelector('#categories-tbody');
            if (tbody.children.length <= 6) {
                tbody.insertAdjacentHTML('beforeend', tr);
                Array.from(tbody.children).forEach((el, i) => {
                    if (i > 0)
                        el.querySelector('.btn-delete').classList.remove('d-none');
                    if (i == tbody.children.length - 1)
                        el.querySelector('.form-conditions').innerHTML = '';
                });
            }
            renderNameAttribute();
        }

        const removeRow = (e) => {
            e.target.parentNode.closest('tr').remove()
            renderNameAttribute();
        }

        const createSelect = (name, options, label) => {
            let select = document.createElement('select');
            select.classList.add('form-select', 'form-index');
            select.dataset.name = `meta.${name}`;
            select.dataset.meta = name;
            select.appendChild(new Option(`-- Pilih ${label} --`, ''))
            options.forEach((option) => {
                select.appendChild(new Option(option, option));
            });
            select.addEventListener('change', (e) => renderCalculation(e.target))
            return select;
        }

        const renderCalculation = (el) => {
            const prices = @json($insurances->pluck('prices')->flatten());
            let price = Array.from(prices).filter((price) => {
                return Array.from(el.parentNode.children).filter(el => el.value).every((el) => {
                    if (condition = price.conditions[el.dataset.meta]) {
                        return Array.from(condition).indexOf(el.value) >= 0;
                    }
                });
            });
            if (price.length === 1 && (price = price[0])) {
                el.parentNode.parentNode.querySelector('.form-calculate-id').value = price.id;
                Array.from(el.parentNode.children).map(el => el.classList.remove('is-invalid'));
                ['cmp', 'empl'].forEach(c => {
                    let price_factor = price.price_factor;
                    let condition = price.conditions;
                    Array.from(price.price_factor_additional || []).forEach(key => {
                        if (salaries[key]) price_factor += salaries[key];
                    });
                    let el_wrapper = el.parentNode.parentNode.parentNode.querySelector(`.form-calculate-${c}`);
                    let el_type = el_wrapper.querySelector(`.form-calculate`);
                    el_type.querySelector(`.form-calculate-price`).value = price[`${c}_price_type`] == 1 ? 100 : price[`${c}_price`];
                    el_type.querySelector(`.form-calculate-factor`).value = price[`${c}_price_type`] == 1 ? price[`${c}_price`] : (condition['services'] == 'Jaminan Pensiun' ? maxSalary : price_factor);

                    calculatePrice();
                })
            } else {
                Array.from(el.parentNode.children).map(el => el.classList.add('is-invalid'));
            }
            // Disable save button
            document.getElementById('form-add-insurances-submit').classList.toggle(
                'disabled',
                Array.from(document.getElementById('form-add-insurances').querySelectorAll('input,select')).some(el => el.classList.contains('is-invalid'))
            );
        }

        const calculatePrice = () => {
            Array.from(document.querySelectorAll('.form-calculate-result')).map(el => {
                let price = parseFloat(el.parentNode.previousElementSibling.querySelector(`.form-calculate-price`).value);
                let factor = parseFloat(el.parentNode.previousElementSibling.querySelector(`.form-calculate-factor`).value);
                el.value = Math.round((price / 100 * factor) || 0);
            })
        }

        const renderFormMeta = (el) => {
            let form = el.querySelector(':checked').dataset.form;
            if (form && (form = JSON.parse(form))) {
                if (form.conditions && form.conditions.length) {
                    if (group = el.parentNode.nextElementSibling.querySelector('.form-conditions')) {
                        group.innerHTML = '';
                        Array.from(form.conditions).forEach((option) => {
                            group.appendChild(
                                createSelect(option.key, option.values, option.label)
                            )
                        })
                    }
                }
            }
            renderNameAttribute();
        }

        const renderNameAttribute = () => {
            let tbody = document.querySelector('#categories-tbody');
            Array.from(tbody.children).forEach((tr, index) => {
                Array.from(tr.querySelectorAll('.form-index')).forEach((input) => {
                    input.name = `insurances[${index}][${input.dataset.name.split('.').join('][')}]`;
                });
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('categories-add').addEventListener('click', addRow);
        });
    </script>
@endpush
