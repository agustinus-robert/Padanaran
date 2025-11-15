<div class="text-center">
	<div class="pb-4 d-flex justify-content-center">
		<div id="upload-preview" class="rounded-circle @error('file') border border-danger @enderror" style="background: url('{{ $user->profile_avatar_path }}') center center no-repeat; background-size: cover; min-width: 128px; min-height: 128px; width: 200px; height: 200px;"></div>
	</div>
	<div class="mb-3">
		<div class="mb-3">
			<label for="upload-input" class="form-label required">Pilih foto profil</label>
			<input class="form-control" name="file" type="file" id="upload-input" accept="image/*">
			<small class="text-muted">Berkas berupa .jpg atau .png maksimal berukuran 2mb</small>
		</div>
		@error('file')
			<small class="text-danger d-block"> {{ $message }} </small>
		@enderror
	</div>
	<div>
		<button id="submit-form" class="btn btn-soft-danger" type="submit" disabled><i class="mdi mdi-check"></i> Simpan</button>
		@isset($back)
			<a class="btn btn-ghost-light text-dark" href="{{ request('next', route('account::index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
		@endisset
	</div>
</div>

@push('scripts')
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			document.querySelector("#upload-input").addEventListener('change', (e) => {
				const [file] = e.target.files
				if (file) {
					document.querySelector('#upload-preview').style.backgroundImage = `url(${URL.createObjectURL(file)})`
				}

				document.querySelector('#submit-form').removeAttribute('disabled');
			});
		});
	</script>
@endpush