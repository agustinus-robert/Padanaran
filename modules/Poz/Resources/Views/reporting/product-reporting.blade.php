@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Laporan Produk')

@section('navtitle', env('APP_NAME') . ' Laporan Produk')


@section('content')
    @php
        $arr = [
            'global' => false,
            'column' => $column,
            'ajax' => [
                'url' => route('poz::reporting.reporting.product.datatables'),
                'script' => 'function(d) { ajaxDataFunction(d); }',
            ],
            'parameters' => [
                'drawCallback' => 'function() { ajaxParam(); }',
            ],
            'menu' => 'reporting',
            'title' => 'Laporan Produk',
        ];
    @endphp

    @livewire('poz::datatables.custom-datatable', ['arr' => $arr])
@endsection

<script>
    function ajaxDataFunction(d) {
        d.filterTitle = $("#kt_filter_search").val();
        d.filterInstansi = $('#filter_instansi').val();
        d.filterJobs = $('#filter_jobs').val();
        d.search = $('#globalSearch').val();
        d.filter = $('#filter').attr('data-order');
        d.outlet = new URLSearchParams(window.location.search).get('outlet') || "{{ auth()->user()->current_outlet_id }}";
        d.report = $('#report').val()
    }

    function ajaxParam() {

    }
</script>
