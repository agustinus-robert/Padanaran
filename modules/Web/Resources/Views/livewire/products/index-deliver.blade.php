<div>
    <div class="page-content">
        <div class="checkout">
            <div class="container">
                <div class="checkout-discount">
                    <form action="#">
                        <input type="text" class="form-control" required id="checkout-discount-input">
                        <label for="checkout-discount-input" class="text-truncate">Have a coupon? <span>Click here to enter your code</span></label>
                    </form>
                </div><!-- End .checkout-discount -->
                <div>
                    <div class="row">
                        <div class="col-lg-9">
                            <h2 class="checkout-title">Billing Details</h2><!-- End .checkout-title -->


                            <label>Name *</label>
                            <input id="name" type="text" class="form-control" required>

                            <label>Email</label>
                            <input id="email" type="email" placeholder="isikan email anda" class="form-control">

                            <label>Country *</label>
                            <input id="country" type="text" class="form-control" required>

                            <label>Street address *</label>
                            <input id="address" type="text" class="form-control" placeholder="Alamat Rumah" required>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Provinsi</label>

                                    <select id="province-select" class="form-control">
                                        <option value="" disabled selected>Pilih Provinsi</option>
                                        @foreach ($province as $key => $value)
                                            <option value="{{ $value['province_id'] }}">{{ $value['province'] }}</option>
                                        @endforeach
                                        <!-- Tambahkan opsi lainnya sesuai kebutuhan -->
                                    </select>
                                    <div id="province-spinner" class="spinner hidden"></div>
                                </div><!-- End .col-sm-6 -->

                                <div class="col-sm-6">
                                    <label>Kota</label>
                                    <select id="city-select" class="form-control" wire:ignore>
                                        <option value="" disabled selected>Pilih Kota</option>
                                    </select>
                                    <div id="city-spinner" class="spinner hidden"></div>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <select wire:ignore onchange="window.shippingMethod()" id="shipping-select" class="form-control">
                                        <option value="">Pilih Kurir</option>
                                        <option value="jne">JNE</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <select wire:ignore id="shipping-type-select" class="form-control">
                                        <option value="">Pilih Tipe Shipping</option>
                                    </select>
                                </div>

                            </div><!-- End .row -->

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkout-create-acc">
                                <label class="custom-control-label" for="checkout-create-acc">Create an account?</label>
                            </div><!-- End .custom-checkbox -->

                            <label>Order notes (optional)</label>
                            <textarea class="form-control" cols="30" rows="4" placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                        </div><!-- End .col-lg-9 -->
                        <aside class="col-lg-3">
                            <div class="summary">
                                <h3 class="summary-title">Your Order</h3><!-- End .summary-title -->

                                <table class="table-summary table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($carts as $key => $value)
                                            <tr>
                                                <td><a href="#">{{ $value->product->name }}</a></td>
                                                <td>{{ $value->qty }} x {{ number_format($value->product->price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="summary-subtotal">
                                            <td>Subtotal:</td>
                                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr><!-- End .summary-subtotal -->
                                        <tr>
                                            <td>Shipping:</td>
                                            <td>{{ $shipping }}</td>
                                        </tr>
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        </tr><!-- End .summary-total -->
                                    </tbody>
                                </table><!-- End .table table-summary -->

                                <button id="save" class="btn-outline-primary-2 btn-order btn btn-block">
                                    <span class="btn-text">Place Order</span>
                                    <span class="btn-hover-text">Proceed to Checkout</span>
                                </button>
                            </div><!-- End .summary -->
                        </aside><!-- End .col-lg-3 -->
                    </div><!-- End .row -->
                </div>
            </div><!-- End .container -->
        </div><!-- End .checkout -->
    </div><!-- End .page-content -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        function number_format(amount, decimals = 0, dec_point = ',', thousands_sep = '.') {
            amount = amount.toFixed(decimals);
            amount = amount.replace('.', dec_point);
            let parts = amount.split(dec_point);
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
            return parts.join(dec_point);
        }
    </script>
    @script
        <script>
            window.updateCart = function(itemId, change) {
                let qtyElement = document.getElementById(`qty-${itemId}`);
                let currentQty = parseInt(qtyElement.innerText);

                if (currentQty + change < 1) {
                    alert('Produk tidak boleh kurang dari 1');
                    return;
                }

                qtyElement.innerText = currentQty + change;
                $wire.dispatch('cartItemUpdate', {
                    id: itemId,
                    action: change
                })
            };

            window.shippingMethod = function() {
                const shippingCityOrigin = 419;
                const shippingCourier = window.document.querySelector('#shipping-select').value;
                const shippingCityDestination = window.document.querySelector('#city-select').value;
                const shippingTypeSelect = window.document.querySelector('#shipping-type-select');
                shippingTypeSelect.disabled = true;

                $wire.dispatch('shippingSave', {
                    cityOrigin: shippingCityOrigin,
                    courier: shippingCourier,
                    cityDestination: shippingCityDestination
                })


                Livewire.hook('message.processed', () => {
                    modalToggle.checked = true;
                });
            }

            window.addShipping = function() {
                const spinner = document.querySelector('#shipping-cost-spinner');
                spinner.classList.remove('hidden');

                const elem = document.querySelector('#changeCosted');
                elem.classList.add('hidden');

                var costShip = window.document.querySelector('#shipping-type-select').value;

                $wire.dispatch('shippingCosted', {
                    shippingCost: costShip
                });
            }


            Livewire.on('cart-count', (itemId, qty) => {
                let qtyElement = document.getElementById(`qty-${itemId[0].itemId}`);
                if (qtyElement) {
                    qtyElement.innerText = qty;
                }

                $wire.dispatch('refresh');
                $wire.dispatch('countCart')
            });

            window.addEventListener('DOMContentLoaded', function() {
                const name = window.document.querySelector('#name');
                const email = window.document.querySelector('#email');
                const provinceSelect = window.document.querySelector('#province-select');
                const citySelect = window.document.querySelector('#city-select');
                const provinceSpinner = window.document.querySelector('#province-spinner');
                const citySpinner = window.document.querySelector('#city-spinner');
                const shippingCourierz = window.document.querySelector('#shipping-select');
                const shippingTypeSelect = window.document.querySelector('#shipping-type-select');
                const saveData = window.document.querySelector('#save');

                citySelect.disabled = true;
                shippingTypeSelect.disabled = true;
                shippingCourierz.disabled = true;

                saveData.addEventListener('click', function() {
                    $wire.dispatch('shippingTransaction', {
                        name: name.value,
                        email: email.value,
                        province: provinceSelect.value,
                        city: citySelect.value,
                        courier: shippingCourierz.value,
                        shipping_type: shippingTypeSelect.value
                    });
                })

                provinceSelect.addEventListener('change', function() {
                    if (provinceSelect.value === '') {
                        shippingCourierz.value = '';
                        shippingTypeSelect.value = '';
                        citySelect.value = '';
                        shippingTypeSelect.disabled = true;
                        shippingCourierz.disabled = true;
                    } else {
                        citySelect.disabled = false;
                        citySpinner.classList.remove('hidden');

                        $wire.dispatch('getCities', {
                            provinceId: this.value
                        })
                    }
                });

                citySelect.addEventListener('change', function() {
                    if (citySelect.value === '') {
                        shippingCourierz.value = '';
                        shippingTypeSelect.value = '';
                        citySelect.value = '';
                        shippingCourierz.disabled = true;
                        shippingTypeSelect.disabled = true;

                    } else {
                        shippingCourierz.disabled = false;
                        shippingTypeSelect.disabled = false;
                    }
                });


            });

            Livewire.on('updateCities', function(cities) {
                const citySelect = document.querySelector('#city-select'); // Cari ulang elemen
                if (!citySelect) {
                    console.error("Elemen #city-select tidak ditemukan.");
                    return;
                }

                citySelect.classList.remove('hidden');

                if (cities.length > 0) {
                    setTimeout(() => {
                        cities[0].city.forEach(function(city) {
                            const option = document.createElement('option');
                            option.value = city.city_id;
                            option.textContent = city.city_name;
                            citySelect.appendChild(option);
                        })
                    }, 100)
                } else {
                    console.error("Data kota tidak ditemukan atau kosong");
                }
            });

            Livewire.on('updateShipping', function(shippings) {
                const shippingTypeSelect = window.document.querySelector('#shipping-type-select');
                shippingTypeSelect.disabled = false;

                if (shippings.length > 0) {
                    setTimeout(() => {
                        shippings[0].json.forEach(function(ships) {
                            ships.costs.forEach(function(costs) {
                                const option = document.createElement('option');
                                option.value = costs.cost[0].value;
                                option.textContent = costs.description + ' Rp (' + number_format(costs.cost[0].value, 0, ',', '.') + ') ';
                                shippingTypeSelect.appendChild(option);
                            })
                        })
                    }, 100)
                } else {
                    console.error("Data kurir tidak ditemukan atau kosong");
                }
            })
        </script>
    @endscript
</div>
