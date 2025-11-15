<div class="mb-3">
    <label class="form-label required">Username skype</label>
    <div class="input-group d-flex">
        <div class="input-group-text"><i class="mdi mdi-skype-business"></i></div>
        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->getMeta('skype_username')) }}" placeholder="live:.cid.123456789" required>
    </div>
    @error('username')
        <small class="text-danger d-block"> {{ $message }} </small>
    @enderror
    <div class="text-muted my-4">
        Di mana saya dapat menemukan username Skype? <a target="_blank" href="https://support.skype.com/en/faq/fa10858/what-s-my-skype-name#:~:text=Where%20can%20I%20find%20my,is%20displayed%20in%20your%20profile.">Klik disini</a>
    </div>
</div>
<div>
    <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
    @isset($back)
        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('account::index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
    @endisset
</div>
