@extends('admin::layouts.default')

@section('title', 'Tool')

@section('navtitle', 'Tool')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                @livewire('admin::tool-component')
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $tool_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah Peralatan</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('admin::inventories.tool.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat Peralatan yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
