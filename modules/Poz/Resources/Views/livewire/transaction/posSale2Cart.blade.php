<div class="flex-row-auto w-xl-450px">
    <div class="card card-flush bg-body" id="kt_pos_form">
        <div class="card-header pt-5">
            <h3 class="card-title fw-bold fs-2qx text-gray-800">Current Order</h3>
            <div class="card-toolbar">
                <a href="javascript:void(0)" wire:click="$emitUp('clearItem')" class="btn btn-light-primary fs-4 fw-bold py-4">Clear All</a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive mb-8">
                <table class="gs-0 gy-4 my-0 table align-middle">
                    <thead>
                        <tr>
                            <th class="min-w-175px"></th>
                            <th class="w-125px"></th>
                            <th class="w-60px"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selectedItems as $index => $item)
                            <tr data-kt-pos-element="item" data-kt-pos-item-price="{{ $item['price'] }}">
                                <td class="pe-0">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('uploads/' . productItem($item['id'])->location . '/' . productItem($item['id'])->image_name) }}" class="w-50px h-50px rounded-3 me-3" alt="" />
                                        <span class="fw-bold text-hover-primary fs-6 me-1 cursor-pointer text-gray-800">{{ productItem($item['id'])->name }}</span>
                                    </div>
                                </td>
                                <td class="pe-0">
                                    <div class="position-relative d-flex align-items-center">
                                        <button wire:click="$emitUp('decreaseQty', {{ $index }})" type="button" class="btn btn-icon btn-sm btn-light btn-icon-gray-500">
                                            <i class="ki-outline ki-minus fs-3x"></i>
                                        </button>
                                        <input type="text" class="form-control fs-3 fw-bold w-30px border-0 px-0 text-center text-gray-800" readonly value="{{ $item['qty'] }}" />
                                        <button type="button" class="btn btn-icon btn-sm btn-light btn-icon-gray-500" wire:click="$emitUp('increaseQty', {{ $index }})">
                                            <i class="ki-outline ki-plus fs-3x"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-primary fs-2">{{ $item['price'] }}</span>
                                </td>
                                <td>
                                    <button wire:click="$emitUp('eraseProduct', {{ $item['id'] }})" type="button" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-stack bg-success rounded-3 mb-11 p-6">
                <div class="fs-6 fw-bold text-white">
                    <span class="d-block lh-1 mb-2">Subtotal</span>
                    <span class="d-block mb-2">Discounts</span>
                    <span class="d-block mb-9">Tax</span>
                    <span class="d-block fs-2qx lh-1">Total</span>
                </div>
                <div class="fs-6 fw-bold text-end text-white">
                    <span class="d-block lh-1 mb-2">Rp {{ number_format($subTotal, 2) }}</span>
                    <span class="d-block no-border mb-2"> <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#discountModal"><i style="color:#0b0b38;" class="fas fa-edit"></i></a> {{ $inv['discount'] ?? 0 }}</span>
                    <span class="d-block mb-9">11%</span>
                    <span class="d-block fs-2qx lh-1">Rp {{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>

            <div class="m-0">
                <h1 class="fw-bold mb-5 text-gray-800">Payment Method</h1>
                <div class="d-flex flex-equal gap-xxl-9 mb-12 gap-5 px-0">
                    <label class="btn bg-light btn-color-gray-600 btn-active-text-gray-800 border-3 border-active-primary btn-active-light-primary w-100 border border-gray-100 px-4">
                        <input class="btn-check" type="radio" name="method" value="0" />
                        <i class="ki-outline ki-dollar fs-2hx mb-2 pe-0"></i>
                        <span class="fs-7 fw-bold d-block">Cash</span>
                    </label>
                </div>
                <form wire:submit.prevent="$emitUp('save')">
                    <button type="submit" class="btn btn-primary fs-1 w-100 py-1">
                        <i class="fa fa-shopping-bag" style="font-size:18px;"></i>
                        Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
