<div>
    {{ $dataTable->table() }}

    @push('scripts')
        <script type="text/javascript">
            $(function() {
                window.LaravelDataTables = window.LaravelDataTables || {};
                window.LaravelDataTables["datatablesSimple"] = $("#datatablesSimple").DataTable({
                    columnDefs: [{
                        targets: 0,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    }],
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        "url": "datatable?class=Modules\\Admin\\DataTables\\CategoryzationMenuDatatables",
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
                        "data": "DT_RowIndex",
                        "name": "DT_RowIndex",
                        "title": "No",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "title",
                        "name": "title",
                        "title": "Title",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "action",
                        "name": "action",
                        "title": "Action",
                        "orderable": true,
                        "searchable": true
                    }],
                    "drawCallback": function(settings) {
                        if (window.livewire) {
                            window.livewire.rescan();
                        }
                    },
                    "order": [
                        [1, "desc"]
                    ],
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
    @endpush
</div>
