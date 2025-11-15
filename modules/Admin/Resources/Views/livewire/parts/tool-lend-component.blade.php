<?php 
if(isset($is_edit) && $is_edit == true){ ?>
	<?php 
	foreach($tool_item_mutation as $index => $valuedt){ 
		?>
		<tr>
			<td>
				<div>
					<select id="tool_choose" class="form-select select2" style="text-align: center;" disabled>
						<option value="">Pilih Alat</option>
						@foreach($tools as $index => $value)
							<option {!! $value->id == $valuedt->tool_id ? 'selected' : '' !!} price="{{$value->price}}" value="{{$value->id}}">{{$value->name_asset}}</option>
						@endforeach 
					</select>
				</div>
			</td>
			<td>
				<input id="unit_from" type="text" placeholder="Unit Awal" class="form-control" value="{{\Modules\Admin\Models\Units::find(\Modules\Admin\Models\Tool::find($valuedt->unit_from)->code_unit)->name}}" readonly />
			</td>
			<td>
				<input id="unit_qty_from" type="text" placeholder="Jumlah Unit Awal" class="form-control" readonly value="{{$valuedt->qty_src}}" />
			</td>
			<td>
				<input type="text" id="qty" placeholder="Jumlah" class="form-control qty" value="{{$valuedt->qty_mutation}}" disabled />
			</td>
			<td>
				<div>
					<select disabled id="unit_to_choose" class="form-select unit_to_choose select2" style="text-align: center;">
						<option value="">Pilih Unit</option>
						@foreach($unitMutation as $indexunit => $valueunit)
						<option {{$valueunit['id'] == $valuedt->unit_to ? 'selected' : ''}} value="{{$valueunit['id']}}">{{$valueunit['name']}}</option>
						@endforeach
						{{-- @foreach($unit as $index => $value)
							<option value="{{$value->id}}">{{$value->name}}</option>
						@endforeach  --}}
					</select>
				</div>
			</td>
			<td>
				<button disabled type="button" class="btn btn-danger disabledTool"><span class="mdi mdi-trash-can"></span></button>
			</td>
		</tr>
	<?php } ?>
<?php } else { ?>
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
		<input id="unit_from" type="text" placeholder="Unit Awal" class="form-control"  wire:model="form.unit_from_data.{{$generate}}" readonly />
	</td>
	<td>
		<input id="unit_qty_from" type="text" placeholder="Jumlah Unit Awal" class="form-control"  wire:model="form.qty_from.{{$generate}}" readonly />
	</td>
	<td>
		<input type="text" id="qty{{$generate}}" placeholder="Jumlah" wire:change="check_choose($event.target.value, {{$generate}}, 'qty')"  wire:model="form.qty.{{$generate}}" class="form-control qty{{$generate}}"/>
	</td>
	<td>
		<div>
			<input type="date" class="form-control" wire:model="form.start_date.{{$generate}}" />
		</div>
	</td>
	<td>
		<div>
			<input type="date" class="form-control" wire:model="form.end_date.{{$generate}}" />
		</div>
	</td>
	<td>
		<div>
			<input type="text"  class="form-control forheit{{$generate}}" wire:model="form.forheit.{{$generate}}" />
		</div>
	</td>
	<td>
		<button type="button" class="btn btn-danger disabledTool{{$generate}}" wire:click="$dispatch('deleteItems', {id: {{$generate}} })"><span class="mdi mdi-trash-can"></span></button>
	</td>
</tr>
<?php } ?>