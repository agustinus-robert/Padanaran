@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Kategori')

@section('navtitle', env('APP_NAME') . ' Kategori')

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('poz::master.category', ['action' => $action])
    @else
        @php
            $arr = [
                'global' => false,
                'column' => $column,
                'ajax' => [
                    'url' => route('poz::master.category.datatables'),
                    'script' => 'function(d) { ajaxDataFunction(d); }',
                ],
                'parameters' => [
                    'drawCallback' => 'function() { ajaxParam(); }',
                ],
                'title' => 'Daftar Kategori',
                'menu' => 'category',
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
