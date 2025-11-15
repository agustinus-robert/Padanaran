<tr>
	<td>
		<div wire:ignore>
			<select id="vehcile_choose" generate="{{$generate}}" wire:model="vehcile_choose.{{$generate}}" class="form-select vehcile_choose{{$generate}} select2" style="text-align: center;">
				<option value="">Pilih Kendaraan</option>
				<option value="1">Kendaraan 1</option>
				<option value="2">Kendaraan 2</option>
			</select>
		</div>
	</td>
	<td>
		<input type="text" wire:model="form.harga.{{$generate}}" placeholder="Harga" class="form-control" />
	</td>
	<td>
		<button type="button" class="btn btn-danger disabledVechile{{$generate}}" wire:click="$dispatch('deleteItems', {id: {{$generate}} })"><span class="mdi mdi-trash-can"></span></button>
	</td>
</tr>