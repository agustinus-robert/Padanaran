<div>
	@if(isset($vArr))
		<fieldset>
		<legend>Preview:</legend>

			<div class="form-group">
				<label>{{$vArr['label']}}</label>
				@if($vArr['type'] == 1)
					<input type="text" class="form-control" placeholder="Jawaban" disabled>
				@endif
			</div>
		</fieldset>
	@endif
</div>