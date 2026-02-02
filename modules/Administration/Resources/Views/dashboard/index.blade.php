@extends('layouts.horizontal-layout')

@section('title', 'Dasbor - ')

@section('titleTemplate', config('account.admin.name'))

@section('navtitle', 'Dashboard')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dasbor</li>
@endsection

@push('nav')
    @include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')
    @if(config('theme.default') == 'material')
        @include('layouts.component.material-admin-top-nav')
    @endif

    <div class="container-fluid">
        <div class="row">
            @if(config('theme.default') == 'material')
                @include('layouts.component.material-admin-dashboard-global')
            @elseif(config('theme.default') == 'skote')
                @include('layouts.component.skote-admin-header-global')
                @include('layouts.component.skote-admin-dashboard-global')
            @endif
        </div>
    </div>
@endsection
