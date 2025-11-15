@extends('admin::layouts.default')

@section('title', 'Aset | Tambah pengajuan')

@section('navtitle', 'Tambah pengajuan')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('admin::inventories.procurements.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Buat pengajuan</h2>
            <div class="text-secondary">Silakan isi formulir di bawah ini untuk menambah pengajuan</div>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body"><i class="mdi mdi-plus"></i> Tambah pengajuan</div>
        <div class="card-body border-top">
            <form class="form-block" action="{{ route('admin::inventories.procurements.store', ['next' => request('next', route('admin::inventories.procurements.index'))]) }}" method="POST" enctype="multipart/form-data">@csrf
                {{-- @include('x-core::Company.Inventories.Proposals.create') --}}
            </form>
        </div>
    </div>
@endsection
