@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Supplier')
@section('navtitle', env('APP_NAME') . ' Supplier')

@section('content')
<div class="mb-2">
    <button type="button" class="btn btn-sm btn-success" onclick="addRow()">+ Add Row</button>
</div>

<div class="card">
    @php
        $shiftLabels = [
            'morning' => 'Pagi',
            'afternoon' => 'Siang',
            'evening' => 'Sore',
        ];

        $shiftLabel = $shiftLabels[strtolower($supplier_schedule)] ?? ucfirst($supplier_schedule);
    @endphp

    <div class="card-header bg-primary text-white">
        Shift: {{ $shiftLabel }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('poz::schedule.supplier_schedule.store', ['outlet' => request()->query('outlet', auth()->user()->current_outlet_id)]) }}">
            @csrf
            <input type="hidden" name="time" value="{{ strtolower($supplier_schedule) }}">

            <table class="table table-bordered" id="schedule-table">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Product</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="schedule-body">
                    @foreach($prodSupp as $item)
                        <tr>
                            <td>
                                <select name="schedules[{{ $loop->index }}][supplier_id]" class="form-control supplier-select" required>
                                    <option value="">-- Select Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $supplier->id == $item->supplier_id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="schedules[{{ $loop->index }}][product_id]" class="form-control product-select" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <button type="submit" id="submitBtn" class="btn btn-primary mt-3" disabled>Save Schedule</button>
            </div>
        </form>
    </div>
</div>

<script>
    let rowIndex = document.querySelectorAll('#schedule-body tr').length;

    function addRow() {
        if (!lastRowIsValid()) {
            alert("Please complete the previous row first!");
            return;
        }

        const tbody = document.getElementById('schedule-body');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                <select name="schedules[${rowIndex}][supplier_id]" class="form-control supplier-select" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="schedules[${rowIndex}][product_id]" class="form-control product-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button>
            </td>
        `;

        tbody.appendChild(row);
        rowIndex++;

        attachValidationEvents();
        validateSubmit();
    }

    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();
        validateSubmit();
    }

    function attachValidationEvents() {
        document.querySelectorAll('.supplier-select, .product-select').forEach(el => {
            el.removeEventListener('change', validateSubmit);
            el.addEventListener('change', validateSubmit);
        });
    }

    function validateSubmit() {
        const rows = document.querySelectorAll('#schedule-body tr');
        let valid = true;

        if (rows.length === 0) {
            document.getElementById('submitBtn').disabled = true;
            return;
        }

        const selected = new Set();

        rows.forEach(row => {
            const supplier = row.querySelector('.supplier-select');
            const product = row.querySelector('.product-select');

            const supplierSelected = supplier && supplier.value !== '';
            const productSelected = product && product.value !== '';

            const key = supplier?.value + '-' + product?.value;
            if (selected.has(key)) {
                valid = false;
            } else {
                selected.add(key);
            }

            if (!(supplierSelected && productSelected)) {
                valid = false;
            }
        });

        document.getElementById('submitBtn').disabled = !valid;
    }

    function lastRowIsValid() {
        const lastRow = document.querySelector('#schedule-body tr:last-child');
        if (!lastRow) return true;

        const supplier = lastRow.querySelector('.supplier-select');
        const product = lastRow.querySelector('.product-select');

        return supplier?.value !== '' && product?.value !== '';
    }

    document.addEventListener('DOMContentLoaded', () => {
        attachValidationEvents();
        validateSubmit();
    });
</script>
@endsection
