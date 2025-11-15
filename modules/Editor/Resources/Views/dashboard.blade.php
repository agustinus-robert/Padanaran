@extends('poz::layout.index')

@section('title', 'Dashboard POS')

@section('navtitle', 'Dashboard POS')

@section('content')

    <div class="content">
        {{-- <h1 class="mt-4">Dashboard</h1> --}}
        {{-- <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="">Dashboard</a></li>
        </ol> --}}

        <div class="card mt-5 shadow">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary"><i class="fas fa-th-large me-1"></i> Selamat Datang, {{ Auth::user()->name }}</h6>
            </div>

            <div class="card-body">
                <h1>dashboard</h1>
                <p>Kelola data Anda dengan mudah dan efisien pada halaman admin untuk memastikan pengelolaan informasi yang lebih terstruktur, aman, dan terorganisir sesuai dengan kebutuhan bisnis atau sistem Anda.</p>
            </div>
        </div>

    @endsection
