<div id="content-block">
	<div class="mb-3">
		<label class="form-label required">Alamat surel</label>
		<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
		@error('email')
			<small class="text-danger d-block"> {{ $message }} </small>
		@enderror
	</div>
	@if ($user->email) 
		<div class="mb-3">
			<div class="mb-1">Status verifikasi</div>
			@if ($user->email_verified_at) 
				<div class="text-success"><i class="mdi mdi-check"></i> Terverifikasi</div>
			@else
				<div class="text-muted mb-4"><i class="mdi mdi-close"></i> Belum terverifikasi</div>
				<a id="reverify" href="{{ route('account::user.email.reverify', ['uid' => encrypt($user->id), 'next' => ($next ?? route('account::index'))]) }}">Kirim tautan verifikasi sekarang!</a>
			@endif
		</div>
	@endif
		<div>
			<button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
			@isset($back)
				<a class="btn btn-ghost-light text-dark" href="{{ request('next', route('account::index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
			@endisset
		</div>
	@if ($user->email_verified_at)
		<hr class="text-muted">
		<p class="mb-0">
			<strong>Peringatan!</strong> <br>
			Jika Anda mengubah alamat surel {{ $user->display_name }}, kami akan melakukan verifikasi ulang terhadap surel tersebut
		</p>
	@endif
</div>

@push('scripts')
<script>
	window.addEventListener('DOMContentLoaded', () => {
		document.getElementById('reverify').addEventListener('click', () => {
			UIBlock.block(document.getElementById('content-block'));
		});
	});
</script>
@endpush