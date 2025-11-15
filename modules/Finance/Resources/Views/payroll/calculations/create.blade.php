@extends('finance::layouts.default')

@section('title', 'Penghitungan gaji | ')
@section('navtitle', 'Penghitungan gaji')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('finance::payroll.calculations.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Hitung penggajian {{ $employee->user->name }}</h2>
            <div class="text-secondary">Anda dapat membuat penghitungan gaji dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body">
            <i class="mdi mdi-file-plus-outline"></i> Formulir penghitungan gaji
        </div>
        <div class="card-body border-top border-light">
            <form action="{{ route('finance::payroll.calculations.store', ['next' => request('next', route('finance::payroll.calculations.index'))]) }}" method="POST"> @csrf
                <div class="row align-items-center mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Nama karyawan</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6 fw-bold">{{ $employee->user->name }}</div>
                    <input class="d-none" type="number" name="employee" value="{{ $employee->id }}">
                </div>
                <div class="row align-items-center mb-2">
                    <label class="col-lg-3 col-xl-2 col-form-label">Periode</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6 fw-bold">
                        <div class="align-items-center d-flex">
                            <div>{{ $start_at->isoFormat('LL') }}</div>
                            <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                            <div>{{ $end_at->isoFormat('LL') }}</div>
                        </div>
                        <input class="d-none" type="date" name="start_at" value="{{ $start_at->format('Y-m-d') }}">
                        <input class="d-none" type="date" name="end_at" value="{{ $end_at->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 col-xl-2 col-form-label required">Template gaji</label>
                    <div class="col-lg-8 col-xl-7 col-xxl-6">
                        <div class="card @error('template_id') border-danger mb-1 @enderror mb-0">
                            <div class="overflow-auto rounded" style="max-height: 300px;">
                                @forelse ($employee->salaryTemplates as $template)
                                    <label class="card-body border-secondary d-flex align-items-center @if (!$loop->last) border-bottom @endif py-2">
                                        <input class="form-check-input me-3" type="radio" name="template_id" value="{{ $template->id }}" data-route="{{ route('finance::payroll.calculations.create', [...request()->only('employee', 'start_at', 'end_at', 'next'), 'template' => $template->id]) }}" @checked(old('template_id', request('template')) == $template->id) onchange="reloadWithTemplate(event.currentTarget)" required>
                                        <div>
                                            <div class="fw-bold mb-0">{{ $template->name }}</div>
                                            <div class="small text-muted">Dibuat {{ $template->created_at->diffForHumans() }} dengan {{ count($template->components) }} komponen</div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="text-muted p-2">Karyawan ini belum memiliki template, silakan buat <a href="{{ route('finance::payroll.templates.create', ['employee' => $employee->id, 'next' => url()->full()]) }}">template</a> terlebih dahulu di sini</div>
                                @endforelse
                            </div>
                        </div>
                        @error('template_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                @if ($selectedTemplate = $employee->salaryTemplates->firstWhere('id', request('template')))
                    <div class="row mb-3">
                        <label class="col-lg-3 col-xl-2 col-form-label required">Nama slip gaji</label>
                        <div class="col-lg-8 col-xl-7 col-xxl-6">
                            <input class="form-control" type="text" name="name" value="{{ $selectedTemplate->prefix == 'Bonus' ? $selectedTemplate->name : $selectedTemplate->prefix . ' ' . $end_at->formatLocalized('%B %Y') }}">
                        </div>
                    </div>
                @endif
                @if ($employee->salaryTemplates->count())
                    <div class="row mb-3">
                        <label class="col-lg-3 col-xl-2 col-form-label required">Komponen</label>
                        <div class="col-lg-9 col-xl-10 col-xxl-10">
                            <div class="card @error('components') border-danger mb-1 @enderror mb-0">
                                @if (request('template'))
                                    @foreach ($selectedTemplate->items->sortBy(['slip_az', 'ctg_az', 'az'])->groupBy(['slip_name', 'ctg_name']) as $slip => $categories)
                                        <div class="card-header border-bottom-0 text-muted small text-uppercase" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($slip) }}" style="cursor: pointer;">{{ $slip }} <i class="mdi mdi-chevron-down float-end"></i></div>
                                        <div class="list-group list-group-flush {{ $loop->first ? 'show' : '' }} collapse" id="collapse-{{ Str::slug($slip) }}">
                                            <input class="d-none" name="components[{{ $loop->index }}][az]" value="{{ $loop->iteration }}">
                                            <input class="d-none" name="components[{{ $loop->index }}][slip]" value="{{ $slip }}">
                                            <table class="calc-table table align-middle">
                                                @foreach ($categories as $category => $items)
                                                    <thead class="table-active">
                                                        <tr>
                                                            <th class="align-middle" colspan="3">{{ $loop->iteration . '. ' . $category }}</th>
                                                            <th>
                                                                <input class="d-none" name="components[{{ $loop->parent->index }}][ctgs][{{ $loop->index }}][az]" value="{{ $loop->iteration }}">
                                                                <input class="d-none" name="components[{{ $loop->parent->index }}][ctgs][{{ $loop->index }}][ctg]" value="{{ $category }}">
                                                                <button type="button" class="btn btn-light text-danger rounded-circle items-btn-add px-2 py-1" onclick="addRow(event.currentTarget)"><i class="mdi mdi-plus"></i></button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="calc-tbody">
                                                        @foreach ($items as $item)
                                                            <tr class="calc-row @if ($loop->first) calc-row-template @endif">
                                                                <td style="max-width: 260px">
                                                                    <div class="input-group">
                                                                        <label class="input-group-text">
                                                                            <input class="form-check-input mt-0" type="checkbox" data-name="enable" value="1" checked>
                                                                        </label>
                                                                        <select class="form-select" data-name="component_id" onchange="renderAmountValue()" required>
                                                                            <option value="" data-disabled="1" data-default-amount="0" data-multiplier="1" data-operate-symbol data-operate-icon="{{ \Modules\Core\Enums\SalaryOperateEnum::NULL->icon() }}" data-operate-color="{{ \Modules\Core\Enums\SalaryOperateEnum::NULL->color() }}" data-unit="">-- Pilih komponen --</option>
                                                                            @foreach ($components->where('category.name', $category) as $component)
                                                                                <option value="{{ $component->id }}" @selected($component->id == $item->component_id) data-disabled="{{ empty($component->meta->editable) }}" data-default-amount="{{ $component->default_amount }}" data-multiplier="{{ $component->multiplier }}" data-operate-icon="{{ $component->operate->icon() }}" data-operate-symbol="{{ $component->operate->symbol() }}" data-operate-color="{{ $component->operate->color() }}"
                                                                                    data-unit="{{ $component->unit->label() }}">
                                                                                    {{ $component->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" data-name="name" value="{{ $item->name }}" required>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control d-none" type="text" data-name="u" value="{{ $item->component->unit->value }}" readonly>
                                                                    <div class="input-group flex-nowrap">
                                                                        <div class="input-group-text bg-soft-success text-success">
                                                                            <i class="mdi mdi-plus"></i>
                                                                        </div>
                                                                        @if ($item->component->unit->prefix())
                                                                            <div class="input-group-text">{{ $item->component->unit->prefix() }}</div>
                                                                        @endif
                                                                        <input type="number" class="form-control text-end" data-name="amount" value="0" min="0" onkeyup="renderRealAmountValue(event.currentTarget)" required>
                                                                        @if ($item->component->unit->suffix())
                                                                            <div class="input-group-text">{{ $item->component->unit->suffix() }}</div>
                                                                        @endif
                                                                    </div>
                                                                    <input type="number" class="d-none" data-name="n" readonly value="1">
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-secondary rounded-circle items-btn-remove px-2 py-1" onclick="removeRow(event.currentTarget)"><i class="mdi mdi-minus"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr @class([
                                                            'calc-row-subtotal',
                                                            'd-none' => $item->component->unit->disabledState(),
                                                        ])>
                                                            <td colspan="2">
                                                                <div>Subtotal</div>
                                                                <div class="small text-muted"><cite>Terbilang: <span class="calc-row-subtotal-inwords">nol</span> rupiah</cite></div>
                                                            </td>
                                                            <td><input type="number" class="form-control calc-row-subtotal-input text-end" value="0" readonly></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                @endforeach
                                                <tr>
                                                    <td colspan="2">
                                                        <div>Total {{ $slip }} </div>
                                                        <div class="small text-muted"><cite>Terbilang: <span class="calc-slip-total-inwords">nol</span> rupiah</cite></div>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control calc-slip-total-input text-end" disabled readonly>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="card-body text-muted">Silakan pilih dahulu template</div>
                                @endif
                            </div>
                            @error('components')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-xl-2 col-form-label">Take Home Pay (THP)</label>
                        <div class="col-lg-8 col-xl-7 col-xxl-6">
                            <div class="card card-body mb-0">
                                <input class="d-none" type="number" name="amount" value="0">
                                <h4>Rp <span class="thp-input">0</span></h4>
                                <div class="small text-muted"><cite>Terbilang: <span class="thp-inwords">nol</span> rupiah</cite></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-lg-3 col-xl-2 col-form-label">Catatan</label>
                        <div class="col-lg-8 col-xl-7 col-xxl-6">
                            <textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10">
                            <hr>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="agreement" type="checkbox" required>
                                <label class="form-check-label" for="agreement">Dengan ini saya selaku Human Resource (HR) menyatakan data di atas adalah valid</label>
                            </div>
                            <div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                {{-- <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::service.attendance.schedules.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a> --}}
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const reloadWithTemplate = (el) => {
            window.location.href = el.dataset.route;
        }

        const addRow = (el) => {
            let template = el.closest('thead').nextElementSibling.querySelector('.calc-row-template').cloneNode(true)
            template.querySelector('[data-name="component_id"]').value = "";
            el.closest('thead').nextElementSibling.insertBefore(template, el.closest('thead').nextElementSibling.querySelector('tr:last-child'));
            renderAmountValue();
        }

        const removeRow = (el) => {
            el.closest('tr').remove();
            renderInputName();
            sumSubTotal();
        }

        const renderAmountValue = () => {
            [...document.querySelectorAll('[data-name="component_id"] option:checked')].forEach((el, i) => {
                console.log(el)
                let amount = el.closest('.calc-row').querySelector('[data-name="amount"]');
                el.closest('.calc-row').querySelector('[data-name="name"]').value = el.value ? el.text : '';
                amount.closest('.input-group').querySelector('.input-group-text').className = `input-group-text bg-soft-${el.dataset.operateColor || 'secondary'} text-${el.dataset.operateColor === 'danger' ? 'white' : (el.dataset.operateColor || 'secondary')}`;
                amount.closest('.input-group').querySelector('.input-group-text i').className = el.dataset.operateIcon;
                let defaultAmount = (el.dataset.defaultAmount || 0);
                switch (el.dataset.operateSymbol) {
                    case '+':
                        amount.dataset.realAmount = defaultAmount;
                        break;
                    case '-':
                        amount.dataset.realAmount = defaultAmount * -1;
                        break;
                    default:
                        amount.dataset.realAmount = 0;
                        break;
                }
                amount.value = ['jam'].includes(el.dataset.unit.toLowerCase()) ? parseFloat(defaultAmount * (parseFloat(el.dataset.multiplier) || 1)).toFixed(2) : Math.round(defaultAmount * (parseFloat(el.dataset.multiplier) || 1));
                amount.dataset.operateSymbol = el.dataset.operateSymbol;
                amount.readOnly = el.dataset.disabled;
                renderRealAmountValue(el.closest('tr').querySelector('[data-name="amount"]'));
            })
            renderInputName();
            sumSubTotal();
        }

        const renderRealAmountValue = (el) => {
            switch (el.dataset.operateSymbol) {
                case '+':
                    el.dataset.realAmount = (Math.abs(el.value)) * (el.dataset.multiplier ? el.value : 1);
                    break;
                case '-':
                    el.dataset.realAmount = (Math.abs(el.value) * -1) * (el.dataset.multiplier ? el.value : 1);
                    break;
                default:
                    el.dataset.realAmount = 0;
                    break;
            }
            sumSubTotal();
        }

        const sumSubTotal = () => {
            [...document.querySelectorAll('.calc-tbody')].forEach(tbody => {
                tbody.querySelectorAll('tr .items-btn-remove').forEach((tr, i) => tr.classList.toggle('disabled', i == 0))
                let subtotal = Math.abs([...tbody.querySelectorAll('[data-name="amount"]')].map(el => parseFloat(el.dataset.realAmount || 0)).reduce((result, x) => result + x));
                tbody.querySelector('.calc-row-subtotal-input').value = subtotal;
          //      tbody.querySelector('.calc-row-subtotal-inwords').innerHTML = terbilang(subtotal).toLowerCase();
            });
            sumSlipTotal();
        }

        const sumSlipTotal = () => {
            [...document.querySelectorAll('.calc-table')].forEach(table => {
                let sliptotal = [...table.querySelectorAll('[data-name="amount"]')].map(el => parseFloat(el.dataset.realAmount || 0)).reduce((result, x) => result + x);
                table.querySelector('.calc-slip-total-input').classList.toggle('text-danger', sliptotal < 0);
                table.querySelector('.calc-slip-total-input').value = sliptotal;
             //   table.querySelector('.calc-slip-total-inwords').innerHTML = terbilang(Math.abs(sliptotal)).toLowerCase();
            })
            sumTHP();
        }

        const sumTHP = () => {
            let thptotal = Math.round([...document.querySelectorAll('.calc-slip-total-input')].map(el => parseFloat(el.value || 0)).reduce((thp, x) => thp + x));
            document.querySelector('[name="amount"]').value = thptotal;
            document.querySelector('.thp-input').innerHTML = thptotal.toLocaleString('id-ID');
           // document.querySelector('.thp-inwords').innerHTML = terbilang(Math.abs(thptotal)).toLowerCase();
        }

        const renderInputName = () => {
            [...document.querySelectorAll('.calc-table')].forEach((table, h) => {
                [...table.querySelectorAll('.calc-tbody')].forEach((tbody, i) => {
                    [...tbody.querySelectorAll('.calc-row')].forEach((tr, j) => {
                        [...tr.querySelectorAll('[data-name]')].forEach(el => {
                            let result = el.dataset.name.split('.');
                            el.name = `components[${h}][ctgs][${i}][i][${j}][${result.join('][')}]`;
                            if (el.dataset.name == 'amount') {
                                tr.querySelector('[data-name="n"]').value = (tr.querySelector('[data-name="component_id"] option:checked').dataset.operateSymbol == '-' ? -1 : 1);
                            }
                        });
                    });
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderAmountValue()
        })
    </script>
@endpush
