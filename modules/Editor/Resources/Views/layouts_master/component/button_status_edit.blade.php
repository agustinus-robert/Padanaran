<div class="btn-group dropdown no-arrow" role="group">
    <button id="btnGroupDrop1" class="btn btn-xs dropdown-toggle" data-bs-toggle="dropdown" title="Set Status" aria-expanded="false">
        <i class='mdi mdi-pencil-box-multiple-outline' aria-hidden="true"></i>
    </button>

    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        
        <?php 
        if(isset($sch_post->schedule_on) && empty($sch_post->deleted_at) && strtotime(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s')))) < strtotime(date('Y-m-d H:i', strtotime($sch_post->schedule_on.' '.$sch_post->timepicker)))){
        ?>
          <br />
           <a href="javascript:void(0)" class="dropdown-item" href="#"><i class='text-info far fa-hourglass'></i> Post soon published</a>
         
        <?php   
          } else { ?>

          <a id="set_publish" href="javascript:void(0)" data-status="publish" data-id="<?=$id?>" <?=($status == 2 ? 'style="display:none;"' : '')?> class="dropdown-item" href="#"><i class='mdi mdi-publish'></i> Publish</a>
          
          <a id="set_draft" href="javascript:void(0)" data-status="draft" data-id="<?=$id?>" <?=($status == 3 ? 'style="display:none;"' : '')?> class="dropdown-item" href="#"><i class='mdi mdi-download-box'></i> Draft</a>
        <?php    
        }
        ?>

        {{-- <hr class="bg-light"> --}}
        {{-- <a id="show_article" href="javascript:void(0)" data-id="<?=$id?>" data-bs-toggle="modal" data-bs-target="#detailModal" class="dropdown-item" href="#"><i class='far fa-eye text-info'></i> Detail</a> --}}
        {{-- <a href="{{url('log')}}/{{$id}}" target="_blank" class="dropdown-item"><i class='far fa-clipboard text-info'></i> History</a>  --}}
        <a id="show_sch" data-bs-toggle="modal" data-bs-target="#schModal" href="javascript:void(0)" data-id="<?=$id?>" class="dropdown-item" href="#"><i class='mdi mdi-invoice-text-clock'></i> Schedule</a> {{-- 
        <a href="{{url('comment_post')}}/{{$id}}" class="dropdown-item"><i class='far fa-comment text-info'></i> Comment</a> --}}  
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title text-white" id="exampleModalLabel"><i class="far fa-eye"></i> Detail Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div id="modal_show_detail"></div>
    </div>
  </div>
</div>


<div class="modal fade" id="schModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-large" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title text-white" id="exampleModalLabel"><i class="far fa-eye"></i> Schedule Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div id="modal_show"></div>
    </div>
  </div>
</div>