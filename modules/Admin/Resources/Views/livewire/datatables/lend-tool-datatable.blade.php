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
            window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["lend-tool-table"]=$("#lend-tool-table").DataTable({"serverSide":true,"processing":true,"ajax":{"url":"datatable?class=Modules\\Admin\\DataTables\\LendToolDatatables","type":"GET","data":function(data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (!data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                delete data.search.regex;
               
                return $.extend(data, myData)
                delete data.search.regex;}},"columns":[{"data":"DT_RowIndex","name":"DT_RowIndex","title":"No","orderable":true,"searchable":true},{"data":"invoice","name":"invoice","title":"Invoice","orderable":true,"searchable":true},{"data":"date_sk","name":"date_sk","title":"Tanggal SK","orderable":true,"searchable":true},{"data":"file_sk","name":"file_sk","title":"Dokumen SK","orderable":true,"searchable":true},{"data":"action","name":"action","title":"Action","orderable":true,"searchable":true}],"drawCallback":function(settings) {
                    var page_info = window.LaravelDataTables["lend-tool-table"].page.info();
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