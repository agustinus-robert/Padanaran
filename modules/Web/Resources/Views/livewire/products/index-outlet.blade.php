<div>
    <div class="cart-dropdown dropdown">
        <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
            <div class="icon">
                <i style="font-size:19px;" class="fa-solid fa-store"></i>
            </div>
            <p>Outlet</p>
        </a>

        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-cart-products">

                @foreach ($outlets as $value)
                    <div class="product">
                        <div class="product-cart-details">
                            <h4 class="product-title">
                                <a wire:click="redirectOutlet({{ $value->id }})" href="javascript:void(0)">{{ $value->name }}</a>
                            </h4>
                        </div><!-- End .product-cart-details -->

                        <figure class="product-image-container">
                            <a wire:click="redirectOutlet({{ $value->id }})" href="javascript:void(0)" class="product-image">
                                <img src="{{ asset('uploads/' . $value->location . '/' . $value->image_name) }}" alt="product">
                            </a>
                        </figure>
                    </div><!-- End .product -->
                @endforeach

            </div><!-- End .cart-product -->

        </div><!-- End .dropdown-menu -->
    </div>
</div>
