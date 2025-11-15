<div>
    <div class="mt-4 container">
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Page</span>
                    </div>
                    <select name="pagelist" id="pagelist" class="form-control"></select>
                    <div class="input-group-append">
                        <span class="input-group-text">of&nbsp;<span id="totalpages"></span></span>
                    </div>
                </div>
            </div>
        </div>

        {{ $dataTable->table() }}
    </div>


    @push('scripts')
        <script type="text/javascript">$(function(){
              var myData = {};

            function loadData(myData){
            window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["mutation-tool-table"]=$("#mutation-tool-table").DataTable({"serverSide":true,"processing":true,"ajax":{"url":"datatable?class=Modules\\Admin\\DataTables\\MutationToolDatatables","type":"GET","data":function(data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (!data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                delete data.search.regex;
                return $.extend(data, myData)
                delete data.search.regex;}},"columns":[{"data":"DT_RowIndex","name":"DT_RowIndex","title":"No","orderable":true,"searchable":true},{"data":"file_sk","name":"file_sk","title":"File Sk","orderable":true,"searchable":true},{"data":"detail","name":"detail","title":"Detail","orderable":true,"searchable":true},{"data":"date_mutation","name":"date_mutation","title":"Date Mutation","orderable":true,"searchable":true},{"data":"action","name":"action","title":"Action","orderable":true,"searchable":true, "render": function (data, type, row) {
                                    var trash = "{{$trash}}"
                                    if(trash == 1){
                                        return '<button class="btn btn-sm btn-info" wire:click="$dispatch(\'mutation-tool-restore\', { id: '+row.id+'})"><i class="fa fa-refresh" aria-hidden="true"></i></button>'
                                    } else {
                                        if(row.is_return != 1){
                                            return '<button title="ubah" class="btn btn-sm btn-warning" wire:click="$dispatch(\'mutation-tool-edit\', { id: '+row.id+'})"><i class="fa fa-pencil" aria-hidden="true"></i></button>'+
                                            '<button title="pengembalian" class="btn btn-sm btn-info" wire:click="$dispatch(\'mutation-tool-retur\', { id: '+row.id+'})"><i class="fa fa-share"></i></button>'; 
                                        } else {
                                            return 'Barang sudah dikembalikan'
                                        }  
                                    }
                                    
                                }
                }],"drawCallback":function(settings) {
                    var page_info = window.LaravelDataTables["mutation-tool-table"].page.info();
                      $('#totalpages').text(page_info.pages);
                      var html = '';
                      var start = 0;
                      var length = page_info.length;
                      for(var count = 1; count <= page_info.pages; count++) {         
                          var page_number = count - 1;
                          html += '<option value="'+page_number+'" data-start="'+start+'" data-length="'+length+'">'+count+'</option>';
                          start = start + page_info.length;
                      }
                      $('#pagelist').html(html);
                      $('#pagelist').val(page_info.page);
            },"order":[[1,"desc"]],"buttons":[{"extend":"create"},{"extend":"export"},{"extend":"print"},{"extend":"reset"},{"extend":"reload"}],"paging":true,"searching":true,"info":false,"searchDelay":350});}

            loadData(myData)

                $('#pagelist').change(function(){
                var start = $('#pagelist').find(':selected').data('start');
                var length = $('#pagelist').find(':selected').data('length');
                myData.start = start
                myData.length = length
                loadData(myData)
                var page_number = parseInt($('#pagelist').val());
                var test_table = $('#tool-table').dataTable();
                test_table.fnPageChange(page_number);
            })

        });</script>

    @endpush
</div>