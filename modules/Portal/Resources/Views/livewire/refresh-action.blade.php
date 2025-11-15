<div>
	@if(isset($this->content_end_date['id']['post1']) && strtotime(date('Y-m-d')) > strtotime(date('Y-m-d', strtotime($this->content_end_date['id']['post1']))))
		<button disable class="btn btn-danger">Pendaftaran Ditutup</button>
	@else
		@if(isset($events[$even]))
			<button wire:click="choose_event({{$even}})" class="btn btn-danger">Batalkan</button>
		@else
			<button wire:click="choose_event({{$even}})" class="btn btn-primary">Daftar</button>
		@endif
	@endif
</div>