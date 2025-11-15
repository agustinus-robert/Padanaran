@props(['useTransparentNavbar' => false, 'isLayoutLess' => false])

<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/styles.css') }}" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('pos-favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('pos-favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('pos-favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('pos-favicon/site.webmanifest') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css" integrity="sha512-/k658G6UsCvbkGRB3vPXpsPHgWeduJwiWGPCGS14IQw3xpr63AEMdA8nMYG2gmYkXitQxDTn6iiK/2fD4T87qA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>@yield('title') @yield('titleTemplate', config('app.settings.app_name', config('app.name', 'Laravel')))</title>
    @auth
        <meta name="oauth-token" content="{{ json_decode(Cookie::get(config('auth.cookie')))->access_token }}" />
    @endauth

    @stack('styles')
    <style>
        [x-cloak] {
            display: none;
        }

        .navbar {
            position: sticky;
            top: 0;
            /* Tempatkan navbar di atas halaman */
            z-index: 1000;
            /* Pastikan navbar berada di atas konten lainnya */
            background-color: #fff;
            /* Pastikan navbar memiliki latar belakang yang solid */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Bayangan ringan untuk memberi efek terangkat */
            width: 100%;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }
    </style>
</head>

@php($showSplash = env('SHOW_SPLASH_SCREEN', true))

<body>
    <div class="navbar">
        <div class="flex">
            <a class="btn btn-ghost text-xl">Shop</a>
        </div>

        <div class="flex flex-1 justify-center">
            <ul class="menu menu-horizontal px-1">
                <li><a href="{{ route('web::home.page') }}">Beranda</a></li>
                <li><a href="{{ route('web::about.page') }}">Tentang</a></li>
                {{-- <li><a href="{{ route('web::services.page') }}">Services</a></li> --}}
                <li><a href="{{ route('web::products.page') }}">Produk</a></li>
                <li><a href="{{ route('web::contact.page') }}">Kontak</a></li>
            </ul>
        </div>

        <div class="flex-none">
            @livewire('web::products-commerces.product-cart-temporary')

            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="avatar btn btn-circle btn-ghost">
                    <div class="w-10 rounded-full">
                        <img alt="Tailwind CSS Navbar component" src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                    </div>
                </div>
                <ul tabindex="0" class="menu dropdown-content menu-sm rounded-box bg-base-100 z-[1] mt-3 w-52 p-2 shadow">
                    <li>
                        <a class="justify-between">
                            Profile
                            <span class="badge">New</span>
                        </a>
                    </li>
                    <li><a>Settings</a></li>
                    <li><a>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    @yield('main')

    <footer class="footer bg-base-200 text-base-content p-10">
        <aside>
            <svg width="50" height="50" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" class="fill-current">
                <path
                    d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z">
                </path>
            </svg>
            <p>
                ACME Industries Ltd.
                <br />
                Providing reliable tech since 1992
            </p>
        </aside>
        <nav>
            <h6 class="footer-title">Services</h6>
            <a class="link-hover link">Branding</a>
            <a class="link-hover link">Design</a>
            <a class="link-hover link">Marketing</a>
            <a class="link-hover link">Advertisement</a>
        </nav>
        <nav>
            <h6 class="footer-title">Company</h6>
            <a class="link-hover link">About us</a>
            <a class="link-hover link">Contact</a>
            <a class="link-hover link">Jobs</a>
            <a class="link-hover link">Press kit</a>
        </nav>
        <nav>
            <h6 class="footer-title">Legal</h6>
            <a class="link-hover link">Terms of use</a>
            <a class="link-hover link">Privacy policy</a>
            <a class="link-hover link">Cookie policy</a>
        </nav>
    </footer>

    @livewireScripts

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />

    @stack('scripts')
</body>

</html>
