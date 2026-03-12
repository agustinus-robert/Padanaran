@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Manage Schedule')
@section('navtitle', env('APP_NAME') . ' Manage Schedule')

@section('content')
@php
    $shiftLabels = [
        'morning' => ['label' => 'Pagi', 'icon' => 'bx-sun', 'color' => 'warning'],
        'afternoon' => ['label' => 'Siang', 'icon' => 'bx-cloud-light-rain', 'color' => 'info'],
        'evening' => ['label' => 'Sore', 'icon' => 'bx-moon', 'color' => 'dark'],
    ];

    $currentKey = strtolower($supplier_schedule);
    $config = $shiftLabels[$currentKey] ?? ['label' => ucfirst($currentKey), 'icon' => 'bx-time', 'color' => 'primary'];
@endphp

<style>
    /* Menyamakan tinggi Select2 dan Tombol Aksi */
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 1.3rem + 2px) !important;
        display: flex;
        align-items: center;
    }
    .table td {
        vertical-align: middle !important;
    }
    /* Ukuran khusus untuk ikon header agar lebih besar */
    .header-icon-large {
        font-size: 2.5rem !important;
    }
</style>

<div class="card card-custom gutter-b shadow-sm border-0">
    <div class="card-header border-0 py-5 bg-light-{{ $config['color'] }}">
        <div class="card-title">
            <div class="symbol symbol-65 symbol-light-{{ $config['color'] }} mr-5">
                <span class="symbol-label">
                    <i class="bx {{ $config['icon'] }} text-{{ $config['color'] }} header-icon-large"></i>
                </span>
            </div>
            <h3 class="card-label font-weight-bolder text-dark">
                Shift {{ $config['label'] }}
                <span class="text-muted font-size-sm font-weight-bold d-block">Kelola daftar supplier dan produk untuk shift ini</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('poz::schedule.supplier_schedule.index', ['outlet' => request('outlet')]) }}" class="btn btn-sm btn-light-primary font-weight-bold mr-2">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
            <button type="button" class="btn btn-sm btn-{{ $config['color'] }} font-weight-bold" onclick="addRow()">
                <i class="bx bx-plus"></i> Tambah Baris
            </button>
        </div>
    </div>

    <div class="card-body pt-2">
        <form method="POST" action="{{ route('poz::schedule.supplier_schedule.store', ['outlet' => request()->query('outlet', auth()->user()->current_outlet_id)]) }}">
            @csrf
            <input type="hidden" name="time" value="{{ $currentKey }}">

            <div class="table-responsive">
                <table class="table table-head-custom" id="schedule-table">
                    <thead>
                        <tr class="text-left text-uppercase">
                            <th style="min-width: 250px" class="pl-0">Supplier</th>
                            <th style="min-width: 250px">Produk</th>
                            <th class="text-right pr-0" style="width: 50px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-body">
                        @forelse($prodSupp as $item)
                            <tr>
                                <td class="pl-0 py-3">
                                    <select name="schedules[{{ $loop->index }}][supplier_id]" class="form-control select2 supplier-select" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $supplier->id == $item->supplier_id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3">
                                    <select name="schedules[{{ $loop->index }}][product_id]" class="form-control select2 product-select" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-right pr-0 py-3">
                                    <button type="button" class="btn btn-icon btn-light-danger btn-sm" onclick="removeRow(this)">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="empty-state">
                                <td colspan="3" class="text-center py-10 text-muted">
                                    <i class="bx bx-info-circle font-size-h1 d-block mb-2"></i>
                                    Belum ada jadwal. Klik "Tambah Baris" untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="separator separator-dashed my-8"></div>

            <div class="d-flex justify-content-end">
                <button type="submit" id="submitBtn" class="btn btn-primary font-weight-bolder px-10 py-3" disabled>
                    <i class="bx bx-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let rowIndex = {{ count($prodSupp) }};

    function initSelect2(element = null) {
        const target = element ? $(element).find('.select2') : $('.select2');
        target.select2({
            width: '100%',
            placeholder: "-- Pilih --"
        });
    }

    function addRow() {
        if (!lastRowIsValid()) {
            Swal.fire({
                icon: 'warning',
                title: 'Opps!',
                text: 'Harap lengkapi baris sebelumnya terlebih dahulu.',
                confirmButtonText: "Ok",
                customClass: { confirmButton: "btn btn-primary" }
            });
            return;
        }

        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.remove();

        const tbody = document.getElementById('schedule-body');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td class="pl-0 py-3">
                <select name="schedules[${rowIndex}][supplier_id]" class="form-control select2 supplier-select" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="py-3">
                <select name="schedules[${rowIndex}][product_id]" class="form-control select2 product-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="text-right pr-0 py-3">
                <button type="button" class="btn btn-icon btn-light-danger btn-sm" onclick="removeRow(this)">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);
        
        $(row).find('.select2').select2({ width: '100%' });
        
        rowIndex++;
        attachValidationEvents();
        validateSubmit();
    }

    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();
        validateSubmit();
        
        const tbody = document.getElementById('schedule-body');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `<tr id="empty-state"><td colspan="3" class="text-center py-10 text-muted">...</td></tr>`;
        }
    }

    function attachValidationEvents() {
        $('.supplier-select, .product-select').on('change', function() {
            validateSubmit();
        });
    }

    function validateSubmit() {
        const rows = document.querySelectorAll('#schedule-body tr:not(#empty-state)');
        let valid = rows.length > 0;
        const selected = new Set();

        rows.forEach(row => {
            const supplier = row.querySelector('.supplier-select').value;
            const product = row.querySelector('.product-select').value;

            if (!supplier || !product) valid = false;

            const key = supplier + '-' + product;
            if (selected.has(key)) {
                valid = false;
                row.classList.add('bg-light-danger');
            } else {
                selected.add(key);
                row.classList.remove('bg-light-danger');
            }
        });

        document.getElementById('submitBtn').disabled = !valid;
    }

    function lastRowIsValid() {
        const lastRow = document.querySelector('#schedule-body tr:last-child:not(#empty-state)');
        if (!lastRow) return true;
        const s = lastRow.querySelector('.supplier-select').value;
        const p = lastRow.querySelector('.product-select').value;
        return s !== '' && p !== '';
    }

    $(document).ready(function() {
        initSelect2();
        attachValidationEvents();
        validateSubmit();
    });
</script>
@endsection