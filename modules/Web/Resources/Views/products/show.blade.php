@extends('web::layouts.default', [
    'useTransparentNavbar' => true,
])
@section('title', request()->bahasa == 'id' ? 'Beranda' : 'Produk' . ' | ')

@section('main')


@endsection