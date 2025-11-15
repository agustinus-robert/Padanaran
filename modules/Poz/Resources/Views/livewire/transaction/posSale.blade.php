<div>
    <div class="row">
        <div class="col-md-1 no-padding">
            <div class="sidebar close">
                <div class="logo-details">
                    <i class='bx bxl-c-plus-plus'></i>
                    <span class="logo_name">CodingLab</span>
                </div>
                <ul class="nav-links">
                    <li>
                        <div class="iocn-link">
                            <a href="javascript:void(0)" wire:click="filterReset">
                                <i class="fa fa-refresh"></i>
                                <span class="link_name">Reset</span>
                            </a>
                        </div>
                    </li>

                    <li class="divider">
                        <i class="fas fa-ellipsis-h"></i>
                    </li>


                    <li>

                        <div class="iocn-link">
                            <a href="#">
                                <i class="fa-solid fa-tags"></i>
                                <span class="link_name">Category</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a class="link_name" href="#">Category</a></li>
                            @foreach ($category as $key => $value)
                                <a href="javascript:void(0)" wire:click="filterByCategory({{ $value->id }})">
                                    {{ $value->name }}
                                </a>
                            @endforeach
                        </ul>
                    </li>

                    <li>
                        <div class="iocn-link">
                            <a href="#">
                                <i class="fa-solid fa-brands fa-stubber"></i>
                                <span class="link_name">Brand</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a class="link_name" href="#">Brands</a></li>
                            @foreach ($brand as $key => $value)
                                <li>
                                    <a href="javascript:void(0)" wire:click="filterByBrand({{ $value->id }})">
                                        {{ $value->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="divider">
                        <i class="fas fa-ellipsis-h"></i>
                    </li>

                    <li>
                        <div class="iocn-link">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#ProductModal">
                                <i class="fa-solid fa-box"></i>
                                <span class="link_name">Product</span>
                            </a>
                        </div>
                    </li>

                    <li>
                        <div class="iocn-link">
                            <a href="javascript:void(0)" wire:click="addSplit">
                                <i class="fa-solid fas fa-coins"></i>
                                <span class="link_name">Split Bill</span>
                            </a>
                        </div>
                    </li>


                    <li>
                        <div class="iocn-link">
                            <a href="javascript:void(0)" wire:click="addCashierBalance">
                                <i class="fa-solid fa-cash-register"></i>
                                <span class="link_name">Cash Register</span>
                            </a>
                        </div>
                    </li>


                    <li>
                        <div class="iocn-link">
                            <a href="javascript:void(0)" wire:click="changeTax">
                                <i class="fa fa-calculator" aria-hidden="true"></i>
                                <span class="link_name">Choose Tax</span>
                            </a>
                        </div>
                    </li>

                    <li class="divider">
                        <i class="fas fa-ellipsis-h"></i>
                    </li>

                </ul>
            </div>
        </div>

        <div class="col-md-11">
            <section class="header-main">

                <div class="row align-items-center">
                    <div class="col-lg-2">
                        <div class="brand-wrap ml-1">
                            <i class="fa-solid fa-store fa-xl"></i>
                            <h2 class="logo-text">POS</h2>
                        </div> <!-- brand-wrap.// -->
                    </div>

                    <div class="col-lg-4">
                        {{-- <p><i class="fa fa-user fa-xl" aria-hidden="true"></i> Kasir 1</p> --}}
                    </div>

                    <div class="col-lg-6 col-sm-6">
                        <div class="widgets-wrap d-flex justify-content-end mr-2">
                            <div class="widget-header">
                                <a href="#" class="icontext">
                                    <a href="#" class="btn btn-primary m-btn m-btn--icon m-btn--icon-only">
                                        <i class="fa fa-home"></i>
                                    </a>
                                </a>

                                <a href="#" class="icontext mr-3">
                                    <a href="#" class="btn btn-primary m-btn m-btn--icon m-btn--icon-only">
                                        <i class="fa fa-bell"></i>
                                    </a>
                                </a>
                            </div> <!-- widget .// -->
                            <div class="widget-header dropdown">
                                <a href="#" class="icontext ml-3" data-toggle="dropdown" data-offset="20,10">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png" class="avatar" alt="">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#"><i class="fa fa-sign-out-alt"></i> Logout</a>
                                </div> <!--  dropdown-menu .// -->
                            </div> <!-- widget  dropdown.// -->
                        </div> <!-- widgets-wrap.// -->
                    </div> <!-- col.// -->
                </div> <!-- row.// -->

            </section>
            <!-- ========================= SECTION CONTENT ========================= -->
            <section class="section-content padding-y-sm bg-default">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8 card padding-y-sm card">
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Silahkan masukkan nama produk" type="text" wire:model.live.debounce.500ms="query" placeholder="Masukkan 3 huruf, untuk melakukan pencarian..." class="form-control">
                                @if (strlen($query) >= 3)
                                    <ul class="list-group mt-2">
                                        @forelse($results as $result)
                                            <li class="list-group-item" wire:click="addItem({{ $result['id'] }})" style="cursor: pointer;">
                                                {{ $result['name'] }} <!-- Sesuaikan field yang ingin ditampilkan -->
                                            </li>
                                        @empty
                                            <li class="list-group-item">Tidak ada hasil</li>
                                        @endforelse
                                    </ul>
                                @endif
                            </div>

                            <span id="items">
                                <div class="row">
                                    @foreach ($product as $key => $val)
                                        <div class="col-md-3">
                                            <figure class="card card-product">
                                                @if (strtotime(date('Y-m-d', strtotime($val->created_at))) == strtotime(date('Y-m-d')))
                                                    <span class="badge-new"> NEW </span>
                                                @endif

                                                <div class="img-wrap">
                                                    <img src="{{ asset('uploads/' . $val->location . '/' . $val->image_name) }}">
                                                    <a class="btn-overlay" href="#"><i class="fa fa-search-plus"></i> Quick view</a>
                                                </div>
                                                <figcaption class="info-wrap">
                                                    <a href="#" class="title">{{ $val->name }}</a>
                                                    <div class="action-wrap">
                                                        <a href="javascript:void(0)" wire:click="addItem({{ $val->id }})" class="btn btn-primary btn-sm float-right"> <i class="fa fa-cart-plus"></i> Add </a>
                                                        <div class="price-wrap h5">
                                                            <span class="price-new">Rp. {{ number_format($val->price, 0, ',', '.') }}</span>
                                                        </div> <!-- price-wrap.// -->
                                                    </div> <!-- action-wrap -->
                                                </figcaption>
                                            </figure> <!-- card // -->
                                        </div> <!-- col // -->
                                    @endforeach
                                </div> <!-- row.// -->

                            </span>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <span id="cart">
                                    <div class="table-container">
                                        <table class="tbs table-hover shopping-cart-wrap table">
                                            <thead class="text-muted">
                                                <tr>
                                                    <th scope="col" class="text-center" width="100">Item</th>
                                                    <th scope="col" class="text-center" width="20">Qty</th>
                                                    <th scope="col" class="text-center" width="50">Price</th>
                                                    <th scope="col" class="text-center" width="20">Delete</th>
                                                </tr>
                                            </thead>
                                            @if (count($selectedItems) > 0)
                                                <tbody class="tbs-bdy">
                                                    @foreach ($selectedItems as $index => $item)
                                                        <tr>
                                                            <td>
                                                                <figure class="media">
                                                                    <div class="img-wrap"><img src="{{ asset('uploads/' . productItem($item['id'])->location . '/' . productItem($item['id'])->image_name) }}" class="img-thumbnail img-xs"></div>
                                                                    <figcaption class="media-body">
                                                                        <h6 style="font-size:12px;" class="title text-truncate">{{ productItem($item['id'])->name }} </h6>
                                                                        <p style="font-size:10px;">Brand: {{ brandItem($item['brand_id'])->name }} - Category: {{ categoryItem($item['category_id'])->name }}</p>
                                                                    </figcaption>
                                                                </figure>
                                                            </td>

                                                            <td width="80" class="text-center">
                                                                <input size="30" type="number" class="form-control" wire:model.lazy="selectedItems.{{ $index }}.qty" min="1" wire:change="updateQty({{ $index }}, $event.target.value)" />
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="price-wrap">
                                                                    <div style="font-size:10px;" class="price">{{ $item['price'] }}</div>
                                                                </div> <!-- price-wrap .// -->
                                                            </td>

                                                            <td class="text-center">
                                                                <button wire:click="removeItem({{ $item['id'] }})" class="btn btn-sm btn-outline-danger"> <i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            @endif
                                        </table>
                                    </div>
                                </span>
                            </div> <!-- card.// -->
                            <div class="box">
                                <dl class="dlist-align">
                                    <dt style="font-size:13px;">Discount: </dt>
                                    <dd class="text-right" style="font-size:13px;"><button data-toggle="modal" data-target="#discountModal"><i class="fa-solid fa-minus"></i></button> <a href="#">{{ isset($this->inv['discount']) ? $this->inv['discount'] : 0 }}</a></dd>
                                </dl>
                                <dl class="dlist-align">
                                    <dt style="font-size:13px;">Tax:</dt>
                                    <dd class="text-right" style="font-size:13px;"><a href="#">11%</a></dd>
                                    <input class="form-control" type="hidden" wire:model="inv.ppn" disabled />
                                </dl>
                                <dl class="dlist-align">
                                    <dt style="font-size:13px;">Sub Total:</dt>
                                    <dd class="text-right" style="font-size:13px;"><b>Rp {{ number_format($subTotal, 2) }}</b></dd>
                                </dl>
                                <dl class="dlist-align">
                                    <dt style="font-size:13px;">Grand Total: </dt>
                                    <dd class="h4 b text-right" style="font-size:13px;"> Rp {{ number_format($grandTotal, 2) }} </dd>
                                </dl>
                                <form wire:submit.prevent="save" enctype="multipart/form-data">
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-md btn-block"> <i class="fa fa-shopping-bag"></i> Charge </button>
                                        </div>
                                    </div>
                                </form>

                            </div> <!-- box.// -->

                        </div>
                    </div><!-- container //  -->
                </div>
            </section>
        </div>
    </div>
    <!-- Modal untuk mengatur discount -->
    <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Set Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="discount">Discount (%)</label>
                    <!-- Input discount menggunakan Livewire -->
                    <input type="number" class="form-control" id="discount" wire:model.defer="inv.discount">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Tombol submit yang akan menyimpan nilai discount -->
                    <button type="button" wire:click="applyDiscount" class="btn btn-primary" data-dismiss="modal" wire:click="applyDiscount">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ProductModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Add Direct Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @livewire('poz::transaction.product', ['action' => 'direction'])
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('close-modal', function() {
            $('#ProductModal').fadeOut(300, function() {
                $('#ProductModal').modal('hide').fadeIn(); // Memastikan modal tidak terlihat
            });
        });

        document.addEventListener('close-modal', function() {
            $('#ProductModal').fadeOut(300, function() {
                $('#ProductModal').modal('hide').fadeIn(); // Memastikan modal tidak terlihat
            });
        });
    </script>
</div>
