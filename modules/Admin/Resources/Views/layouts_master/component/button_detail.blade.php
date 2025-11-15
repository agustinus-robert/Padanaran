<?php if(isset($speaker_id)){ ?>
<button class="btn btn-info btn-xs ml-1 mb-1 btn_detail_modal" type="button" data-status="{{$speaker_id}}" data-id="{{ $id }}" title="Edit">
    <i class="fas fa-eye"></i>
</button>
<?php } else { ?>
<button class="btn btn-info btn-xs ml-1 mb-1 btn_detail_modal" type="button" data-id="{{ $id }}" title="Edit">
    <i class="fas fa-eye"></i>
</button>
<?php } ?>