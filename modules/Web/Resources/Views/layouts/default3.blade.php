<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <!-- Site Title -->
    <title>RMyCommerce</title>

    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('rosier/img/favicon.png') }}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('rosier/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rosier/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rosier/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rosier/css/odometer.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rosier/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('rosier/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rosier/css/main.css') }}">

    @stack('styles')
</head>

<body>
    <!-- header-area-start -->
    <header class="header sticky-active">
        <div class="top-bar">
            <div class="container">
                <div class="top-bar-inner">
                    <div class="top-bar-left">
                        <ul class="top-left-list">
                            <li><a href="about.html">About</a></li>
                            <li><a href="contact.html">My Account</a></li>
                            <li><a href="wishlist.html">Wishlist</a></li>
                            <li><a href="checkout.html">Checkout</a></li>
                        </ul>
                    </div>
                    <div class="top-bar-middle">
                        <span>Free shipping for all orders of 150$</span>
                    </div>
                    <div class="top-bar-right">
                        <ul class="top-right-list">
                            <li><a href="contact.html">Store Location</a></li>
                            <li>
                                <div class="nice-select select-control country" tabindex="0">
                                    <span class="current">Language</span>
                                    <ul class="list">
                                        <li data-value="" class="option selected focus">Language</li>
                                        <li data-value="vdt" class="option">English</li>
                                        <li data-value="can" class="option">Bangla</li>
                                        <li data-value="uk" class="option">Arabic</li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div class="nice-select select-control select-2 country" tabindex="0">
                                    <span class="current">Currency</span>
                                    <ul class="list">
                                        <li data-value="" class="option selected focus">Currency</li>
                                        <li data-value="vdt" class="option">Doller</li>
                                        <li data-value="can" class="option">Rupee</li>
                                        <li data-value="uk" class="option">Taka</li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-middle">
            <div class="container">
                <div class="header-middle-inner">
                    <div class="header-middle-left">
                        <div class="header-logo d-lg-block">
                            <a href="index.html">
                                <img src="{{ asset('rosier/img/logo/logo-1.png') }}" alt="Logo">
                            </a>
                        </div>
                        <div class="category-form-wrap">
                            <div class="nice-select select-control country" tabindex="0">
                                <span class="current">ALL Categories</span>
                                <ul class="list">
                                    <li data-value="" class="option selected focus">ALL Categories</li>
                                    <li data-value="vdt" class="option">Fashion</li>
                                    <li data-value="can" class="option">Organic</li>
                                    <li data-value="uk" class="option">Furniture</li>
                                </ul>
                            </div>
                            <form class="header-form" action="mail.php">
                                <input class="form-control" type="text" name="search" placeholder="Search here...">
                                <button class="submit rr-primary-btn">Search here</button>
                            </form>
                        </div>
                    </div>
                    <div class="header-middle-right">
                        <ul class="contact-item-list">
                            <li>
                                <div class="content">
                                    <span>Call Us Now:</span>
                                    <a class="number" href="tel:+25821592159">+(258) 2159-2159</a>
                                </div>
                                <a href="#" class="icon">
                                    <i class="fa-regular fa-phone"></i>
                                </a>
                            </li>
                            <li>
                                <a href="wishlist.html" class="icon">
                                    <i class="fa-sharp fa-regular fa-heart"></i>
                                </a>
                            </li>
                            <li>
                                <a href="cart.html" class="icon">
                                    <i class="fa-light fa-bag-shopping"></i>
                                    <span>2</span>
                                </a>
                                <div class="content">
                                    <span>Your cart,</span>
                                    <h5 class="number">$1280.00</h5>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="primary-header">
            <div class="container">
                <div class="primary-header-inner">
                    <div class="header-logo mobile-logo">
                        <a href="index.html">
                            <img src="{{ asset('rosier/img/logo/logo-1.png') }}" alt="Logo">
                        </a>
                    </div>
                    <div class="header-menu-wrap">
                        <div class="mobile-menu-items">
                            <ul>

                                <li>
                                    <a href="{{ route('web::home.page') }}">Home</a>
                                    {{-- class="menu-item-has-children active" <ul>
                                        <li><a href="index.html">Fashion Home</a></li>
                                        <li><a href="index-2.html">Grocery Home</a></li>
                                        <li><a href="index-3.html">Furniture</a></li>
                                    </ul> --}}
                                </li>
                                <li>
                                    <a href="{{ route('web::cart.page') }}">Shop</a>
                                    {{-- <ul>  class="menu-item-has-children"
                                        <li><a href="shop.html">Shop</a></li>
                                        <li><a href="shop-grid.html">Shop Grid</a></li>
                                        <li><a href="shop-details.html">Shop Details</a></li>
                                        <li><a href="cart.html">Cart</a></li>
                                        <li><a href="wishlist.html">Wishlist</a></li>
                                        <li><a href="checkout.html">Checkout</a></li>
                                    </ul> --}}
                                </li>
                                <li><a href="{{ route('web::contact.page') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.header-menu-wrap -->
                    <div class="header-right-wrap">
                        <div class="header-right">
                            <span>Get 30% Discount Now <span>Sale</span></span>
                            <div class="header-right-item">
                                <a href="javascript:void(0)" class="mobile-side-menu-toggle"><i class="fa-sharp fa-solid fa-bars"></i></a>
                            </div>
                        </div>
                        <!-- /.header-right -->
                    </div>
                </div>
                <!-- /.primary-header-inner -->
            </div>
        </div>
    </header>
    <!-- /.Main Header -->

    <div id="popup-search-box">
        <div class="box-inner-wrap d-flex align-items-center">
            <form id="form" action="#" method="get" role="search">
                <input id="popup-search" type="text" name="search" placeholder="Type keywords here...">
            </form>
            <div class="search-close"><i class="fa-sharp fa-regular fa-xmark"></i></div>
        </div>
    </div>
    <!-- /#popup-search-box -->

    @yield('main')
    <!-- ./ blog-section -->

    <footer class="footer-section bg-grey pt-60">
        <div class="container">
            <div class="footer-items">
                <div class="footer-item">
                    <div class="icon">
                        <img src="{{ asset('rosier/img/icon/footer-1.png') }}" alt="icon">
                    </div>
                    <div class="content">
                        <h4 class="title">Free Shipping</h4>
                        <span>Free shipping on orders over $65</span>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="icon">
                        <img src="{{ asset('rosier/img/icon/footer-2.png') }}" alt="icon">
                    </div>
                    <div class="content">
                        <h4 class="title">Free Returns</h4>
                        <span>30-days free return polic</span>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="icon">
                        <img src="{{ asset('rosier/img/icon/footer-3.png') }}" alt="icon">
                    </div>
                    <div class="content">
                        <h4 class="title">Secured Payments</h4>
                        <span>We accept all major credit card</span>
                    </div>
                </div>
                <div class="footer-item item-2">
                    <div class="icon">
                        <img src="{{ asset('rosier/img/icon/footer-4.png') }}" alt="icon">
                    </div>
                    <div class="content">
                        <h4 class="title">Customer Service</h4>
                        <span>Top notch customer service</span>
                    </div>
                </div>
            </div>
            <div class="row footer-widget-wrap pb-60">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <div class="widget-header">
                            <h3 class="widget-title">About Store</h3>
                        </div>
                        <div class="footer-contact">
                            <div class="icon"><i class="fa-sharp fa-solid fa-phone-rotary"></i></div>
                            <div class="content">
                                <span>Have Question? Call Us 24/7</span>
                                <a href="tel:+25836922569">+258 3692 2569</a>
                            </div>
                        </div>
                        <ul class="schedule-list">
                            <li><span>Monday - Friday:</span>8:00am - 6:00pm</li>
                            <li><span>Saturday:</span>8:00am - 6:00pm</li>
                            <li><span>Sunday</span> Service Close</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <div class="widget-header">
                            <h3 class="widget-title">Our Stores</h3>
                        </div>
                        <ul class="footer-list">
                            <li><a href="contact.html">New York</a></li>
                            <li><a href="contact.html">London SF</a></li>
                            <li><a href="contact.html">Los Angele</a></li>
                            <li><a href="contact.html">Chicago</a></li>
                            <li><a href="contact.html">Las Vegas</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <div class="widget-header">
                            <h3 class="widget-title">Shop Categories</h3>
                        </div>
                        <ul class="footer-list">
                            <li><a href="shop-grid.html">New Arrivals</a></li>
                            <li><a href="shop-grid.html">Best Selling</a></li>
                            <li><a href="shop-grid.html">Vegetables</a></li>
                            <li><a href="shop-grid.html">Fresh Meat</a></li>
                            <li><a href="shop-grid.html">Fresh Seafood</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <div class="widget-header">
                            <h3 class="widget-title">Useful Links</h3>
                        </div>
                        <ul class="footer-list">
                            <li><a href="contact.html">Privacy Policy</a></li>
                            <li><a href="contact.html">Terms & Conditions</a></li>
                            <li><a href="contact.html">Contact Us</a></li>
                            <li><a href="blog-grid.html">Latest News</a></li>
                            <li><a href="contact.html">Our Sitemaps</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <div class="widget-header">
                            <h3 class="widget-title">Our Newsletter</h3>
                        </div>
                        <div class="news-form-wrap">
                            <p class="mb-20">Subscribe to the mailing list to receive updates one the new arrivals and other discounts</p>
                            <div class="footer-form mb-20">
                                <form action="#" class="rr-subscribe-form">
                                    <input class="form-control" type="email" name="email" placeholder="Email address">
                                    <input type="hidden" name="action" value="mailchimpsubscribe">
                                    <button class="submit">Subscribe</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                            <p class="mb-0">I would like to receive news and special offer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row copyright-content">
                    <div class="col-lg-6">
                        <div class="footer-img-wrap">
                            <span>Payment System:</span>
                            <div class="footer-img"><a href="#"><img src="{{ asset('rosier/img/images/footer-img-1.png') }}" alt="img"></a></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p>Copyright & Design 2024 <span>©Roiser</span>. All Right Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- ./ footer-section -->

    <div id="scroll-percentage"><span id="scroll-percentage-value"></span></div>
    <!--scrollup-->
    @stack('scripts')
    <!-- JS here -->
    <script src="{{ asset('rosier/js/vendor/jquary-3.6.0.min.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/bootstrap-bundle.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/imagesloaded-pkgd.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/waypoints.min.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/venobox.min.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/odometer.min.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/meanmenu.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/smooth-scroll.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/jquery.isotope.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/countdown.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/swiper.min.js') }}"></script>
    <script src="{{ asset('rosier/js/vendor/nice-select.js') }}"></script>
    <script src="{{ asset('rosier/js/ajax-form.js') }}"></script>
    <script src="{{ asset('rosier/js/contact.js') }}"></script>
    <script src="{{ asset('rosier/js/main.js') }}"></script>
</body>

</html>
