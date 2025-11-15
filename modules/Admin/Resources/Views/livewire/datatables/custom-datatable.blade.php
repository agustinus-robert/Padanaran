<div>
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
