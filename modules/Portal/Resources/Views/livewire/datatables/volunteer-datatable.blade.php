<div>
    {{ $dataTable->table(['class' => 'table table-bordered'], true) }}

    @push('scripts')
        <script type="text/javascript">
            $(function(){window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["datatablesSimple"]=$("#datatablesSimple").DataTable({"serverSide":true,"processing":true,"ajax":{"url":"datatable?class=Modules\\Portal\\DataTables\\VolunteerDatatables","type":"GET",
            "headers": {
              'Authorization': 'Bearer ' + localStorage.getItem('XSRF-TOKEN') // Ganti dengan cara Anda menyimpan token
            },"data":function(data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (!data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                delete data.search.regex;
                data.id_user = '{!! $id !!}'
            }},"columns":[{"data":"DT_RowIndex","name":"DT_RowIndex","title":"No","orderable":true,"searchable":true},{"data":"name_event","name":"name_event","title":"Name Event","orderable":true,"searchable":true},{"data":"start_date","name":"start_date","title":"Start Date","orderable":true,"searchable":true},{"data":"end_date","name":"end_date","title":"End Date","orderable":true,"searchable":true}],"drawCallback":function(settings) {
                if (window.livewire) {
                    window.livewire.rescan();
                }
            },"order":[[1,"desc"]],"buttons":[{"extend":"create"},{"extend":"export"},{"extend":"print"},{"extend":"reset"},{"extend":"reload"}],"paging":true,"searching":true,"info":false,"searchDelay":350});});</script>
    @endpush
</div>