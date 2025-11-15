@extends('poz::layout.adminlte.index')

@section('title', env('APP_NAME') . ' Brand')

@section('navtitle', env('APP_NAME') . ' Brand')

@section('header')
    <div class="content"> <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Casier</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Reference</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Casier
                    </li>
                </ol>
            </div>
        </div> <!--end::Row-->
    </div> <!--end::Container-->
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
        @livewire('poz::master.casier', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::master.casier.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
                'menu' => 'casier',
            ];
        @endphp

        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">List Of Casier</div>
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
        d.filterInstansi = $('#filter_instansi').val();
        d.filterJobs = $('#filter_jobs').val();
    }

    function ajaxParam() {

    }
</script>
