<div class="row mt-3 bg-light p-2">
	<div class="col-md-9">
	  <div class="form-group">
	    <label  class="form-label" for="exampleFormControlSelect1"><b>Label</b></label>
	    <input type="text" class="form-control" wire:model.live.debounce.250ms="arr.{{$rand}}.label" />
	  	
	  	<label class="form-label" for="exampleFormControlInput1"><span class="text-danger" id="arr.{{$rand}}.label"></span></label>
	  </div>
	</div>

	<div class="col-md-2">
		<div class="form-group">
	    	<label  class="form-label" for="exampleFormControlSelect1">Tipe</label>
	    	<select wire:change="type({{$rand}}, $event.target.value)" class="form-select">
	    		<option value="">Pilih Tipe</option>
	    		@foreach($formEnum as $key => $val)
	    			<option {{isset($arr[$rand]['type']) && $arr[$rand]['type'] == $val->value ? 'selected' : ''}} value="{{$val->value}}">{{$val->name}}</option>
	    		@endforeach
	    	</select>
	  	</div>
	</div>

	<div class="col-md-1">
		<div class="form-group">
			<label  class="form-label">Aksi</label>
			<button class="btn btn-danger" wire:click="erase({{$rand}})" class="erase"><span class="mdi mdi-trash-can-outline"></span></button>
		</div>
	</div>

	<div wire:loading wire:target="arr.{{$rand}}.label" class="text-center mt-3" style="position: absolute; margin: auto; max-height: 14px;">
	<img src="https://cdnjs.cloudflare.com/ajax/libs/galleriffic/2.0.1/css/loader.gif" />
	</div>
</div>
