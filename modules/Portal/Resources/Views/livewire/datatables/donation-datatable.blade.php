<div>
    {{ $dataTable->table() }}

    @push('scripts')
        <script type="text/javascript">$(function(){window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["datatablesSimple"]=$("#datatablesSimple").DataTable({"serverSide":true,"processing":true,"ajax":{"url":"datatable?class=Modules\\Portal\\DataTables\\DonationDatatables","type":"GET","data":function(data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (!data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                data.email = '{!! $email !!}'
                delete data.search.regex;}},"columns":[{"data":"DT_RowIndex","name":"DT_RowIndex","title":"No","orderable":true,"searchable":true},{"data":"invoice","name":"invoice","title":"Invoice","orderable":true,"searchable":true},{"data":"status","name":"status","title":"Status","orderable":true,"searchable":true},{"data":"action","name":"action","title":"Action","orderable":true,"searchable":true}],"drawCallback":function(settings) {
                if (window.livewire) {
                    window.livewire.rescan();
                }
            },"order":[[1,"desc"]],"buttons":[{"extend":"create"},{"extend":"export"},{"extend":"print"},{"extend":"reset"},{"extend":"reload"}],"paging":true,"searching":true,"info":false,"searchDelay":350});});</script>

    @endpush
</div>