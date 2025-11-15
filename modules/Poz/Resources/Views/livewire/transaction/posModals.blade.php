<div>
    <!-- Discount Modal -->
    <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Set Discount</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <label for="discount">Discount</label>
                    <input type="number" class="form-control" id="discount" wire:model.defer="inv.discount">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="applyDiscount" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="ProductModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add Direct Product</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    @livewire('poz::transaction.product', ['action' => 'direction'])
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Modal -->
    <div class="modal fade" id="kategoriModal" tabindex="-1" role="dialog" aria-labelledby="kategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kategoriModalLabel">Filter Kategori</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" wire:model="filterCatValue" name="option" type="radio" value="0" id="flexRadioDefaultAll" />
                        <label class="form-check-label" for="flexRadioDefaultAll">All</label>
                    </div>
                    @foreach ($category as $key => $value)
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" wire:model="filterCatValue" name="option" type="radio" value="{{ $value->id }}" id="flexRadioDefault{{ $value->id }}" />
                            <label class="form-check-label" for="flexRadioDefault{{ $value->id }}">{{ $value->name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="filterByCategory" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Shortcut Modal -->
    <div class="modal fade" id="ShortcutModal" tabindex="-1" role="dialog" aria-labelledby="shortcutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shortcutModalLabel">ShortCut Brand</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    @foreach ($brand as $key => $value)
                        <div class="form-check form-check-custom form-check-solid p-2">
                            <input type="checkbox" class="form-check-input" wire:model="checkboxShortcut" value="{{ $value->id }}" id="shortcutCheckbox{{ $value->id }}" />
                            <label class="form-check-label" for="shortcutCheckbox{{ $value->id }}">{{ $value->name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="saveShortcut" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Brand Modal -->
    <div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="brandModalLabel">Filter Brand</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" wire:model="filterBrandValue" name="option" type="radio" value="0" id="filterBrandAll" />
                        <label class="form-check-label" for="filterBrandAll">All</label>
                    </div>
                    @foreach ($brand as $key => $value)
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" wire:model="filterBrandValue" name="option" type="radio" value="{{ $value->id }}" id="filterBrand{{ $value->id }}" />
                            <label class="form-check-label" for="filterBrand{{ $value->id }}">{{ $value->name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="filterByBrand" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('close-modal', function() {
            $('#ProductModal').fadeOut(300, function() {
                $('#ProductModal').modal('hide').fadeIn();
            });
        });

        document.addEventListener('close-modal-shortcut', function() {
            $('#ShortcutModal').fadeOut(300, function() {
                var modal = new bootstrap.Modal(document.getElementById('ShortcutModal'));
                modal.hide();
            });
            $('.modal-backdrop').remove();
        });
    </script>
</div>
