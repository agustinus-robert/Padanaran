@extends('hrms::layouts.default')

@section('title', 'Rekapitulasi THR | ')
@section('navtitle', 'Rekapitulasi THR')

@php
    $profile = array_filter([
        'Nama karyawan' => $employee->user->name,
        'Agama' => \Modules\Account\Enums\ReligionEnum::tryFrom($employee->user->getMeta('profile_religion') ?? 1)->label(),
        'NIP' => $employee->kd ?: '-',
        'Tanggal bergabung' => $employee->joined_at->isoFormat('LL') ?: '-',
        'Jabatan' => $employee->position->position->name ?? '-',
        'Departemen' => $employee->position->position->department->name ?? '-',
        'Atasan' => $employee->position->position->parents->last()?->employees->first()->user->name ?? null,
    ]);

    $tmpl_count = $salaryTemplates->count();
@endphp

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('hrms::summary.feastdays.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Hitung rekapitulasi THR</h2>
            <div class="text-secondary">Menampilkan data penghitungan THR.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-box-multiple-outline"></i> Detail karyawan
                </div>
                <div class="list-group list-group-flush border-top">
                    @foreach ($profile as $label => $value)
                        <div class="list-group-item">
                            <div class="row d-flex align-items-center">
                                <div class="col-sm-6 col-xl-12">
                                    <div class="small text-muted">{{ $label }}</div>
                                </div>
                                <div class="col-sm-6 col-xl-12 fw-bold"> {{ $value }} </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-xxl-8 order-xxl-first">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Data rekapitulasi</div>
                </div>
                <form class="form-confirm form-block" action="{{ route('hrms::summary.feastdays.store', ['employee' => $employee->id, 'next' => request('next', route('hrms::summary.feastdays.index'))]) }}" method="post"> @csrf
                    <div class="card-body">
                        <div class="input-group">
                            <div class="input-group-text">Periode cut off</div>
                            <input class="form-control" type="text" value="{{ $cutoff_at->format('Y-m-d') }}" readonly disabled>
                        </div>
                        <input class="d-none" name="start_at" type="date" value="{{ $cutoff_at->format('Y-m-d') }}">
                        <input class="d-none" name="end_at" type="date" value="{{ $cutoff_at->format('Y-m-d') }}">
                    </div>
                    <div class="table-responsive">
                        <table class="table-hover table-bordered table">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Template</th>
                                    <th>Komponen</th>
                                    <th>Jumlah</th>
                                    <th>
                                        <button type="button" class="btn btn-secondary rounded-circle btn-delete-row px-2 py-1" onclick="addRow(event.target)"><i class="mdi mdi-plus"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="calc-tbody">
                                @foreach ($periods as $k => $period)
                                    @php($month = $period->format('Y-m'))
                                    @php($tmps = $salaryTemplates->where('start_at', '<', $period->endOfMonth())->where('end_at', '>=', $period->endOfMonth()->subDays(1)))
                                    @foreach ($tmps as $_k => $tmp)
                                        <tr class="period-tr">
                                            <td>
                                                <input class="form-control" data-name="el.m" type="month" data-id="{{ $tmp->id ?? '' }}" data-item="{{ $tmp ? $tmp->items->pluck('id') : [0] }}" onkeyup="renderAmountSubTotal()" value="{{ $month }}">
                                            </td>
                                            <td>
                                                <select class="form-select" data-name="el.template" id="" onchange="renderTemplateItems(event.target)">
                                                    @foreach ($salaryTemplates as $template)
                                                        <option value="{{ $template->id }}" data-components="{{ $template->components }}" @selected(Str::contains($template->name, date('Y', strtotime($month))))>{{ $template->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="components-td pb-1">
                                                <div class="components-template">
                                                    <div class="components d-flex align-items-center mb-1">
                                                        <select class="form-select me-2" data-name="el.components..id" onchange="renderAmountValue(event.target)"></select>
                                                        <input class="form-control me-2" data-name="el.components..amount" type="number" onkeyup="renderAmountSubTotal()">
                                                        <div class="text-nowrap">
                                                            <button type="button" id="add-{{ $k }}" class="btn btn-light rounded-circle text-danger btn-add-components px-2 py-1" onclick="addComponents(event.target)"><i class="mdi mdi-plus-circle-outline"></i></button>
                                                            <button type="button" id="del-{{ $k }}" class="btn btn-secondary rounded-circle btn-delete-components px-2 py-1" onclick="deleteComponents(event.target)"><i class="mdi mdi-minus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input class="form-control group-{{ $tmp->id }} me-2" data-name="el.subtotal_amount" type="number" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-secondary rounded-circle btn-delete-row px-2 py-1" onclick="deleteRow(event.target)"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr>
                                    <td colspan="3" class="align-middle">Angka pembagi</td>
                                    <td>
                                        <input class="form-control divider-amount" name="result[division]" type="number" value="12" onkeyup="renderAmountTotal()">
                                    </td>
                                    <td></td>
                                </tr>
                                @foreach ($salaryTemplates as $_k => $_temp)
                                    <tr>
                                        <td colspan="3" class="align-middle">
                                            <div>Total slip template {{ $_temp->name }}</div>
                                            <div class="small text-muted"><cite>Terbilang: <span class="subtotal-inwords-{{ $_temp->id }}">nol</span> rupiah</cite></div>
                                        </td>
                                        <td>
                                            <input class="form-control calc-subtotal subtotal-{{ $_temp->id }}" name="result[subtotal][{{ $_temp->id }}]" type="number" readonly>
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="align-middle">
                                        <div>Total akhir</div>
                                        <div class="small text-muted"><cite>Terbilang: <span class="total-inwords">nol</span> rupiah</cite></div>
                                    </td>
                                    <td>
                                        <input class="form-control" name="result[total]" type="number">
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center gy-3">
                            <div class="col-md-auto flex-grow-1">
                                <div class="form-check">
                                    <input class="form-check-input" id="agreement" type="checkbox" required>
                                    <label class="form-check-label" for="agreement">Dengan ini saya selaku Human Resource (HR) menyatakan data di atas adalah valid</label>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <button type="submit" class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const benefit = @json(\Modules\HRMS\Enums\DataRecapitulationTypeEnum::THR);
        const templates = @json($salaryTemplates->mapWithKeys(fn($template) => [$template->id => $template->items]));
        const tc = {!! $tmpl_count !!}

        const renderByData = (currentEl = false) => {
            [...document.querySelectorAll('[data-name="el.m"]')].forEach((el, idx) => {
                let selTemp = el.parentNode.closest('tr').querySelector('[data-name="el.template"]');
                selTemp.value = el.dataset.id;
                renderTemplateItems(selTemp);
                let arr = JSON.parse(el.dataset.item);
                if (JSON.parse(el.dataset.item).length) {
                    for (let index = 0; index < arr.length - 1; index++) {
                        if (arr.length > 1) {
                            el.parentNode.closest('tr').querySelector('.btn-light').click();
                        }
                    }
                }
                [...arr].forEach((x, y) => {
                    let cmptx = document.querySelector(`[name="result[el][${idx}][components][${y}][id]"]`);
                    if (cmptx) {
                        cmptx.value = x;
                    }
                });
            });
        };

        const renderTemplateItems = (currentEl = false) => {
            [...document.querySelectorAll('[data-name="el.template"]')].forEach((el) => {
                if (el === currentEl || currentEl === true) {
                    // Create full select option
                    let select = el.closest('tr').querySelector('[data-name="el.components..id"]');
                    select.innerHTML = ''
                    templates[el.value].forEach(item => {
                        select.insertAdjacentHTML(
                            'beforeEnd',
                            `<option value="${item.id}" data-amount="${item.amount}">${item.name}</option>`
                        )
                    });
                    // Refresh selects
                    el.closest('tr').querySelector('.components-td').innerHTML = `<div class="components-template">${el.closest('tr').querySelector('.components-template').innerHTML}</div>`

                    // Set default checked for select option
                    if (templates[el.value].length) {
                        let default_checked = templates[el.value].filter((item) => (item.component.meta && item.component.meta.as_benefits) ? item.component.meta.as_benefits.indexOf(benefit) >= 0 : false)
                        default_checked.forEach((item, i) => {
                            if (i > 0) {
                                el.closest('tr').querySelector('.components-td').insertAdjacentHTML('beforeEnd', select.closest('.components-template').innerHTML);
                            }
                            el.closest('tr').querySelectorAll('[data-name="el.components..id"]')[i].value = item.id
                        })
                    }

                    [...el.closest('tr').querySelectorAll('[data-name="el.components..id"]')].forEach(component => {
                        renderAmountValue(component)
                    });
                }

                // Set add/delete component buttons
                renderAllComponentButtons();

                renderInputName();
            });
        }

        const deleteComponents = (el) => {
            el.closest('.components').remove();
            renderAmountSubTotal();
            renderInputName();
        }

        const deleteRow = (el) => {
            el.closest('tr').remove();
            let value = document.querySelector('.calc-tbody').rows.length - (tc + 2) < 12 ? 12 : document.querySelector('.calc-tbody').rows.length - (tc + 2);
            document.querySelector('.divider-amount').value = value;
            renderAmountSubTotal();
            renderInputName();
        }

        const addRow = (el) => {
            let template = el.closest('thead').nextElementSibling.rows[0].cloneNode(true);
            let tbody = el.closest('thead').nextElementSibling;
            let value = tbody.rows.length - (tc + 1) < 12 ? 12 : tbody.rows.length - (tc + 1);
            tbody.insertBefore(template, tbody.rows[tbody.rows.length - (tc + 2)]);
            document.querySelector('.divider-amount').value = tbody.rows.length - (tc + 2);
            renderAllComponentButtons();
            renderAmountSubTotal();
            renderInputName();
        };

        const addComponents = (el) => {
            el.closest('.components-td').insertAdjacentHTML('beforeEnd', el.closest('.components-template').innerHTML);
            renderAllComponentButtons();
            renderAmountSubTotal();
            renderInputName();
        };

        const renderAllComponentButtons = () => {
            [...document.querySelectorAll('.components-td')].forEach(td => {
                [...td.querySelectorAll('.components')].forEach((component, i) => {
                    component.querySelector('.btn-add-components').classList.toggle('d-none', i > 0)
                    component.querySelector('.btn-delete-components').classList.toggle('d-none', i == 0)
                })
            })
        }

        const renderAmountValue = (el) => {
            if (el.querySelector(':checked') && el.querySelector(':checked').dataset.amount) {
                el.closest('.components').querySelector('[data-name="el.components..amount"]').value = el.querySelector(':checked').dataset.amount;
            }
            renderAmountSubTotal();
        }

        const renderAmountSubTotal = () => {
            [...document.querySelectorAll('[data-name="el.subtotal_amount"]')].forEach(subtotal => {
                let total = 0;
                [...subtotal.closest('tr').querySelectorAll('[data-name="el.components..amount"]')].forEach(el => total += parseFloat(el.value || '0'));
                subtotal.value = total;
            })
            renderSubTotal();
        }

        // Edit here
        const renderSubTotal = () => {
            Object.keys(templates).forEach(key => {
                let sub = Math.round([...document.querySelectorAll('.group-' + key)].filter((e) => parseInt(e.value)).map(el => parseFloat(el.value || 0)).reduce((s, x) => s + x, 0));
                document.querySelector('.subtotal-' + key).value = sub;
                document.querySelector('.subtotal-inwords-' + key).innerHTML = terbilang(sub).toLowerCase();
            });
            renderAmountTotal();
        }

        const renderAmountTotal = () => {
            let total = 0;
            [...document.querySelectorAll('.calc-subtotal')].forEach(el => total += parseFloat(el.value || '0'));
            let division = document.querySelector('[name="result[division]"]').value;
            total = Math.round(total / (division || 0));
            document.querySelector('[name="result[total]"]').value = total;
            document.querySelector('.total-inwords').innerHTML = terbilang(total).toLowerCase();
        }

        const renderInputName = () => {
            [...document.querySelectorAll('.components-td')].forEach(td => {
                [...td.querySelectorAll('.components')].forEach((cmp, i) => {
                    cmp.querySelector('[data-name="el.components..id"]').dataset.index = i;
                    cmp.querySelector('[data-name="el.components..amount"]').dataset.index = i;
                });
            });

            [...document.querySelectorAll('.period-tr')].forEach((tr, i) => {
                [...tr.querySelectorAll('[data-name]')].forEach(el => {
                    let result = el.dataset.name.replace('..', `.${el.dataset.index}.`).split('.');
                    el.name = `result[${result.shift()}][${i}][${result.join('][')}]`;
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderTemplateItems(true);
            renderByData(true);
            [...document.querySelectorAll('[data-name="el.components..id"]')].forEach(component => {
                renderAmountValue(component)
            });
        });
    </script>
@endpush
