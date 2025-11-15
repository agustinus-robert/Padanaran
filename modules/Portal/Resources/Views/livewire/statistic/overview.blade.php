<div>
    <div class="d-flex justify-content-between align-items-center py-3">
        <h2 class="h3 fw-normal mb-0">Overview</h2>
        <div wire:loading wire:target="dateRange" class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

        <div class="dropdown">


            <select class="form-select" wire:model="selectedRange" wire:change="dateRange">
                <option value="">Pilih</option>
                <option value="currentWeek">Minggu Ini</option>
                <option value="beforeWeek">Minggu Sebelumnya</option>
                <option value="currentMonth">Bulan Ini</option>
                <option value="beforeMonth">Bulan Sebelumnya</option>
            </select>
        </div>
    </div>
    <div class="row items-push">
        <div class="col-sm-6 col-xl-3">
            <a class="block-rounded block-fx-pop h-100 mb-0 block text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-primary-lighter mx-auto my-3">
                        <i class="fa fa-exchange text-primary"></i>
                    </div>
                    <div class="display-4 fw-bold">{{ format_uang($purchase) }}</div>
                    <div class="text-muted mt-1">Pembelian</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a class="block-rounded block-fx-pop h-100 mb-0 block text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-xinspire-lighter mx-auto my-3">
                        <i class="fa fa-shopping-cart text-xinspire-dark"></i>
                    </div>
                    <div class="display-4 fw-bold">{{ format_uang($sale) }}</div>
                    <div class="text-muted mt-1">Penjualan</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a class="block-rounded block-fx-pop h-100 mb-0 block text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-xsmooth-lighter mx-auto my-3">
                        <i class="fa fa-undo text-xsmooth"></i>
                    </div>
                    <div class="display-4 fw-bold">{{ format_uang($retur) }}</div>
                    <div class="text-muted mt-1">Retur</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a class="block-rounded block-fx-pop h-100 mb-0 block text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-xplay-lighter mx-auto my-3">
                        <i class="fa fa-bar-chart text-xplay"></i>
                    </div>
                    <div class="display-4 fw-bold">{{ format_uang($revenue) }}</div>
                    <div class="text-muted mt-1">Pendapatan</div>
                    <div class="fs-8 fw-bold text-danger py-3">
                        Penjualan - Pembelian
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
