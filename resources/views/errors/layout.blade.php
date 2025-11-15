@extends('layouts.vertical')

@section('title')
    @yield('subtitle')
@endsection

@section('bodyclass', 'min-vh-100 d-flex align-items-center bg-light')

@section('main')
    <div class="position-absolute w-100 text-muted text-center" style="bottom: 20px;">
        <small>{{ config('app.name') }}</small>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-8 col-md-10">
                <div class="text-center">
                    <div class="mb-4">
                        <div class="display-1">@yield('emoticon')</div>
                        <h1 class="fw-bold mb-3">@yield('header')</h1>
                        <div class="lead text-muted">@yield('message')</div>
                    </div>
                    <a class="btn btn-soft-danger me-2" href="{{ url()->previous() }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                    <a class="btn btn-soft-secondary text-dark" href="{{ url()->previous() }}">Halaman utama</a>
                </div>
            </div>
        </div>
    </div>
@endsection
