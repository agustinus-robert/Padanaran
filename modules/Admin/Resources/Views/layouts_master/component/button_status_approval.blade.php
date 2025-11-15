@if($status == 2)
<a href="{{url('admin/custom/partnership_id/'.$approve_id.'/status/3')}}" title="Batalkan Status">
    <i class="mdi mdi-cancel mdi-24px"></i>
</a>
@else
<a href="{{url('admin/custom/partnership_id/'.$approve_id.'/status/2')}}" title="Terima Status">
    <i class="mdi mdi-check-decagram mdi-24px"></i>
</a>
@endif