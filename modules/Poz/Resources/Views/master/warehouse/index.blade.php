@extends('poz::layout.adminlte.index')

@section('title', env('APP_NAME') . ' Warehouse')

@section('navtitle', env('APP_NAME') . ' Warehouse')

@section('header')
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div></div>
            </div>
            <div class="col-md-6 text-end"> <a class="btn btn-primary" href="{{ route('poz::master.warehouse.create') }}">Create Warehouse</a> </div>
        </div>
    </div>


    <div class="content">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Warehouse</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Reference</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Warehouse
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')

    @if (Session::has('msg-sukses'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
            <div class="alert alert-success">
                {{ Session::get('msg-sukses') }}
            </div>
        </div>
    @endif

    @if (Session::has('msg-gagal'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
            <div class="alert alert-danger">
                {{ Session::get('msg-gagal') }}
            </div>
        </div>
    @endif

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('poz::master.warehouse', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::master.warehouse.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
            ];
        @endphp

        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">List Of Warehouse</div>
            </div> <!--end::Header--> <!--begin::Body-->
            <div class="card-body">
                @livewire('poz::datatables.custom-datatable', ['arr' => $arr])
            </div> <!--end::Body-->
        </div>
    @endif

@endsection

<script>
    function ajaxDataFunction(d) {
        d.filterTitle = $("#kt_filter_search").val(); // Sesuaikan parameter yang ingin ditambahkan
    }

    function ajaxParam() {

    }
</script>
