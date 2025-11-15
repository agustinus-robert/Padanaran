<div>
    <div class="products">
        <div class="row justify-content-center">
            @foreach ($product as $value)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product product-2">
                        <figure class="product-media">
                            <span class="product-label label-circle label-sale">Sale</span>
                            <a href="javascript:void(0)">
                                <img style="object-fit: cover; width:280px; height:200px;" src="{{ asset('uploads/' . $value->location . '/' . $value->image_name) }}" alt="{{ $value->name }}" class="product-image">
                            </a>

                            <div class="product-action-vertical">
                                <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"></a>
                            </div><!-- End .product-action -->

                            <div class="product-action">
                                <a x-on:click="Livewire.dispatch('product-save-cart', { productId: {{ $value->id }} });" data-product-id="{{ $value->id }}" href="javascript:void(0)" class="btn-product btn-cart" title="Add to cart"><span>add to cart</span></a>
                                <a x-on:click="$dispatch('product-save-cart-redir', { productId: {{ $value->id }} });" href="javascript:void(0)" class="btn-product btn-cart" title="Checkout"><span>checkout</span></a>
                            </div><!-- End .product-action -->
                        </figure><!-- End .product-media -->

                        <div class="product-body">
                            <div class="product-cat">
                                <a href="#">-</a>
                            </div><!-- End .product-cat -->
                            <h3 class="product-title"><a href="product.html">{{ $value->name }}</a></h3><!-- End .product-title -->
                            <div class="product-price">
                                <span class="new-price">{{ 'Rp ' . number_format($value->price, 2, ',', '.') }}</span>
                                {{-- <span class="old-price">Was $349.99</span> --}}
                            </div><!-- End .product-price -->
                            <div class="ratings-container">
                                <div class="ratings">
                                    <div class="ratings-val" style="width: 40%;"></div><!-- End .ratings-val -->
                                </div><!-- End .ratings -->
                                <span class="ratings-text">( 4 Reviews )</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </div><!-- End .row -->
    </div><!-- End .products -->
</div>
