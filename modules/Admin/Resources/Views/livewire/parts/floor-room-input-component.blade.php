<tr>
	<td>
		<div wire:ignore>
			<select id="tool_choose" generate="{{$generate}}" wire:model="tool_choose.{{$generate}}" class="form-select tool_choose{{$generate}} select2" style="text-align: center;">
				<option value="">Pilih Alat</option>
				@foreach($tools as $index => $value)
					<option price="{{$value->price}}" value="{{$value->id}}">{{$value->name_asset}}</option>
				@endforeach 
			</select>
		</div>
	</td>
	<td>
		<b><input type="text" style="border: none; border-color: transparent; text-align:center; background-color:var(--bs-table-bg);" wire:model="buy.{{$generate}}" readonly></b>
	</td>
	<td>
		<input type="text" wire:model="form.harga.{{$generate}}" placeholder="Harga" class="form-control" />
	</td>
	<td>
		<input type="checbox" wire:model="items">
	</td>
</tr>