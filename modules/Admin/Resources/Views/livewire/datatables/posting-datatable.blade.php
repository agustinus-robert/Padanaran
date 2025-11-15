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
                        "url": "datatable?class=Modules\\Admin\\DataTables\\PostingDatatables",
                        "type": "GET",
                        "data": function(data) {

                            for (var i = 0, len = data.columns.length; i < len; i++) {
                                if (!data.columns[i].search.value) delete data.columns[i].search;
                                if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                                if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                                if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                            }
                            data.user = "{{ $user_id }}"
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
                        "data": "slug",
                        "name": "slug",
                        "title": "Slug",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "created_at",
                        "name": "created_at",
                        "title": "Created At",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "status",
                        "name": "status",
                        "title": "Status",
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

        <script type="text/javascript">
            $(document).on('click', '#set_publish', function() {
                Swal.fire({
                        title: "Set Publish?",
                        text: "Publish this post?",
                        icon: "info",
                        showCancelButton: true,
                        showCloseButton: true,
                        focusConfirm: false,
                        focusCancel: false,
                        allowOutsideClick: false,
                        reverseButtons: true,
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            var routing = '{{ route('admin::builder.publish', ':posting') }}';
                            routing = routing.replace(':posting', $(this).data('id'));

                            $.ajax({
                                type: "GET",
                                url: routing,
                                headers: {
                                    "Authorization": "Bearer " + $("meta[name='oauth-token']").attr("content"),
                                },
                                success: function(response) {
                                    var json = JSON.parse(response)
                                    if (json.status == true) {
                                        Swal.fire({
                                            title: 'Publish!',
                                            text: 'Post was publish',
                                            icon: 'success',
                                            showConfirmButton: false,
                                            focusConfirm: false,
                                            focusCancel: false,
                                            allowOutsideClick: false,
                                            timer: 1500,
                                            showLoaderOnConfirm: true,
                                        })

                                        window.LaravelDataTables["datatablesSimple"].ajax.reload();
                                    } else {
                                        Swal.fire({
                                            title: 'Not Publish!',
                                            text: 'Post not Publish',
                                            icon: 'danger',
                                            showConfirmButton: false,
                                            focusConfirm: false,
                                            focusCancel: false,
                                            allowOutsideClick: false,
                                            timer: 1500,
                                            showLoaderOnConfirm: true,
                                        })


                                        window.LaravelDataTables["datatablesSimple"].ajax.reload();
                                    }
                                }
                            })
                        }
                    })

            })

            $(document).on('click', '#set_draft', function() {
                Swal.fire({
                        title: "Set Draft?",
                        text: "Draft this post?",
                        icon: "info",
                        showCancelButton: true,
                        showCloseButton: true,
                        focusConfirm: false,
                        focusCancel: false,
                        allowOutsideClick: false,
                        reverseButtons: true,
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            var routing = '{{ route('admin::builder.draft', ':posting') }}';
                            routing = routing.replace(':posting', $(this).data('id'));

                            $.ajax({
                                type: "GET",
                                url: routing,
                                headers: {
                                    "Authorization": "Bearer " + $("meta[name='oauth-token']").attr("content"),
                                },
                                success: function(response) {
                                    var json = JSON.parse(response)
                                    if (json.status == true) {
                                        Swal.fire({
                                            title: 'Drafted!',
                                            text: 'Post was draft',
                                            icon: 'success',
                                            showConfirmButton: false,
                                            focusConfirm: false,
                                            focusCancel: false,
                                            allowOutsideClick: false,
                                            timer: 1500,
                                            showLoaderOnConfirm: true,
                                        })

                                        window.LaravelDataTables["datatablesSimple"].ajax.reload();
                                    } else {
                                        Swal.fire({
                                            title: 'Not Drafted!',
                                            text: 'Post not draft',
                                            icon: 'danger',
                                            showConfirmButton: false,
                                            focusConfirm: false,
                                            focusCancel: false,
                                            allowOutsideClick: false,
                                            timer: 1500,
                                            showLoaderOnConfirm: true,
                                        })

                                        window.LaravelDataTables["datatablesSimple"].ajax.reload();
                                    }
                                }
                            })
                        }
                    })

            })

            $(document).on('click', '#show_sch', function() {
                var footer_response = '';
                var routing = '{{ route('admin::builder.view_schedule', ':posting') }}';
                routing = routing.replace(':posting', $(this).data('id'));

                $.ajax({
                    type: "GET",
                    url: routing,
                    headers: {
                        "Authorization": "Bearer " + $("meta[name='oauth-token']").attr("content"),
                    },
                    data: {
                        'id': $(this).data('id')
                    },
                    success: function(response) {
                        $('#modal_show').html(response)
                        //$('#sch_footer').html(footer_response)
                    }
                });
            })
        </script>
    @endpush
</div>
