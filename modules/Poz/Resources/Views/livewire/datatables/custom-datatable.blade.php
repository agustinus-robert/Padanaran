<style>
    .custom-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .page-title-box h4 {
        font-weight: 700;
        color: #495057;
        letter-spacing: -0.02em;
    }
    .btn-create {
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(85, 110, 230, 0.3);
    }
    .alert-custom {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    /* Solusi Overflow & Scrollbar */
    .table-responsive-custom {
        padding-bottom: 15px; /* Memberikan jarak antara tabel dan scrollbar */
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Mempercantik Scrollbar (Chrome, Safari, Edge) */
    .table-responsive-custom::-webkit-scrollbar {
        height: 8px; /* Tinggi scrollbar horizontal */
    }
    .table-responsive-custom::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .table-responsive-custom::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    .table-responsive-custom::-webkit-scrollbar-thumb:hover {
        background: #b1b1b1;
    }

    .breadcrumb-item a {
        color: #74788d;
        text-decoration: none;
    }
    .breadcrumb-item.active {
        color: #556ee6;
        font-weight: 600;
    }
    
    /* Mempercantik Table Header */
    #dataTableBuilder thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 700;
        border-bottom: 2px solid #eff2f7;
    }
</style>

<div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-white p-3 rounded shadow-sm">
                <h4 class="mb-sm-0 font-size-18 text-uppercase">
                    <i class="bx bx-layer me-2 text-primary"></i>{{ $title }}
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ ucfirst($menu) }}</a></li>
                        <li class="breadcrumb-item active">Daftar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if (Session::has('msg-sukses'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.duration.500ms>
                    <div class="alert alert-success alert-custom alert-dismissible fade show border-start border-success border-4" role="alert">
                        <i class="mdi mdi-check-circle-outline me-2 fs-4 align-middle"></i>
                        <strong>Berhasil!</strong> {{ Session::get('msg-sukses') }}
                        <button type="button" class="btn-close" @click="show = false"></button>
                    </div>
                </div>
            @endif

            @if (Session::has('msg-gagal'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.duration.500ms>
                    <div class="alert alert-danger alert-custom alert-dismissible fade show border-start border-danger border-4" role="alert">
                        <i class="mdi mdi-alert-octagon-outline me-2 fs-4 align-middle"></i>
                        <strong>Gagal!</strong> {{ Session::get('msg-gagal') }}
                        <button type="button" class="btn-close" @click="show = false"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card custom-card shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0 text-dark">Data {{ ucfirst($menu) }}</h5>
                            <p class="text-muted font-size-13 mb-0">Kelola dan lihat rincian data {{ $menu }} Anda.</p>
                        </div>

                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @if ($menu == 'reporting')
                                    <div style="min-width: 160px;">
                                        <select class="form-select border-light shadow-sm" id="report" name="report">
                                            <option value="all">📅 Filter Status</option>
                                            <option value="now">Hari Ini</option>
                                            <option value="yesterday">Kemarin</option>
                                            <option value="thisweek">Minggu Ini</option>
                                            <option value="thismonth">Bulan Ini</option>
                                            <option value="thisyear">Tahun Ini</option>
                                        </select>
                                    </div>
                                @endif

                                @php
                                    $routes = [
                                        'brand' => 'master.brand.create', 'category' => 'master.category.create',
                                        'unit' => 'master.unit.create', 'supplier' => 'master.supplier.create',
                                        'tax' => 'master.tax.create', 'product' => 'transaction.product.create',
                                        'sale' => 'transaction.sale.create', 'purchase' => 'transaction.purchase.create',
                                        'retur' => 'transaction.return.create', 'adjustment' => 'transaction.adjustment.create',
                                        'adjustment-supplier' => 'supplierz.adjustment.create', 'quotation' => 'supplierz.quotation.create',
                                    ];
                                    $labels = ['quotation' => 'Penawaran', 'retur' => 'Return'];
                                    $currentRoute = $routes[$menu] ?? null;
                                    $btnLabel = $labels[$menu] ?? ucfirst($menu);
                                    $isSupplierModule = in_array($menu, ['adjustment-supplier', 'quotation']);
                                @endphp

                                @if($currentRoute)
                                    <a class="btn btn-primary btn-create waves-effect waves-light shadow-sm" 
                                       href="{{ route('poz::' . $currentRoute, $isSupplierModule ? [] : ['outlet' => $outlet]) }}">
                                        <i class="bx bx-plus-circle me-1"></i> Tambah {{ $btnLabel }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive-custom">
                        {{ $html->table(['class' => 'table table-hover align-middle table-nowrap mb-0', 'id' => 'dataTableBuilder']) }}
                    </div>
                </div>

                @if ($tableArr['global'] == false)
                    {{ $html->scripts() }}
                @else
                    <script type="text/javascript">
                        $(function() {
                            window.LaravelDataTables = window.LaravelDataTables || {};
                            window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
                                "serverSide": true,
                                "processing": true,
                                "dom": '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
                                "buttons": [
                                    { extend: 'export', className: 'btn btn-light btn-sm text-primary border' },
                                    { extend: 'print', className: 'btn btn-light btn-sm text-primary border' },
                                    { extend: 'reload', className: 'btn btn-light btn-sm text-primary border' }
                                ],
                                "ajax": {
                                    "url": "datatable?class=Modules\\Admin\\DataTables\\CustomDatatables",
                                    "type": "GET",
                                    "data": function(data) {
                                        for (var i = 0, len = data.columns.length; i < len; i++) {
                                            if (!data.columns[i].search.value) delete data.columns[i].search;
                                            if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                                            if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                                            if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                                        }
                                        delete data.search.regex;
                                    }
                                },
                                "columns": [
                                    { "data": "id", "name": "id", "title": "ID", "className": "text-center fw-bold" },
                                    { "data": "content", "name": "content", "title": "Informasi" },
                                    { "data": "created_at", "name": "created_at", "title": "Tanggal Dibuat" }
                                ],
                                "language": {
                                    "search": "_INPUT_",
                                    "searchPlaceholder": "Cari data...",
                                    "paginate": { "previous": "<i class='mdi mdi-chevron-left'>", "next": "<i class='mdi mdi-chevron-right'>" }
                                },
                                "pagingType": "full_numbers",
                                "drawCallback": function() {
                                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                                    if (window.livewire) { window.livewire.rescan(); }
                                }
                            });
                        });
                    </script>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('keyup', '#globalSearch', function() {
            $('#dataTableBuilder').DataTable().draw();
        });

        $(document).on('change', '#report', function() {
            $('#dataTableBuilder').DataTable().ajax.reload();
        });
    });
</script>