@extends('poz::layout.index_supplier')

@section('title', env('APP_NAME') . ' Brand')

@section('navtitle', env('APP_NAME') . ' Brand')

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('poz::supplierz.quotation-supplier', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::supplierz.quotation.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
                'title' => 'Daftar Penawaran',
                'menu' => 'quotation',
            ];
        @endphp

        @livewire('poz::datatables.custom-datatable', ['arr' => $arr])
    @endif

@endsection

<script>
    function ajaxDataFunction(d) {
        d.filterTitle = $("#kt_filter_search").val();
        d.filterInstansi = $('#filter_instansi').val();
        d.filterJobs = $('#filter_jobs').val();
        d.search = $('#globalSearch').val();
        d.filter = $('#filter').attr('data-order');
        d.outlet = new URLSearchParams(window.location.search).get('outlet') || "{{ auth()->user()->current_outlet_id }}";
    }

    function ajaxParam() {

    }
</script>
