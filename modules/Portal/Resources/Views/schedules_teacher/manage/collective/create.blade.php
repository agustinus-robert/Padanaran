@extends('portal::layouts.index')

@section('title', 'Jadwal kerja | ')
@section('container-type', 'container-fluid px-5')

 <header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('skote/images/logo.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('skote/images/logo-dark.png') }}" alt="" height="17">
                    </span>
                </a>

                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('skote/images/logo-light.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('skote/images/logo-light.png') }}" alt="" height="39">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm font-size-16 d-lg-none header-item waves-effect waves-light px-3" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>

        <div class="d-flex">
            @include('portal::layouts.components.notifications')
            
            @include('layouts.shortcut_menu')

            @include('layouts.nav_name')
            
        </div>
</header>

@push('style')
    <style>
        table th,
        table td {
            border: 1px solid rgba(153, 153, 153, 0.5);
            padding: 0.5rem;
        }

        thead {
            background-color: #e9ecef;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('contents')
    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                <div class="navbar-collapse collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link arrow-none" href="{{ route('portal::dashboard-msdm.index') }}" id="topnav-dashboard" role="button">
                                <i class="bx bx-home-circle me-2"></i><span key="t-dashboards">Dashboards</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @if (Session::has('msg-sukses'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                        <div class="alert alert-success">
                            {{ Session::get('msg-sukses') }}
                        </div>
                    </div>
                @endif
                @livewire('portal::presence.collective-presence')
            </div>
        </div>
    </div>
@endsection
