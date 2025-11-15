<div class="flex-row-auto w-xl-450px">
    <div class="card card-flush bg-body" id="kt_pos_form">
        <div class="card-header pt-5">
            <h3 class="card-title fw-bold fs-2qx text-gray-800">Current Order</h3>
            <div class="card-toolbar">
                <a href="javascript:void(0)" wire:click="clearItem" class="btn btn-light-primary fs-4 fw-bold py-4">Clear All</a>
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
                        @if (count($selectedItems) > 0)
                            @foreach ($selectedItems as $index => $item)
                                <tr data-kt-pos-element="item" data-kt-pos-item-price="{{ productItem($item['id'])->price }}">
                                    <td class="pe-0">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('uploads/' . productItem($item['id'])->location . '/' . productItem($item['id'])->image_name) }}" class="w-50px h-50px rounded-3 me-3" alt="" />
                                            <span class="fw-bold text-hover-primary fs-6 me-1 cursor-pointer text-gray-800">{{ productItem($item['id'])->name }}</span>
                                        </div>
                                    </td>
                                    <td class="pe-0">
                                        <div class="position-relative d-flex align-items-center" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="999" data-kt-dialer-step="1" data-kt-dialer-decimals="0">
                                            <button wire:click="decreaseQty({{ $index }})" type="button" class="btn btn-icon btn-sm btn-light btn-icon-gray-500" data-kt-dialer-control="decrease">
                                                <i class="ki-outline ki-minus fs-3x"></i>
                                            </button>
                                            <input type="text" class="form-control fs-3 fw-bold w-30px border-0 px-0 text-center text-gray-800" data-kt-dialer-control="input" placeholder="Amount" name="manageBudget" value="{{ $item['qty'] }}" readonly="readonly" value="1" />

                                            <button type="button" class="btn btn-icon btn-sm btn-light btn-icon-gray-500" data-kt-dialer-control="increase" wire:click="increaseQty({{ $index }})">
                                                <i class="ki-outline ki-plus fs-3x"></i>
                                            </button>

                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-primary fs-2" data-kt-pos-element="item-total">{{ productItem($item['id'])->price }}

                                        </span>
                                    </td>
                                    <td>
                                        <button wire:click="eraseProduct({{ $item['id'] }})" type="button" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-stack bg-success rounded-3 mb-11 p-6">
                <div class="fs-6 fw-bold text-white">
                    <span class="d-block lh-1 mb-2">Subtotal</span>
                    <span class="d-block mb-2">Discounts</span>
                    <span class="d-block mb-9">Tax</span>
                    <span class="d-block mb-2">Kembalian</span>
                    <span class="d-block fs-2qx lh-1">Total</span>
                </div>
                <div class="fs-6 fw-bold text-end text-white">
                    <span class="d-block lh-1 mb-2" data-kt-pos-element="total">Rp {{ number_format($subTotal, 2) }}</span>
                    <span class="d-block no-border mb-2" data-kt-pos-element="discount"> <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#discountModal"><i style="color:#0b0b38;" class="fas fa-edit"></i></a> {{ isset($this->inv['discount']) ? $this->inv['discount'] : 0 }}</span>
                    <span class="d-block mb-9" data-kt-pos-element="tax">11%
                        <input class="form-control" type="hidden" wire:model="inv.ppn" disabled />
                    </span>
                    <span class="d-block no-border mb-2" data-kt-pos-element="returns">Rp {{ number_format($inv['returns'], 2) }}</span>
                    <span class="d-block fs-2qx lh-1" data-kt-pos-element="grant-total">Rp {{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="container">
                    <label><b>Total Pembayaran</b></label>
                    <input type="number" class="form-control" id="totalPayment" wire:change="checkPaymentTotal" wire:model.live="inv.paymentTotal">
                </div>
            </div>

            <div class="m-0">
                <div class="row">
                    <div class="col-6">
                        <form wire:submit.prevent="save" enctype="multipart/form-data">
                            <button @disabled(!$canPay) type="submit" class="btn btn-primary fs-1 w-100 py-1">
                                <i class="fa fa-shopping-bag" style="font-size:18px;"></i>
                                Bayar</button>
                        </form>
                    </div>
                    <div class="col-6">
                        {{-- <form wire:submit.prevent="queueSave" enctype="multipart/form-data"> --}}
                        <button class="btn btn-info fs-1 w-100 py-1" data-bs-toggle="modal" data-bs-target="#QueuePlaceModal" class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <i class="fa fa-list" style="font-size:18px;"></i>
                            Antrikan</button>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <!-- Input discount menggunakan Livewire -->
                    <input type="number" class="form-control" id="discount" wire:model.defer="inv.discount">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Tombol submit yang akan menyimpan nilai discount -->
                    <button type="button" wire:click="applyDiscount" class="btn btn-primary" data-bs-dismiss="modal" wire:click="applyDiscount">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="QueuePlaceModal" tabindex="-1" role="dialog" aria-labelledby="queuePlaceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" wire:ignore>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Antrian</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <form id="queueForm">
                    <div class="modal-body">
                        <select class="form-select select-2" id="studentSelect" name="student_id">
                            <option value="">Pilih Siswa</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="QueueModal" tabindex="-1" role="dialog" aria-labelledby="queueModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Antrian</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body" wire:key="queue-list-content">
                    @if (count($studentQueue) > 0)
                        <ul class="list-group">
                            {{-- {{ dd($studentQueue) }} --}}
                            @foreach ($studentQueue as $index => $q)
                                <li class="list-group-item d-flex justify-content-between align-items-center" wire:key="queue-item-{{ $index }}">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="selectedQueue" wire:model="selectedQueue" value="{{ $index }}">
                                        <label class="form-check-label" for="queue-{{ $index }}">
                                            {{ $q['name'] ?? '-' }}
                                        </label>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-danger" wire:click="deleteQueue({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Belum ada transaksi di antrian.</p>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="showSelectedQueue" class="btn btn-warning" data-bs-dismiss="modal"><i class="fa fa-folder-open" aria-hidden="true"></i> Tampilkan</button>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Masukkan Total Uang</h5>

                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <label for="discount">Total Uang</label>
                    <!-- Input discount menggunakan Livewire -->
                    <input type="number" class="form-control" id="totalPayment" wire:model.defer="inv.paymentTotal">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Tombol submit yang akan menyimpan nilai discount -->
                    <button type="button" wire:click="applyReturns" class="btn btn-primary" data-bs-dismiss="modal" wire:click="applyreturns">Save</button>
                </div>
            </div>
        </div>
    </div> --}}

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
        function loadQueueToLivewire() {
            Livewire.dispatch('queue-sh');
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('queue-updated', () => {
                let modalBody = document.querySelector('#QueueModal .modal-body');
                modalBody.innerHTML = @this.renderQueueHtml;
            });

            // Livewire.on('queue-save', (data) => {
            //     let queue = JSON.parse(localStorage.getItem('queue') || '{}');

            //      Object.keys(data).forEach(rootKey => {
            //         if(!queue[rootKey]) queue[rootKey] = {};

            //         Object.keys(data[rootKey]).forEach(studentId => {
            //             if(queue[rootKey][studentId]) {
            //                 // Merge items lama dengan items baru
            //                 let existingItems = queue[rootKey][studentId].items;
            //                 let newItems = data[rootKey][studentId].items;

            //                 // jika ada item dengan id sama, jumlahkan qty
            //                 newItems.forEach(newItem => {
            //                     let found = existingItems.find(i => i.id === newItem.id);
            //                     if(found) {
            //                         found.qty += newItem.qty;
            //                     } else {
            //                         existingItems.push(newItem);
            //                     }
            //                 });

            //                 // update totals
            //                 queue[rootKey][studentId].sub_total = data[rootKey][studentId].sub_total;
            //                 queue[rootKey][studentId].grand_total = data[rootKey][studentId].grand_total;

            //             } else {
            //                 // student baru di root ini
            //                 queue[rootKey][studentId] = data[rootKey][studentId];
            //             }
            //         });
            //     });

            //     localStorage.setItem('queue', JSON.stringify(queue))
            // });
        });
    </script>

    @script
        <script>
            const form = document.getElementById("queueForm");

            form.addEventListener("submit", function(e) {
                e.preventDefault();

                const studentId = document.getElementById("studentSelect").value;
                $wire.dispatch('queueSv', {
                    student_id: studentId
                });

                const modalEl = document.getElementById('QueuePlaceModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        </script>
    @endscript

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('delete-queue-item', function(payloadArray) {
                const payload = payloadArray[0] ?? {};
                const queueKey = payload.queueKey;

                if (queueKey === undefined) return;
                let queue = JSON.parse(localStorage.getItem('queue') || '{}');

                Object.keys(queue).forEach(rootKey => {
                    if (queue[rootKey] && queue[rootKey][queueKey]) {
                        delete queue[rootKey][queueKey];
                    }
                });


                localStorage.setItem('queue', JSON.stringify(queue));
                Livewire.dispatch('update-stock-from-queue', { queue });
                Livewire.dispatch('queue-loaded', { queue });
            });
        });
    </script> --}}


</div>
