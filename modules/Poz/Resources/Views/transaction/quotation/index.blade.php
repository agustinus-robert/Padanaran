@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Brand')

@section('navtitle', env('APP_NAME') . ' Brand')

@section('content')
    @php
        $arr = [
            'global' => false,
            'column' => $column,
            'ajax' => [
                'url' => route('poz::transaction.quotation-transaction.datatables'),
                'script' => 'function(d) { ajaxDataFunction(d); }',
            ],
            'parameters' => [
                'drawCallback' => 'function() { ajaxParam(); }',
            ],
            'title' => 'Daftar Penawaran',
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
    }

    function ajaxParam() {

    }
</script>
