@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Adjustment')

@section('navtitle', env('APP_NAME') . ' Adjustment')

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('poz::transaction.adjustment', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::transaction.adjustment.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
                'title' => 'Daftar Adjustment',
                'menu' => 'adjustment',
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
