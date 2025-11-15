@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Penjualan')

@section('navtitle', env('APP_NAME') . ' Penjualan')

@section('header')
    <div class="app-content-top-area"> <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div></div>
                </div>
                <div class="col-md-6 text-end"> <a class="btn btn-primary" href="{{ route('poz::transaction.sale.create') }}">Create Sell</a> </div>
            </div>
        </div> <!--end::Container-->
    </div>

    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Sale</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Transaction</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Sale
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
@endsection

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('poz::transaction.sale', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::transaction.sale.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
                'title' => 'Daftar Penjualan',
                'menu' => 'sale',
            ];
        @endphp

        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
            <div class="card-body">
                @livewire('poz::datatables.custom-datatable', ['arr' => $arr])
            </div>
        </div>
    @endif

@endsection

<script>
    function ajaxDataFunction(d) {
        d.filterTitle = $("#kt_filter_search").val(); // Sesuaikan parameter yang ingin ditambahkan
        d.filterInstansi = $('#filter_instansi').val();
        d.filterJobs = $('#filter_jobs').val();
        d.search = $('#globalSearch').val();
        d.filter = $('#filter').attr('data-order');
        d.outlet = new URLSearchParams(window.location.search).get('outlet') || "{{ auth()->user()->current_outlet_id }}";
    }

    function ajaxParam() {

    }
</script>
