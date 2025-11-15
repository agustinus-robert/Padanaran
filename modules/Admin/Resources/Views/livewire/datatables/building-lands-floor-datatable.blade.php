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
{{-- 
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModal" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);" >
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peminjaman Per Lantai</h5>
                <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
                <form wire:submit.prevent="save">
                  <div class="modal-body">
                    <div class="mb-3">
                        <label>Harga Total</label>
                        <input type="text" class="form-control" wire:model="form.harga" />
                    </div>

                    <div class="mb-3">
                        <label>Denda</label>
                        <input type="text" class="form-control" wire:model="form.forheit" />
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
                </form>
            </div>
          </div>
        </div> --}}

        {{ $dataTable->table() }}
    </div>


    @push('scripts')
    @script
        <script type="text/javascript">$(function(){

            var myData = {};
            function loadData(myData){
            window.LaravelDataTables=window.LaravelDataTables||{};window.LaravelDataTables["building-land-floor-table"]=$("#building-land-floor-table").DataTable({"serverSide":true,"processing":true,"ajax":{"url":"datatable?class=Modules\\Admin\\DataTables\\BuildingsLandsFloorDatatables","type":"GET","data":function(data) {
                for (var i = 0, len = data.columns.length; i < len; i++) {
                    if (!data.columns[i].search.value) delete data.columns[i].search;
                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                }
                delete data.search.regex;
                myData.building_transaction_id = "{{$building_transaction_id}}"
                myData.building_id = "{{$sell_id}}"
                return $.extend(data, myData)
            }},"columns":[{"data":"DT_RowIndex","name":"DT_RowIndex","title":"No","orderable":true,"searchable":true},{"data":"name","name":"name","title":"Name","orderable":true,"searchable":true},{"data":"forheit_num","name":"forheit_num","title":"Jumlah Keterlambatan (Hari)","orderable":true,"searchable":true},{"data":"eod_lend","name":"eod_lend","title":"Tanggal Akhir Peminjaman","orderable":true,"searchable":true},{"data":"forheit","name":"forheit","title":"Denda","orderable":true,"searchable":true},{"data":"action","name":"action","title":"Action","orderable":true,"searchable":true}],"drawCallback":function(settings) {
                 var page_info = window.LaravelDataTables["building-land-floor-table"].page.info();
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

                var test_table = $('#building-land-floor-table').dataTable();

                test_table.fnPageChange(page_number);

            })
        });</script>
        @endscript
    @endpush
</div>