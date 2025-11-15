<div class="mb-3 row">
	<label class="col-md-4 col-lg-3 col-form-label required">Alamat 1</label>
	<div class="col-md-8 col-lg-9">
		<input type="text" class="form-control @error('address_primary') is-invalid @enderror" @isset($disabled) disabled @else name="address_primary" @endisset  value="{{ old('address_primary', $user->getMeta('address_primary')) }}" required>
		<small class="form-text text-muted">Diisi nama jalan/perumahan/dusun/wilayah terkecil dari alamat Anda</small>
		@error('address_primary')
			<small class="text-danger d-block"> {{ $message }} </small>
		@enderror
	</div>
</div>
<div class="mb-3 row">
	<label class="col-md-4 col-lg-3 col-form-label">Alamat 2</label>
	<div class="col-md-8 col-lg-9">
		<input type="text" class="form-control @error('address_secondary') is-invalid @enderror" @isset($disabled) disabled @else name="address_secondary" @endisset  value="{{ old('address_secondary', $user->getMeta('address_secondary')) }}">
		@error('address_secondary')
			<span class="invalid-feedback"> {{ $message }} </span>
		@enderror
	</div>
</div>
<div class="mb-3 row">
	<label class="col-md-4 col-lg-3 col-form-label required text-nowrap">Kota/Kabupaten</label>
	<div class="col-md-8 col-lg-9">
		<input type="text" class="form-control @error('address_city') is-invalid @enderror" @isset($disabled) disabled @else name="address_city" @endisset  value="{{ old('address_city', $user->getMeta('address_city')) }}" required>
		@error('address_city')
			<span class="invalid-feedback"> {{ $message }} </span>
		@enderror
	</div>
</div>
<div class="mb-3 row">
	<label class="col-md-4 col-lg-3 col-form-label required">Provinsi</label>
	<div class="col-md-8 col-lg-9">
		<select class="form-select @error('address_state') is-invalid @enderror" @isset($disabled) disabled @else name="address_state" @endisset  required>
			@isset($user->getMeta('address_state')->id)
				<option value="{{ $user->getMeta('address_state')->id }}" selected>{{ $user->getMeta('address_state')->full }}</option>
			@endisset
		</select>
		@error('address_state')
			<small class="text-danger d-block"> {{ $message }} </small>
		@enderror
	</div>
</div>
<div class="mb-3 row">
	<label class="col-md-4 col-lg-3 col-form-label">Kodepos</label>
	<div class="col-md-6">
		<input type="number" class="form-control @error('address_postal') is-invalid @enderror" @isset($disabled) disabled @else name="address_postal" @endisset value="{{ old('address_postal', $user->getMeta('address_postal')) }}">
		@error('address_postal')
			<small class="text-danger d-block"> {{ $message }} </small>
		@enderror
	</div>
</div>

@empty($disabled)
	<div>
		<div class="col-md-7 offset-md-4 offset-lg-3">
			<button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
			@isset($back)
				<a class="btn btn-ghost-light text-dark" href="{{ request('next', route('account::index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
			@endisset
		</div>
	</div>

	@push('styles')
		<link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
	@endpush

	@push('scripts')
		<script>
			new TomSelect('[name="address_state"]', {
				valueField: 'id',
				labelField: 'text',
				searchField: 'text',
				load: function(q, callback) {
					fetch('{{ route('api::references.country-states.search') }}?q=' + encodeURIComponent(q))
						.then(response => response.json())
						.then(json => {
							callback(json.data);
						}).catch(()=>{
							callback();
						});
				}
			});
		</script>
	@endpush
@endempty