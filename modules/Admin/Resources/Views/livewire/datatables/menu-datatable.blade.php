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
                        "url": "datatable?class=Modules\\Admin\\DataTables\\MenuDatatables",
                        "type": "GET",
                        "data": function(data) {
                            console.log(data)
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
                        "data": 'DT_RowIndex',
                        "name": 'DT_RowIndex',
                        "orderable": false,
                        "visible": false,
                        "searchable": false
                    }, {
                        "data": "title",
                        "name": "title",
                        "title": "Title",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "slug",
                        "name": "slug",
                        "title": "Slug",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "parent",
                        "name": "parent",
                        "title": "Parent",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "created_at",
                        "name": "created_at",
                        "title": "Created At",
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
                        var api = this.api();
                        var rows = api.rows({
                            page: 'current'
                        }).nodes();
                        var last = null;

                        api.column(3, {
                            page: 'current'
                        }).data().each(function(group, i) {
                            //  console.log(group)

                            if (last !== group) {

                                if (group == 'parent kosong') {
                                    $(rows).eq(i).before(
                                        '<tr class="bg-info text-white" class="group"><td colspan="5">&nbsp;<i class="fa fa-arrow-down" aria-hidden="true"></i> ' + 'Empty <b><i>Parent Menu</i></b>' + '</b></i></td></tr>'
                                    );
                                } else if (group == 'child kosong') {
                                    $(rows).eq(i).before(
                                        '<tr class="bg-light-warning text-black" class="group"><td colspan="5">&nbsp;<i class="fa fa-arrow-down" aria-hidden="true"></i> ' + 'Unrecognized sub menu: <b><i>' + 'Not have parent menu' + '</b></i></td></tr>'
                                    );
                                } else {
                                    $(rows).eq(i).before(
                                        '<tr class="bg-primary text-white" class="group"><td colspan="5">&nbsp;<i class="fa fa-arrow-down" aria-hidden="true"></i> List of Menu ' + '<b><i>' + group + '</b></i></td></tr>'
                                    );


                                }

                                last = group;
                            }
                        });
                    },
                    "createdRow": function(row, data, index) {
                        if (data.action == 'parent_menu') {

                            window.LaravelDataTables["datatablesSimple"].rows($(row)).remove();
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
