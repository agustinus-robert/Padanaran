<div class="mb-3">
	<label class="form-label required">Nomor ponsel</label>
	<div class="input-group d-flex">
		<select class="form-select flex-grow-0 bg-light @error('phone_code') is-invalid @enderror" name="phone_code" style="min-width: 130px;" required>
			<option value="62">+62</option>
		</select>
		<input type="number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $user->getMeta('phone_number')) }}" required data-mask="62#">
	</div>
	@error('phone_code')
		<small class="text-danger d-block"> {{ $message }} </small>
	@enderror
	@error('phone_number')
		<small class="text-danger d-block"> {{ $message }} </small>
	@enderror
</div>
<div class="mb-3">
	<div class="form-check">
		<input class="form-check-input" type="checkbox" id="phone_whatsapp" value="1" name="phone_whatsapp" @if($user->getMeta('phone_whatsapp')) checked @endif>
		<label class="form-check-label" for="phone_whatsapp">Nomor ini <strong><span id="whatsapp-text">@if(!$user->getMeta('phone_whatsapp')) tidak @endif</span> terdaftar</strong> di whatsapp</label>
	</div>
</div>
<div>
	<button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
	@isset($back)
		<a class="btn btn-ghost-light text-dark" href="{{ request('next', route('account::index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
	@endisset
</div>

@push('scripts')
	<script>
		document.addEventListener("DOMContentLoaded", async () => {
			document.querySelector('#phone_whatsapp').addEventListener('change', (e) => {
			    document.querySelector('#whatsapp-text').innerHTML = e.target.checked ? '' : 'tidak'
			});

			let { data } = await axios.get('{{ route('api::references.phones.index') }}');
			for(code in data.data) {
				for(index in data.data[code]) {
					let number = data.data[code][index];
					let option = document.createElement('option');
						option.value = number;
						option.innerHTML = `+${number}`;

					if(number == '{{ old('phone_code', $user->getMeta('phone_code', 62)) }}') {
						option.selected = 'selected'
					}

					document.querySelector('[name="phone_code"]').appendChild(option);
				}
			}
		});
	</script>
@endpush