<div class="modal-body">
      
    <div class="row align-items-center">
        <input type="hidden" name="id" id="id" value="<?=(isset($sch_post->id) && !empty($sch_post->id) ? $sch_post->id : '')?>" />
        <div class="col-lg-12 text-center">
            <div class="form-group">
                <label><b>Date</b></label>
                

                <div class="input-group">
                    
                    {{-- <div class="input-group-prepend"> --}}
                      <span class="input-group-text" id="inputGroupPrepend"><i class='far fa-calendar-alt'></i></span>
                    {{-- </div> --}}

                    <?php 
                        if(isset($sch_post->schedule_on) && empty($sch_post->deleted_at) && strtotime(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s')))) < strtotime(date('Y-m-d H:i', strtotime($sch_post->schedule_on.' '.$sch_post->timepicker)))){
                            
                    ?>
                    
                    <input disabled type="text" value="<?=$sch_post->schedule_on?>" class="form-control" name="date" id="date" />

                <?php 
                    } else {
                ?>
                    <input type="text" class="form-control" name="date" id="date" />
                <?php } ?>
                    
                    <div class="invalid-feedback">
                      Please choose a username.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 text-center">
            <div class="form-group">
                
                <label class="mb-1" for="TEGA9"><b>Time</b></label>

                <div class="input-group">
                    
                    {{-- <div class="input-group-prepend"> --}}
                      <span class="input-group-text" id="inputGroupPrepend"><i class='far fa-clock'></i></span>
                    {{-- </div> --}}


                    <?php 
                        if(isset($sch_post->schedule_on) && empty($sch_post->deleted_at) && strtotime(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s')))) < strtotime(date('Y-m-d H:i', strtotime($sch_post->schedule_on.' '.$sch_post->timepicker)))){
                    ?>

                    <input disabled type="text" value="<?=$sch_post->timepicker?>" class="form-control" name="timepicker" id="timepicker" />

                <?php  

                } else { ?>

                    <input type="text" class="form-control" name="timepicker" id="timepicker" />

                <?php } ?>
                    
                    <div class="invalid-feedback">
                      Please choose a username.
                    </div>
                </div>

                

                
                
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
        <?php 
        if(isset($sch_post->schedule_on) && empty($sch_post->deleted_at) && strtotime(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s')))) < strtotime(date('Y-m-d H:i', strtotime($sch_post->schedule_on.' '.$sch_post->timepicker)))){
          
        ?>
     <button type="button" class="btn btn-danger" id="cancel_sch">Cancel Schedule</button>
      <?php 
      } else { ?>
        <button type="button" class="btn btn-primary" id="save_sch">Save Schedule</button>
      <?php } ?>

      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>

$(document).ready(function(){

  var dateObject = new Date();

  var spl_str = moment(dateObject).format("HH:mm")
  var mins_timen = moment.utc(spl_str,'HH:mm').add(10,'minutes').format('HH:mm')
  
   $('#timepicker').timepicker({
    format: 'HH:mm',
    minTime: mins_timen
   })


    $(document).on('change', '#date', function(){
        //var before_time = moment.utc($(this).val(),'HH:mm').add(60,'minutes').format('HH:mm')

        var dateObject = new Date();
        var year = dateObject.toLocaleString("default", { year: "numeric" });
        var month = dateObject.toLocaleString("default", { month: "2-digit" });
        var day = dateObject.toLocaleString("default", { day: "2-digit" });
        
        var time = dateObject.getHours() + ":" + dateObject.getMinutes()

        var nows = year+'/'+month+'/'+day

        var sp = $(this).val()
        var mins_time = ''
        
        if(nows == sp){
            var mins_time = moment.utc(time,'HH:mm').add(10,'minutes').format('HH:mm')
        }

        $('#timepicker').timepicker({
          format: 'HH:mm',
          minTime: mins_time
         })
    })



  $('#date').datetimepicker({
      formatDate: 'm/d/Y',
      format: 'Y/m/d',
      timepicker:false,
      minDate : new Date()
  })


    $(document).on('click', '#save_sch', function(){
        var vl_date = $('#date').val();
        var vl_time = $('#timepicker').val();
        var footer_response = '';
        
        var routing = '{{ route("admin::builder.post_schedule", ["posting" => $post_id]) }}';
       
        $.ajax({
            type: "POST",
            url: routing,
            headers: {
                "Authorization": "Bearer "+$("meta[name='oauth-token']").attr("content"),
            },
            data: {
                "_token": "{{ csrf_token() }}",
                'post_id' : "{{$post_id}}",
                'date' : vl_date,
                'time' : vl_time
            },
            success: function(response){
                $('#datatablesSimple').DataTable().ajax.reload();
                $('#schModal').modal('hide');
            }
        })
    })

    $(document).on('click', '#cancel_sch', function(){
        var routing = '{{ route("admin::builder.cancel_schedule", ["posting" => $post_id]) }}';
       

        $.ajax({
            type: "POST",
            url: routing,
            headers: {
                "Authorization": "Bearer "+$("meta[name='oauth-token']").attr("content"),
            },
            data: {
                "_token": "{{ csrf_token() }}",
                'id' : $('#id').val(),
            },
            success: function(response){
                $('#datatablesSimple').DataTable().ajax.reload();
                $('#schModal').modal('hide');
            }
        })
    })

 
})
</script>