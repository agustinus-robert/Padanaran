@extends('admin::layouts.default')

@section('title', 'Tool Lend')

@section('navtitle', 'Tool Lend')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                @livewire('admin::tool-lend-component')
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $lend_tool_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah Peminjaman Peralatan</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
        </div>
    </div>
@endsection
