<div x-data="{
    selectedItems: @entangle('selectedItems').defer,
    addItem(product) {
        if (!Array.isArray(this.selectedItems)) {
            this.selectedItems = [];
        }

        let existing = this.selectedItems.find(p => p.id === product.id);
        if (existing) {
            existing.qty++;
        } else {
            this.selectedItems.push({ ...product, qty: 1 });
        }

        // Panggil langsung method Livewire, tapi tanpa await supaya tidak delay
        $wire.addItem(product.id);
    }
}">

    <input type="text" class="form-control mb-5" wire:model.live.debounce.250ms="query" placeholder="Ketik nama produk..." />


    <div class="d-flex d-grid gap-xxl-9 flex-wrap gap-5">
        @foreach ($products as $product)
            <div class="card card-flush flex-row-fluid mw-100 p-6 pb-5" wire:key="product-{{ uniqid() }}">
                <div class="card-body text-center">
                    <img src="{{ asset('uploads/' . $product->location . '/' . $product->image_name) }}" class="rounded-3 w-150px h-150px w-xxl-200px h-xxl-200px mb-4" alt="" />
                    <div class="mb-2 text-center">
                        <span class="fw-bold fs-3 text-gray-800">{{ $product->name }}</span>
                        <span class="fw-semibold d-block fs-6 mt-n1">Stock <b>{{ $product->stock_qty ?? 0 }}</b></span>
                    </div>
                    <span class="text-success fw-bold fs-1">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <p class="mt-2 text-center">
                        @if (($product->stock_qty ?? 0) > 0)
                            <button class="btn btn-primary btn-sm" @click.prevent="addItem({ id: {{ $product->id }}, name: '{{ $product->name }}', price: {{ $product->price }} })">
                                <i class="fa fa-cart-plus"></i> Add
                            </button>
                        @else
                            <button class="btn btn-danger btn-sm" disabled>
                                <i class="fa fa-ban"></i> Stok Habis
                            </button>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="kategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Filter Brand</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-check form-check-custom form-check-solid mb-2">
                        <input class="form-check-input" wire:model="filterBrandValue" name="option" type="radio" value="0" id="flexRadioDefault" />
                        <label class="form-check-label" for="flexRadioDefault">
                            All
                        </label>
                    </div>

                    @foreach ($brand as $key => $value)
                        <div class="form-check form-check-custom form-check-solid mb-2">
                            <input class="form-check-input" wire:model="filterBrandValue" name="option" type="radio" value="{{ $value->id }}" id="flexRadioDefault" />
                            <label class="form-check-label" for="flexRadioDefault">
                                {{ $value->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="filterByBrand" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kategoriModal" tabindex="-1" role="dialog" aria-labelledby="kategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Filter Kategori</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-check form-check-custom form-check-solid mb-3">
                        <input class="form-check-input" wire:model="filterCatValue" name="option" type="radio" value="0" id="flexRadioDefault" />
                        <label class="form-check-label" for="flexRadioDefault">
                            All
                        </label>
                    </div>

                    @foreach ($category as $key => $value)
                        <div class="form-check form-check-custom form-check-solid mb-3">
                            <input class="form-check-input" wire:model="filterCatValue" name="option" type="radio" value="{{ $value->id }}" id="flexRadioDefault" />
                            <label class="form-check-label" for="flexRadioDefault">
                                {{ $value->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="filterByCategory" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>


    @php
        $outlet_id = $inv['outlet'];

        $existingSales = \Modules\Poz\Models\Sale::with('saleItems')
            ->where('sale_status', 1)
            ->whereHas('outlets', function ($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->get();

        $existingQueue = [];
        foreach ($existingSales as $sale) {
            foreach ($sale->saleItems as $item) {
                $existingQueue[$sale->student_id][] = [
                    'id' => $item->product_id,
                    'qty' => $item->qty,
                ];
            }
        }
    @endphp

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const existingQueue = @json($existingQueue);
            Livewire.dispatch('update-stock-from-queue', {
                data: {
                    queue: existingQueue,
                    status: 'minus'
                }
            });
        });
    </script>
</div>
