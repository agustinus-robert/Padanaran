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

<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm position-relative overflow-hidden" 
                style="border-radius: 16px; background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);">
                
                <div class="position-absolute end-0 bottom-0 opacity-1 d-none d-lg-block" style="transform: translate(10%, 10%);">
                    <i class="mdi mdi-mosque text-primary" style="font-size: 12rem;"></i>
                </div>

                <div class="card-body p-4 p-lg-5 position-relative">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <h5 class="text-primary fw-bold mb-1">E-Boarding Management</h5>
                            <h1 class="display-6 fw-bold text-dark mb-2">
                                Assalamu'alaikum, {{ \Str::before(auth()->user()->name, ' ') }}!
                            </h1>
                            <p class="text-muted mb-4 fs-6">
                                Senang melihat Anda kembali. Pantau aktivitas santri dan asrama di <span class="fw-bold text-dark">Digi-Boarding</span> hari ini.
                            </p>
                            
                            <div class="d-flex gap-3">
                                <div class="bg-white px-3 py-2 rounded-3 shadow-xs border d-flex align-items-center gap-2">
                                    <div class="bg-soft-primary p-2 rounded-circle">
                                        <i class="mdi mdi-calendar-check text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Hari ini</small>
                                        <span class="fw-bold small">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="sticky-top" style="top: 20px;">
                @include('account::includes.account-info')
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>

@endsection
