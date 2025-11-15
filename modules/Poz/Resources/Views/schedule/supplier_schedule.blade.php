@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Supplier')

@section('navtitle', env('APP_NAME') . ' Supplier')

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('poz::master.brand', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::schedule.schedule_supplier.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
                'title' => 'Daftar Shift Supplier',
                'menu' => 'Shift Supplier',
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
