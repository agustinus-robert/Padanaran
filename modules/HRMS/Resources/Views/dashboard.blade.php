@extends('layouts.horizontal-layout')

@section('title', 'Dasbor | ')

@section('navtitle', 'Dasbor')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('hrms::layouts.includes.navbar-hrms')
@endpush

@section('body-content')
    <div class="container-fluid">
        @include('components.navbar-admin')

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
