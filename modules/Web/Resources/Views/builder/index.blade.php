@extends('web::layouts.builder', [
    'useTransparentNavbar' => true,
])

@section('title', request()->bahasa == 'id' ? 'Beranda' : 'Produk' . ' | ')

@section('main')

<div class="row">
    <div class="col-md-3">
        @include('web::builder.component.tools.side-tools')
    </div>

    <div class="col-md-6">
        <div id="workspace">
            <h4>Your Page</h4>
            <div class="page" id="page-zone">
                Halaman anda!
            </div>
        </div>
    </div>

    <div class="col-md-3">
        @include('web::builder.component.tools.properties-tool')
    </div>
</div>

@endsection
