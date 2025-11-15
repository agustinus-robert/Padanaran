<div>
	@if(isset($partnership_status->id) && $partnership_status->status == 1)
		<span class="btn btn-light-warning w-100">Menunggu Pesetujuan</span>
	@elseif(isset($partnership_status->id) && $partnership_status->status == 2)
		<span class="btn btn-light-primary w-100"><i class="mdi mdi-check-decagram"></i>PartnerShip</span>
    @elseif(isset($partnership_status->id) && $partnership_status->status == 3)
		<button wire:click="submission" class="btn btn-light-danger w-100">
	      <span class="btn-label">Partnership Dibatalkan</span>
	      <i class="ki-duotone ki-document btn-icon fs-2">
	        <span class="path1"></span>
	        <span class="path2"></span>
	      </i>
	    </button>
	@elseif(isset($partnership_status->id) && $partnership_status->status == 0)
		<button wire:click="submission" class="btn btn-light-danger w-100">
	      <span class="btn-label">Ditolak</span>
	      <i class="ki-duotone ki-document btn-icon fs-2">
	        <span class="path1"></span>
	        <span class="path2"></span>
	      </i>
	    </button>
	@else
		<button wire:click="submission" class="btn btn-bg-light btn-color-gray-500 btn-active-color-gray-900 text-nowrap w-100">
	      <span class="btn-label">Bukan Parnership</span>
	      <i class="ki-duotone ki-document btn-icon fs-2">
	        <span class="path1"></span>
	        <span class="path2"></span>
	      </i>
	    </button>
    @endif
</div>