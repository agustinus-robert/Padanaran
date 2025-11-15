@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Unit')

@section('navtitle', env('APP_NAME') . ' Unit')

@section('header')
    <div class="content">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Unit</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Reference</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Unit
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('poz::master.unit', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::master.unit.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
                'title' => 'Daftar Unit',
                'menu' => 'unit',
            ];
        @endphp

        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
            <div class="card-body">
                @livewire('poz::datatables.custom-datatable', ['arr' => $arr])
            </div> <!--end::Body-->
        </div>
    @endif

@endsection

<script>
    function ajaxDataFunction(d) {
        d.filterTitle = $("#kt_filter_search").val();
        d.search = $('#globalSearch').val();
        d.filter = $('#filter').attr('data-order');
        d.outlet = new URLSearchParams(window.location.search).get('outlet') || "{{ auth()->user()->current_outlet_id }}";
    }

    function ajaxParam() {

    }
</script>
