<div>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$title}}</h3>
            <div class="block-options">
                <div class="dropdown">
                <button type="button" class="btn btn-alt-secondary" id="filter" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filter
                    <i class="fa fa-angle-down ms-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-ecom-filters" style="">
                    <a class="dropdown-item d-flex align-items-center justify-content-between filter-option" data-order="new" href="javascript:void(0)">
                    Terbaru <i class="fa fa-sort-asc"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between filter-option" data-order="old" href="javascript:void(0)">
                    Terlama <i class="fa fa-sort-desc"></i>
                    </a>
                </div>
                </div>
            </div>
        </div>

        <div class="block-content bg-body-dark">
            <form action="#" method="POST" onsubmit="return false;">
                <div class="mb-4">
                    <input type="text" class="form-control form-control-alt" id="globalSearch" placeholder="Silahkan lakukan pencarian ..">
                </div>
            </form>
        </div>

        <div class="block-content">
            {{ $html->table() }}

            @if($tableArr['global'] == false)
                {{$html->scripts()}}
            @else
                <script type="text/javascript">$(function(){window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["dataTableBuilder"]=$("#dataTableBuilder").DataTable({"serverSide":true,"processing":true,"ajax":{"url":"datatable?class=Modules\\Admin\\DataTables\\CustomDatatables","type":"GET","data":function(data) {
                        for (var i = 0, len = data.columns.length; i < len; i++) {

                            if (!data.columns[i].search.value) delete data.columns[i].search;

                            if (data.columns[i].searchable === true) delete data.columns[i].searchable;


                            if (data.columns[i].orderable === true) delete data.columns[i].orderable;


                            if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;


                        }


                        delete data.search.regex;}},"columns":[{"data":"id","name":"id","title":"Id","orderable":true,"searchable":true},{"data":"content","name":"content","title":"Title","orderable":true,"searchable":true},{"data":"created_at","name":"created_at","title":"Created At","orderable":true,"searchable":true}],"drawCallback":function(settings) {

                        if (window.livewire) {


                            window.livewire.rescan();


                        }
                    },"buttons":[{"extend":"create"},{"extend":"export"},{"extend":"print"},{"extend":"reset"},{"extend":"reload"}],"paging":true,"searching":true,"info":false,"searchDelay":350});});</script>
            @endif
        </div>
    </div>

    <script>
         $(document).on('keyup', '#globalSearch', function () {
            $('#dataTableBuilder').DataTable().draw();
        });

        $(document).on('click', '.filter-option', function () {
            let order = $(this).data('order');
            $('#filter').attr('data-order', order);
            $('#dataTableBuilder').DataTable().draw();
        });
    </script>
</div>
