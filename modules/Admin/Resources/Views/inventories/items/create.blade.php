@extends('asset::layouts.default')

@section('title', 'Aset | Tambah inventaris')

@section('navtitle', 'Tambah inventaris')

@section('content')
<div class="row justify-content-center">
    <div class="col-xxl-8 col-xl-10">
        <div class="d-flex align-items-center mb-4">
            <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.items.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
            <div class="ms-4">
                <h2 class="mb-1">Tambah inventaris</h2>
                <div class="text-secondary">Silakan isi formulir di bawah ini untuk menambah inventaris</div>
            </div>
        </div>
        <div class="card border-0">
            <div class="card-body"><i class="mdi mdi-plus"></i> Tambah inventaris</div>
            <div class="card-body border-top">
                <form class="form-block" action="{{ route('asset::inventories.items.store', ['next' => request('next', route('asset::inventories.items.index'))]) }}" method="POST" enctype="multipart/form-data"> @csrf
                    @include('x-core::Company.Inventories.Items.create')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
