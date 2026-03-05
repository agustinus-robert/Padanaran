@extends('layouts.horizontal-layout')

@section('title', 'Dasbor - ')

@section('titleTemplate', config('account.admin.name'))

@section('navtitle', 'Dashboard')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item">Board</li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('nav')
@include('boarding::layouts.includes.navbar-boarding')
@endpush

@section('body-content')

    @if(config('theme.default') == 'material')
        @include('layouts.component.material-admin-top-nav')
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="jumbotron bg-light p-2">
                    <h2>Assalamu'alaikum {{ auth()->user()->name }}!</h2>
                    <p class="text-muted">Selamat datang di Digi-Boarding</p>
                </div>
            </div>
            <div class="col-md-4">
                @include('account::includes.account-info')
            </div>
        </div>
    </div>
@endsection
