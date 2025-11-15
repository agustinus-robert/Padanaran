@extends('asset::layouts.default')

@section('title', 'Aset | Ubah pengajuan')

@section('navtitle', 'Ubah pengajuan')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.proposals.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
    <div class="ms-4">
        <h2 class="mb-1">Ubah pengajuan</h2>
        <div class="text-secondary">Silakan isi formulir di bawah ini untuk menambah pengajuan</div>
    </div>
</div>
<div class="card border-0">
    <div class="card-body"><i class="mdi mdi-plus"></i> Ubah pengajuan</div>
    <div class="card-body border-top">
        <form class="form-block" action="{{ route('asset::inventories.proposals.update', ['proposal' => $proposal->id, 'next' => request('next', route('asset::inventories.proposals.index'))]) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
            @include('x-core::Company.Inventories.Proposals.edit')
        </form>
    </div>
</div>
@endsection

