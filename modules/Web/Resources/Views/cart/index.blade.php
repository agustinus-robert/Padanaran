@extends('web::layouts.default2', [
    'useTransparentNavbar' => true,
])
@section('title', request()->bahasa == 'id' ? 'Keranjang Produk' : 'Keranjang Produk' . ' | ')

@section('main')
    <section class="page-header">
        <div class="shape"><img src="{{ asset('rosier/img/shapes/page-header-shape.png') }}" alt="shape"></div>
        <div class="container">
            <div class="page-header-content">
                <h1 class="title">Shop Grid</h1>
                <h4 class="sub-title">
                    <span class="home">
                        <a href="javascript:void(0)">
                            <span>Home</span>
                        </a>
                    </span>
                    <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                    <span class="inner">
                        <span>Shop Grid</span>
                    </span>
                </h4>
            </div>
        </div>
    </section>
    @livewire('web::products-commerces.cart-manage')

@endsection
