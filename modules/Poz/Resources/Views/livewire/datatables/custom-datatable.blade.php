<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $menu }}</a></li>
                        <li class="breadcrumb-item active">Daftar {{ $menu }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
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

        @if ($menu == 'reporting')
            <div class="col-xxl-2 col-lg-6">
                <select class="form-select" id="report" name="report" aria-label="Default select example">
                    <option value="all">Status</option>
                    <option value="now">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="thisweek">Minggu Ini</option>
                    <option value="thismonth">Bulan Ini</option>
                    <option value="thisyear">Tahun Ini</option>
                </select>
            </div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-body border-bottom">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0"></h5>
                        <div class="flex-shrink-0">
                            @if ($menu == 'brand')
                                <a class="btn btn-primary" href="{{ route('poz::master.brand.create') }}?outlet={{ $outlet }}">Buat Brand</a>
                            @elseif($menu == 'category')
                                <a class="btn btn-primary" href="{{ route('poz::master.category.create') }}?outlet={{ $outlet }}">Buat Kategori</a>
                            @elseif($menu == 'unit')
                                <a class="btn btn-primary" href="{{ route('poz::master.unit.create') }}?outlet={{ $outlet }}">Buat Unit</a>
                            @elseif($menu == 'supplier')
                                <a class="btn btn-primary" href="{{ route('poz::master.supplier.create') }}?outlet={{ $outlet }}">Buat Supplier</a>
                            @elseif($menu == 'tax')
                                <a class="btn btn-primary" href="{{ route('poz::master.tax.create') }}?outlet={{ $outlet }}">Buat Tax</a>
                            @elseif($menu == 'product')
                                <a class="btn btn-primary" href="{{ route('poz::transaction.product.create') }}?outlet={{ $outlet }}">Buat Product</a>
                            @elseif($menu == 'sale')
                                <a class="btn btn-primary" href="{{ route('poz::transaction.sale.create') }}?outlet={{ $outlet }}">Buat Sale</a>
                            @elseif($menu == 'purchase')
                                <a class="btn btn-primary" href="{{ route('poz::transaction.purchase.create') }}?outlet={{ $outlet }}">Buat Purchase</a>
                            @elseif($menu == 'retur')
                                <a class="btn btn-primary" href="{{ route('poz::transaction.return.create') }}?outlet={{ $outlet }}">Buat Return</a>
                            @elseif($menu == 'adjustment')
                                <a class="btn btn-primary" href="{{ route('poz::transaction.adjustment.create') }}?outlet={{ $outlet }}">Buat Adjustment</a>
                            @elseif($menu == 'adjustment-supplier')
                                <a class="btn btn-primary" href="{{ route('poz::supplierz.adjustment.create') }}">Buat Adjustment</a>
                            @elseif($menu == 'quotation')
                                <a class="btn btn-primary" href="{{ route('poz::supplierz.quotation.create') }}">Buat Penawaran</a>
                            @endif
                        </div>
                    </div>
                </div>

                {{ $html->table() }}

                @if ($tableArr['global'] == false)
                    {{ $html->scripts() }}
                @else
                    <script type="text/javascript">
                        $(function() {
                            window.LaravelDataTables = window.LaravelDataTables || {};
                            window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
                                "serverSide": true,
                                "processing": true,
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
                                "columns": [{
                                    "data": "id",
                                    "name": "id",
                                    "title": "Id",
                                    "orderable": true,
                                    "searchable": true
                                }, {
                                    "data": "content",
                                    "name": "content",
                                    "title": "Title",
                                    "orderable": true,
                                    "searchable": true
                                }, {
                                    "data": "created_at",
                                    "name": "created_at",
                                    "title": "Created At",
                                    "orderable": true,
                                    "searchable": true
                                }],
                                "drawCallback": function(settings) {

                                    if (window.livewire) {


                                        window.livewire.rescan();


                                    }
                                },
                                "buttons": [{
                                    "extend": "create"
                                }, {
                                    "extend": "export"
                                }, {
                                    "extend": "print"
                                }, {
                                    "extend": "reset"
                                }, {
                                    "extend": "reload"
                                }],
                                "paging": true,
                                "searching": true,
                                "info": false,
                                "searchDelay": 350
                            });
                        });
                    </script>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(document).on('keyup', '#globalSearch', function() {
            $('#dataTableBuilder').DataTable().draw();
        });


        $(document).on('change', '#report', function() {
            $('#dataTableBuilder').DataTable().ajax.reload();
        })

        $(document).on('click', '.filter-option', function() {
            let order = $(this).data('order');
            $('#filter').attr('data-order', order);
            $('#dataTableBuilder').DataTable().draw();
        });

        $('#dataTableBuilder thead').addClass('table-light'); // biru dan teks putih
    </script>
</div>
</div>
