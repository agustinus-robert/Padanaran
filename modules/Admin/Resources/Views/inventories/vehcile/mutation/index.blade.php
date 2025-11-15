@extends('admin::layouts.default')

@section('title', 'Mutation Vehcile')

@section('navtitle', 'Mutation Vehcile')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                @livewire('admin::mutation-vehcile-component')
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $mutation_vehcile_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah Mutasi Kendaraan</div>
                </div>
                <div><i class="mdi mdi-bank mdi-48px text-light"></i></div>
            </div>
        </div>
    </div>
@endsection
