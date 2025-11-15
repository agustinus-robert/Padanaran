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
                window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["building-land-room-table"]=$("#building-land-room-table").DataTable({"serverSide":true,"processing":true,"ajax":{"url":"datatable?class=Modules\\Admin\\DataTables\\BuildingsLandsRoomDatatables","type":"GET","data":function(data) {
                    for (var i = 0, len = data.columns.length; i < len; i++) {
                        if (!data.columns[i].search.value) delete data.columns[i].search;
                        if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                        if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                        if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                    }
                    delete data.search.regex;

                    myData.floor_id = "{{$building_floor}}"
                    myData.sell_id = "{{$sell_id}}"
                    return $.extend(data, myData)
                }},"columns":[{"data":"DT_RowIndex","name":"DT_RowIndex","title":"No","orderable":true,"searchable":true},{"data":"name","name":"name","title":"Name","orderable":true,"searchable":true},{"data":"forheit_num","name":"forheit_num","title":"Jumlah Keterlambatan (Hari)","orderable":true,"searchable":true},{"data":"eod_lend","name":"eod_lend","title":"Tanggal Akhir Peminjaman","orderable":true,"searchable":true},{"data":"forheit","name":"forheit","title":"Denda","orderable":true,"searchable":true},{"data":"action","name":"action","title":"Action","orderable":true,"searchable":true}],"drawCallback":function(settings) {
                    if (window.livewire) {
                        window.livewire.rescan();
                    }
                },"order":[[1,"desc"]],"buttons":[{"extend":"create"},{"extend":"export"},{"extend":"print"},{"extend":"reset"},{"extend":"reload"}],"paging":true,"searching":true,"info":false,"searchDelay":350});
            }

            loadData(myData)

                $('#pagelist').change(function(){

                var start = $('#pagelist').find(':selected').data('start');

                var length = $('#pagelist').find(':selected').data('length');

                myData.start = start
                myData.length = length

                loadData(myData)
                
                var page_number = parseInt($('#pagelist').val());

                var test_table = $('#building-land-room-table').dataTable();

                test_table.fnPageChange(page_number);

            })
        });</script>
    @endpush
</div>