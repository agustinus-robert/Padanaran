@extends('finance::layouts.default')

@section('title', 'Dasbor | ')

@section('navtitle', 'Dasbor')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between">
                        <div>
                            <img class="w-100" src="{{ asset('img/manypixels/Welcome.svg') }}" alt="" style="height: 140px;">
                        </div>
                        <div class="order-md-first text-md-start text-center">
                            <div class="px-4 py-3">
                                <h2 class="fw-normal">Selamat datang {{ Auth::user()->name }}!</h2>
                                <div class="text-muted">di {{ config('modules.finance.name') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4"></div>
    </div>
@endsection
