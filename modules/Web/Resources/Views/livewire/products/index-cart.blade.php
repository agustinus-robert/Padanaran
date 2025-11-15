<div>
    <div class="cart-dropdown dropdown">
        <a href="javascript:void(0)" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
            <div class="icon">
                <i class="icon-shopping-cart"></i>
                <span class="cart-count">{{ $countNum }}</span>
            </div>
            <p>Cart</p>
        </a>

        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-cart-products">
                @php($total = 0)
                @if (count($products) > 0)
                    @foreach ($products as $value)
                        <div class="product">
                            <div class="product-cart-details">
                                <h4 class="product-title">
                                    <a href="product.html">{{ $value->product->name }}</a>
                                </h4>

                                <span class="cart-product-info">
                                    <span class="cart-product-qty">{{ $value->qty }}</span>
                                    x {{ number_format($value->product->price, 0, ',', '.') }}
                                </span>
                            </div><!-- End .product-cart-details -->

                            <figure class="product-image-container">
                                <a href="javascript:void(0)" class="product-image">
                                    <img src="{{ asset('uploads/' . $value->product->location . '/' . $value->product->image_name) }}" alt="{{ $value->product->name }}">
                                </a>
                            </figure>
                            @php($total += $value->qty * $value->product->price)
                            {{-- <a href="#" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a> --}}
                        </div><!-- End .product -->
                    @endforeach
                @endif

            </div><!-- End .cart-product -->

            <div class="dropdown-cart-total">
                <span>Total</span>

                <span class="cart-total-price">{{ number_format($total, 0, ',', '.') }}</span>
            </div><!-- End .dropdown-cart-total -->

            <div class="dropdown-cart-action">
                <a href="{{ route('web::cart.page') }}" class="btn btn-primary">View Cart</a>
                <a href="{{ route('web::deliver.page') }}" class="btn-outline-primary-2 btn"><span>Checkout</span><i class="icon-long-arrow-right"></i></a>
            </div><!-- End .dropdown-cart-total -->
        </div><!-- End .dropdown-menu -->
    </div><!-- End .cart-dropdown -->
</div>
