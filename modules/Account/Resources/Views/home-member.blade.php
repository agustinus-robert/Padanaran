@extends('admin::layouts.admin')

@extends('account::layouts.components.navbar-user')

@section('title', 'Posting Image')

@section('navtitle', 'Posting Image')

@section('content')
	

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="">Dashboard</a></li>
    </ol>

    <div class="card shadow mb-4">
        <div class="card-header">
          <h6 class="font-weight-bold text-primary"><i class="fas fa-th-large me-1"></i> User Dashboard</h6>
        </div>

        <div class="card-body">
            <h1>Halaman dashboard</h1>
            <p>Ini merupakan halaman User Dashboard</p>
        </div>
    </div>

@endsection