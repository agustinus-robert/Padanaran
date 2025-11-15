<form style="display:inline-block;" method="POST" action="{{ url($delete) }}">
  {{ method_field('DELETE') }}
  {{ csrf_field() }}
    <button class="btn btn-sm btn-danger" type="submit" title="Hapus">
        <i class="fa fa-trash"></i> Hapus
    </button>
</form>
