@extends('web::layouts.default', [
    'useTransparentNavbar' => $useTransparentNavbar ?? false,
])
@section('main')
    @if (isset($headerTitle))
        <header class="flex flex-col gap-4 items-center justify-center py-32"
            style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/rand-3.jpg');">
            <h1 class="text-white font-bold text-4xl uppercase">
                {{ $headerTitle }}
            </h1>
            <span class="text-white font-semibold"><a href="" class="hover:underline">{{request()->bahasa == 'id' ? 'Beranda' : 'Home'}}</a>
                /
                <a href="" class="hover:underline">{{request()->bahasa == 'id' ? 'Tentang' : 'About'}}</a> / <span
                    class="text-primary-500">{{ $headerTitle }}</span>
            </span>
        </header>
    @endif
    @yield('content')
    @include('web::partials.contact-us-section')
    @include('web::partials.map-section')
@endsection
