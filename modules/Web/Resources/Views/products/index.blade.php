@extends('web::layouts.default2', [
    'useTransparentNavbar' => true,
])
@section('title', request()->bahasa == 'id' ? 'Beranda' : 'Produk' . ' | ')

@section('main')

    <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Nikmati berbelanja di <span>Toko Kami</span></h1>
        </div><!-- End .container -->
    </div>


    @livewire('web::products-commerces.product-manage')


@endsection
