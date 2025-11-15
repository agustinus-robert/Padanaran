@extends('poz::layout.index_supplier')

@section('title', env('APP_NAME') . ' Laporan Produk')

@section('navtitle', env('APP_NAME') . ' Laporan Produk')


@section('content')

    <div class="mb-3">
        <button class="btn btn-outline-primary shift-btn" data-shift="morning">Pagi</button>
        <button class="btn btn-outline-warning shift-btn" data-shift="afternoon">Siang</button>
        <button class="btn btn-outline-success shift-btn" data-shift="evening">Sore</button>
    </div>

    @php
        $arr = [
            'global' => false,
            'column' => $column,
            'ajax' => [
                'url' => route('poz::supplierz.reporting.reporting.product.datatables'),
                'script' => 'function(d) { ajaxDataFunction(d); }',
            ],
            'parameters' => [
                'drawCallback' => 'function() { ajaxParam(); }',
            ],
            'menu' => 'reporting',
            'title' => 'Data Supplier Produk',
        ];
    @endphp

    @livewire('poz::datatables.custom-datatable', ['arr' => $arr])
@endsection

@push('scripts')
    <script>
        let selectedShift = ''; 

        $(document).ready(function () {
            $('.shift-btn').on('click', function () {
                selectedShift = $(this).data('shift');

                $('.shift-btn').removeClass('active');
                $(this).addClass('active');

                $('#dataTableBuilder').DataTable().ajax.reload();
            });
        });

        function ajaxDataFunction(d) {
            d.filterTitle = $("#kt_filter_search").val();
            d.filterInstansi = $('#filter_instansi').val();
            d.filterJobs = $('#filter_jobs').val();
            d.search = $('#globalSearch').val();
            d.filter = $('#filter').attr('data-order');
            d.outlet = new URLSearchParams(window.location.search).get('outlet') || "{{ auth()->user()->current_outlet_id }}";
            d.report = $('#report').val();

            d.shift = selectedShift;
        }

        function ajaxParam() {
            // Optional: fungsi ini bisa kamu pakai kalau ingin update UI setelah datatable selesai load
        }
    </script>
@endpush
