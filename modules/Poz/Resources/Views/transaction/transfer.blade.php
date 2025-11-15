@extends('poz::layout.adminlte.index')

@section('title', env('APP_NAME') . ' Transfer')

@section('navtitle', env('APP_NAME') . ' Transfer')

@section('header')
    <div class="app-content-top-area"> <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div></div>
                </div>
                <div class="col-md-6 text-end"> <a class="btn btn-primary" href="{{ route('poz::transaction.transfer.create') }}">Create Transfer</a> </div>
            </div>
        </div> <!--end::Container-->
    </div>

    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Transfer</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Transaction</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Transfer
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
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
        @livewire('poz::transaction.transfer', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::transaction.transfer.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
            ];
        @endphp

        @livewire('poz::datatables.custom-datatable', ['arr' => $arr])
    @endif

@endsection

<script>
    function ajaxDataFunction(d) {
        d.filterTitle = $("#kt_filter_search").val(); // Sesuaikan parameter yang ingin ditambahkan
        d.filterInstansi = $('#filter_instansi').val();
        d.filterJobs = $('#filter_jobs').val();
    }

    function ajaxParam() {

    }
</script>
