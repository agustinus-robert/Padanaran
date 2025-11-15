@extends('asset::layouts.default')

@section('title', 'Aset | Tambah peminjaman inventaris')

@section('navtitle', 'Tambah peminjaman inventaris')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.lease.manages.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Tambah peminjaman inventaris</h2>
                    <div class="text-secondary">Silakan isi formulir di bawah ini untuk menambah peminjaman inventaris</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-plus"></i> Tambah peminjaman inventaris</div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('asset::inventories.lease.manages.store', ['next' => request('next', route('asset::inventories.lease.manages.index'))]) }}" method="POST" enctype="multipart/form-data"> @csrf
                        @include('asset::lease.manages.includes.form')
                        <hr class="text-secondary my-4">
                        <div class="row">
                            <div class="col-auto">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-content-save-outline"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
