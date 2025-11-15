@extends('web::layouts.default3', [
    'useTransparentNavbar' => true,
])
@section('title', request()->bahasa == 'id' ? 'Beranda' : 'Home' . ' | ')
@push('styles')
    <style>
        .cta-border {
            margin-left: 0px !important;
        }

        .story-wrapper {
            width: 200px;
            height: 355px;
            /* Rasio 9:16 */
            border-radius: 15px;
            overflow: hidden;
            border: 2px solid #ccc;
            position: relative;
            background: #000;
        }

        .story-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
            transform: scale(1.2);
            /* Crop bagian pinggir */
            transform-origin: top center;
            pointer-events: none;
            /* biar nggak bisa klik kalau kamu mau readonly */
        }
    </style>
@endpush

@section('main')
    <div class="mobile-side-menu">
        <div class="side-menu-content">
            <div class="side-menu-head">
                <a href='javascript:void(0)'><img src="{{ asset('rosier/img/logo/logo-1.png') }}" alt="logo"></a>
                <button class="mobile-side-menu-close"><i class="fa-regular fa-xmark"></i></button>
            </div>
            <div class="side-menu-wrap"></div>
            <ul class="side-menu-list">
                <li><i class="fa-light fa-location-dot"></i>Address : <span>Amsterdam, 109-74</span></li>
                <li><i class="fa-light fa-phone"></i>Phone : <a href="tel:+01569896654">+01 569 896 654</a></li>
                <li><i class="fa-light fa-envelope"></i>Email : <a href="mailto:info@example.com">info@example.com</a></li>
            </ul>
        </div>
    </div>
    <!-- /.mobile-side-menu -->

    <div id="preloader">
        <div class="preloader-close">X</div>
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!-- ./ preloader -->

    <section class="hero-section">
        <div class="overlay"></div>
        <div class="hero-images">
            <div class="hero-people"><img src="{{ asset('rosier/img/images/hero-peoples.png') }}" alt="img"></div>
            <div class="hero-shape"><img src="{{ asset('rosier/img/shapes/hero-shape-1.png') }}" alt="shape"></div>
            <div class="hero-shape-2"><img src="{{ asset('rosier/img/shapes/hero-shape-2.png') }}" alt="shape"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-8"></div>
                <div class="col-xl-4 col-lg-12">
                    <div class="hero-content">
                        <h4 class="sub-title">ummer 22 women’s collection</h4>
                        <h2 class="title">Super COLLECTION <br>FOR WOMEN</h2>
                        <h5 class="price"><span>From</span>$320.00</h5>
                        <a href="shop.html" class="rr-primary-btn">View Collections</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ./ hero-section -->

    <section class="category-section pt-100 pb-100">
        <div class="container">
            <div class="category-top heading-space space-border">
                <div class="section-heading mb-0">
                    <h2 class="section-title">Best for your categories</h2>
                    <p>29 categories belonging to a total 15,892 products</p>
                </div>
                <!-- Carousel Arrows -->
                <div class="swiper-arrow">
                    <div class="swiper-nav swiper-next"><i class="fa-regular fa-arrow-left"></i></div>
                    <div class="swiper-nav swiper-prev"><i class="fa-regular fa-arrow-right"></i></div>
                </div>
            </div>
            {{-- <div class="category-carousel swiper">
                <div class="swiper-wrapper">
                    @foreach ($editor as $value)
                        <div class="swiper-slide">
                            <div class="category-item">
                                <div class="category-img" style="width: 200px; height: 200px; overflow: hidden; border-radius: 12px; border: 1px solid #ccc; background: #000;">
                                    <iframe src="{{ asset('story/' . $value->name_file) }}" width="100%" height="100%" style="border: none; transform: scale(1.2); transform-origin: top center; pointer-events: none;">
                                    </iframe>
                                </div>
                                <h3 class="title">
                                    <a href="{{ asset('story/' . $value->name_file) }}">Your Story</a>
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> --}}
        </div>
    </section>
    <!-- ./ category-section -->

    <section class="discount-section pb-100 overflow-hidden">
        <div class="row gy-lg-0 gy-4">
            <div class="col-lg-4 col-md-6">
                <div class="discount-item item-1">
                    <div class="product-overlay"></div>
                    <div class="shape"><img src="{{ asset('rosier/img/shapes/dis-shpe.png') }}" alt="shape"></div>
                    <div class="content">
                        <span>Special 50% Disocunt</span>
                        <h3 class="title">The Latest Men’s Trends <br> This Season</h3>
                        <a href="shop.html"><i class="fa-regular fa-plus"></i>View Collections</a>
                    </div>
                    <div class="men"><img src="{{ asset('rosier/img/images/discount-men-1.png') }}" alt="img"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="discount-item">
                    <div class="product-overlay"></div>
                    <div class="shape"><img src="{{ asset('rosier/img/shapes/dis-shpe.png') }}" alt="shape"></div>
                    <div class="men"><img src="{{ asset('rosier/img/images/discount-men-2.png') }}" alt="img"></div>
                    <div class="content">
                        <span>Special 50% Disocunt</span>
                        <h3 class="title">Latest Kids Trends <br>This Season</h3>
                        <a href="shop.html"><i class="fa-regular fa-plus"></i>View Collections</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="discount-item">
                    <div class="product-overlay"></div>
                    <div class="shape"><img src="{{ asset('rosier/img/shapes/dis-shpe.png') }}" alt="shape"></div>
                    <div class="men"><img src="{{ asset('rosier/img/images/discount-men-3.png') }}" alt="img"></div>
                    <div class="content">
                        <span>Special 50% Disocunt</span>
                        <h3 class="title">Latest Women’s Trends <br>This Season</h3>
                        <a href="shop.html"><i class="fa-regular fa-plus"></i>View Collections</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ./ discount-section -->

    <section class="fashion-section pb-100">
        <div class="container">
            <div class="category-top heading-space space-border">
                <div class="section-heading mb-0">
                    <h2 class="section-title">GET YOUR FASHION STYLE</h2>
                    <p>29 categories belonging to a total 15,892 products</p>
                </div>
                <!-- Carousel Arrows -->
                <div class="swiper-arrow">
                    <div class="swiper-nav swiper-next"><i class="fa-regular fa-arrow-left"></i></div>
                    <div class="swiper-nav swiper-prev"><i class="fa-regular fa-arrow-right"></i></div>
                </div>
            </div>
            <div class="shop-carousel swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="shop-item">
                            <div class="shop-thumb">
                                <div class="overlay"></div>
                                <img src="{{ asset('rosier/img/shop/shop-1.png') }}" alt="shop">
                                <span class="sale">New</span>
                                <ul class="shop-list">
                                    <li><a href="cart.html"><i class="fa-regular fa-cart-shopping"></i></a></li>
                                    <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                    <li><a href="#"><i class="fa-light fa-eye"></i></a></li>
                                </ul>
                            </div>
                            <div class="shop-content">
                                <span class="category">Levi’s Cotton</span>
                                <h3 class="title"><a href="shop-details.html">Monica Diara Party Dress</a></h3>
                                <div class="review-wrap">
                                    <ul class="review">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                    <span>(15 Reviews)</span>
                                </div>
                                <span class="price"> <span class="offer">$250.00</span>$157.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="shop-item">
                            <div class="shop-thumb">
                                <div class="overlay"></div>
                                <img src="{{ asset('rosier/img/shop/shop-2.png') }}" alt="shop">
                                <span class="sale">New</span>
                                <ul class="shop-list">
                                    <li><a href="cart.html"><i class="fa-regular fa-cart-shopping"></i></a></li>
                                    <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                    <li><a href="#"><i class="fa-light fa-eye"></i></a></li>
                                </ul>
                            </div>
                            <div class="shop-content">
                                <span class="category">Levi’s Cotton</span>
                                <h3 class="title"><a href="shop-details.html">Onima Black Flower Sandal</a></h3>
                                <div class="review-wrap">
                                    <ul class="review">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                    <span>(15 Reviews)</span>
                                </div>
                                <span class="price"> <span class="offer">$450.00</span>$257.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="shop-item">
                            <div class="shop-thumb">
                                <div class="overlay"></div>
                                <img src="{{ asset('rosier/img/shop/shop-3.png') }}" alt="shop">
                                <span class="sale">New</span>
                                <ul class="shop-list">
                                    <li><a href="cart.html"><i class="fa-regular fa-cart-shopping"></i></a></li>
                                    <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                    <li><a href="#"><i class="fa-light fa-eye"></i></a></li>
                                </ul>
                            </div>
                            <div class="shop-content">
                                <span class="category">Levi’s Cotton</span>
                                <h3 class="title"><a href="shop-details.html">Poncho Sweater international</a></h3>
                                <div class="review-wrap">
                                    <ul class="review">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                    <span>(15 Reviews)</span>
                                </div>
                                <span class="price"> <span class="offer">$550.00</span>$427.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="shop-item">
                            <div class="shop-thumb">
                                <div class="overlay"></div>
                                <img src="{{ asset('rosier/img/shop/shop-4.png') }}" alt="shop">
                                <span class="sale">New</span>
                                <ul class="shop-list">
                                    <li><a href="cart.html"><i class="fa-regular fa-cart-shopping"></i></a></li>
                                    <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                    <li><a href="#"><i class="fa-light fa-eye"></i></a></li>
                                </ul>
                            </div>
                            <div class="shop-content">
                                <span class="category">Levi’s Cotton</span>
                                <h3 class="title"><a href="shop-details.html">D’valo Office Cotton Suite</a></h3>
                                <div class="review-wrap">
                                    <ul class="review">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                    <span>(15 Reviews)</span>
                                </div>
                                <span class="price"> <span class="offer">$350.00</span>$257.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ./ fashion-section -->

    <section class="collect-section pb-100">
        <div class="container">
            <div class="row gy-lg-0 gy-4">
                <div class="col-lg-6">
                    <div class="collect-item">
                        <span>1500+ Products</span>
                        <h3 class="title">Women Collections</h3>
                        <p>This is genuinely the first theme i bought for which i did not had <br> to write one line of code.</p>
                        <ul class="collect-list">
                            <li><a href="shop.html">Blazer</a></li>
                            <li><a href="shop.html">Blouses & Shirts</a></li>
                            <li><a href="shop.html">Dresser</a></li>
                            <li><a href="shop.html">Jeans</a></li>
                            <li><a href="shop.html">Knits</a></li>
                            <li><a href="shop.html">Pants</a></li>
                            <li><a href="shop.html">Skirts</a></li>
                            <li><a href="shop.html">Suits</a></li>
                            <li><a href="shop.html">Sweatshirts & Hoodie</a></li>
                            <li><a href="shop.html">T-Shirts</a></li>
                            <li><a href="shop.html">Tops & Bodysuits</a></li>
                        </ul>
                        <div class="men"><img src="{{ asset('rosier/img/images/discount-1.png') }}" alt="discount"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="collect-items">
                        <div class="collect-item item-1">
                            <span>1500+ Products</span>
                            <h3 class="title">men Collections</h3>
                            <ul class="collect-list">
                                <li><a href="shop.html">Blazer</a></li>
                                <li><a href="shop.html">Blouses & Shirts</a></li>
                                <li><a href="shop.html">Dresser</a></li>
                                <li><a href="shop.html">Jeans</a></li>
                            </ul>
                            <div class="men"><img src="{{ asset('rosier/img/images/discount-2.png') }}" alt="discount"></div>
                        </div>
                        <div class="collect-item item-2">
                            <span>1500+ Products</span>
                            <h3 class="title">Top Accessories</h3>
                            <ul class="collect-list">
                                <li><a href="shop.html">Blazer</a></li>
                                <li><a href="shop.html">Blouses & Shirts</a></li>
                                <li><a href="shop.html">Dresser</a></li>
                                <li><a href="shop.html">Jeans</a></li>
                            </ul>
                            <div class="men"><img src="{{ asset('rosier/img/images/discount-3.png') }}" alt="discount"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ./ collect-section -->

    <section class="cta-section pt-100 pb-100" data-background="{{ asset('rosier/img/bg-img/cta-bg.jpg') }}">
        <div class="overlay"></div>
        <div class="container">
            <div class="cta-wrap text-center">
                <span>Spring summer 22 women’s collection</span>
                <h2 class="title">-15% Off Discount <br>All Here</h2>
                <a href="shop.html" class="rr-primary-btn cta-btn">View Collections</a>
            </div>
        </div>
    </section>
    <!-- ./ cta-section -->

    <div class="sponsor-section pt-100">
        <div class="container">
            <div class="row sponsor-wrap">
                @for ($i = 0; $i < 9; $i++)
                    <div class="sponsor-item bd-right bd-bottom">
                        <a href="#"><img src="{{ asset('rosier/img/sponsor/sponsor-1.png') }}" alt="img"></a>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <!-- ./ sponsor-section -->

    <section class="deal-section pt-100 pb-100">
        <div class="container">
            <div class="row deal-wrap align-items-center">
                <div class="shape"><img src="{{ asset('rosier/img/shapes/deal-shape.png') }}" alt="shape"></div>
                <div class="col-xl-5 col-lg-12">
                    <div class="deal-content">
                        <div class="section-heading mb-0">
                            <h2 class="section-title">Deal Of the days</h2>
                            <p>Elegant pink origami design three type of dimensional view and <br>decoration co Great for adding a decorative...</p>
                        </div>
                        <div class="deal-info">
                            <div class="icon">
                                <img src="{{ asset('rosier/img/icon/deal-icon.png') }}" alt="icon">
                            </div>
                            <div class="content">
                                <p>Limited Time offer. THe Deal will expire <br> one august 18, 2024 </p>
                            </div>
                        </div>
                        <a href="shop.html" class="rr-primary-btn deal-btn">View All Collections</a>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-12">
                    <div class="row gy-md-0 gy-4">
                        @for ($i = 0; $i < 1; $i++)
                            <div class="col-md-6">
                                <div class="shop-item deal-shop">
                                    <div class="shop-thumb">
                                        <div class="overlay"></div>
                                        <img src="{{ asset('rosier/img/shop/shop-5.jpg') }}" alt="shop">
                                        <span class="sale">New</span>
                                        <ul class="shop-list">
                                            <li><a href="#"><i class="fa-regular fa-cart-shopping"></i></a></li>
                                            <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                                            <li><a href="#"><i class="fa-light fa-eye"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <span class="category">Levi’s Cotton</span>
                                        <h3 class="title"><a href="shop-details.html">Monica Diara Party Dress</a></h3>
                                        <div class="review-wrap">
                                            <ul class="review">
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                            </ul>
                                            <span>(15 Reviews)</span>
                                        </div>
                                        <span class="price"> <span class="offer">$250.00</span>$157.00</span>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ./ deal-section -->

    <section class="blog-section pb-100">
        <div class="container">
            <div class="section-heading text-center">
                <h2 class="section-title mb-0">Our Latest News Insight</h2>
            </div>
            <div class="row gy-lg-0 gy-4 justify-content-center">
                @for ($i = 0; $i < 1; $i++)
                    <div class="col-lg-4 col-md-6">
                        <div class="post-card">
                            <div class="post-thumb">
                                <img src="{{ asset('rosier/img/blog/post-1.jpg') }}" alt="post">
                            </div>
                            <div class="post-content-wrap">
                                <div class="post-content">
                                    <ul class="post-meta">
                                        <li><i class="fa-sharp fa-solid fa-calendar-days"></i>3 Comment</li>
                                        <li><i class="fa-regular fa-tag"></i>oil Change</li>
                                    </ul>
                                    <h3 class="title"><a href="blog-details.html">Fashion Around the: Exploring Cultural Influences</a></h3>
                                </div>
                                <div class="post-bottom">
                                    <a href="blog-details.html" class="read-more">Read More<i class="fa-regular fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    @push('scripts')
        {{-- <script>
            var editor = "{{ $editor->count() }}"
        </script> --}}
    @endpush

@endsection
