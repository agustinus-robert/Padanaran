{{-- NOMOR PONSEL --}}
<x-input-group :isRow="true">
    <x-label value="Nomor ponsel" required />

    <x-col size="12">
        <div class="input-group">
            <select
                name="phone_code"
                class="form-select flex-grow-0 bg-light @error('phone_code') is-invalid @enderror"
                style="min-width:50px"
                required
            >
                <option value="62" @selected(old('phone_code', '62') == '62')>+62</option>
            </select>

            <x-input
                type="number"
                name="phone_number"
                required
                data-mask="62#"
                :value="old('phone_number', $user->getMeta('phone_number'))"
            />
        </div>

        @error('phone_code')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror

        @error('phone_number')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    </x-col>
</x-input-group>

<div class="mb-3">
    <div class="form-check ps-0">
        <input
            class="form-check-input ms-0"
            type="checkbox"
            id="phone_whatsapp"
            name="phone_whatsapp"
            value="1"
            @checked($user->getMeta('phone_whatsapp'))
        >
        <label class="form-check-label ms-1" for="phone_whatsapp">
            Nomor ini
            <strong>
                <span id="whatsapp-text">
                    @if(!$user->getMeta('phone_whatsapp')) tidak @endif
                </span>
                terdaftar
            </strong>
            di whatsapp
        </label>
    </div>
</div>

<div>
	 <x-btn variant="dark">
        <span class="material-symbols-rounded">
            check
        </span> Simpan
    </x-btn>

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
