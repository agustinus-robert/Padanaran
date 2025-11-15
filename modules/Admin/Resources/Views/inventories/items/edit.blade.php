@extends('asset::layouts.default')

@section('title', 'Aset | Ubah inventaris')

@section('navtitle', 'Ubah inventaris')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.items.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Ubah Inventaris</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk mengubah inventaris</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-plus"></i> Ubah inventaris {{ $item->name }}</div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('asset::inventories.items.update', ['item' => $item->id, 'next' => request('next', route('asset::inventories.items.index'))]) }}" method="POST" enctype="multipart/form-data"> @csrf @method('PUT')
                        @include('x-core::Company.Inventories.Items.edit')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
