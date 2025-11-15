<fieldset>
    <div class="row">
        <div class="col-md-7 offset-md-4 offset-lg-3">
            <h5 class="text-muted font-weight-normal mb-3">Informasi umum</h5>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label">Gelar depan</label>
        <div class="col-md-4">
            <input type="text" class="form-control @error('profile_prefix') is-invalid @enderror" name="profile_prefix" value="{{ old('profile_prefix', $user->getMeta('profile_prefix')) }}">
            @error('profile_prefix')
                <span class="invalid-feedback"> {{ $message }} </span>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label required">Nama lengkap</label>
        <div class="col-md-7">
            <input type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name ?: $user->profile->name) }}" required>
            <small class="form-text text-muted">Nama lengkap (tidak boleh disingkat) diisi sesuai Akta/KTP/KK atau identitas resmi lainnya.</small>
            @error('name')
                <span class="invalid-feedback"> {{ $message }} </span>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label">Gelar belakang</label>
        <div class="col-md-4">
            <input type="text" class="form-control @error('profile_suffix') is-invalid @enderror" name="profile_suffix" value="{{ old('profile_suffix', $user->getMeta('profile_suffix')) }}">
            @error('profile_suffix')
                <span class="invalid-feedback"> {{ $message }} </span>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label">Tempat lahir</label>
        <div class="col-md-5">
            <input type="text" class="form-control @error('profile_pob') is-invalid @enderror" name="profile_pob" value="{{ old('profile_pob', $user->getMeta('profile_pob')) }}">
            <small class="form-text text-muted">Diisi sesuai Akta/KTP/KK atau identitas resmi lainnya.</small>
            @error('profile_pob')
                <small class="text-danger d-block"> {{ $message }} </small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label">Tanggal lahir</label>
        <div class="col-md-5">
            <input type="date" class="form-control @error('profile_dob') is-invalid @enderror" name="profile_dob" value="{{ old('profile_dob', $user->getMeta('profile_dob') ? date('Y-m-d', strtotime($user->getMeta('profile_dob'))) : '') }}">
            <small class="form-text text-muted">Diisi dengan format hh-bb-tttt (ex: 23-02-2001) dan sesuai dengan Kartu Keluarga atau akta kelahiran </small>
            @error('profile_dob')
                <small class="text-danger d-block"> {{ $message }} </small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label">Jenis kelamin</label>
        <div class="col-md-4">
            <div class="btn-group">
                @foreach (Modules\Account\Enums\SexEnum::cases() as $v)
                    <input class="btn-check" type="radio" id="profile_sex{{ $v->value }}" name="profile_sex" value="{{ $v->value }}" autocomplete="off" @if (!is_null($user->getMeta('profile_sex')) && old('profile_sex', $user->getMeta('profile_sex')) == $v->value) checked @endif>
                    <label class="btn btn-outline-secondary text-dark" for="profile_sex{{ $v->value }}">{{ $v->label() }}</label>
                @endforeach
            </div>
            @error('profile_sex')
                <small class="text-danger d-block"> {{ $message }} </small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label">Golongan darah</label>
        <div class="col-md-4">
            <select name="profile_blood" class="@error('profile_blood') is-invalid @enderror form-select">
                <option value="">-- Pilih --</option>
                @foreach (Modules\Account\Enums\BloodEnum::cases() as $v)
                    <option value="{{ $v->value }}" @if (!is_null($user->getMeta('profile_blood')) && old('profile_blood', $user->getMeta('profile_blood')) == $v->value) selected @endif>{{ $v->label() }}</option>
                @endforeach
            </select>
            @error('profile_blood')
                <span class="invalid-feedback"> {{ $message }} </span>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-md-4 col-lg-3 col-form-label">Agama</label>
        <div class="col-md-4">
            <select name="profile_religion" class="@error('profile_religion') is-invalid @enderror form-select">
                <option value="">-- Pilih --</option>
                @foreach (Modules\Account\Enums\ReligionEnum::cases() as $v)
                    <option value="{{ $v->value }}" @if (!is_null($user->getMeta('profile_religion')) && old('profile_religion', $user->getMeta('profile_religion')) == $v->value) selected @endif>{{ $v->label() }}</option>
                @endforeach
            </select>
            @error('profile_religion')
                <span class="invalid-feedback"> {{ $message }} </span>
            @enderror
        </div>
    </div>
</fieldset>
<div class="row">
    <div class="col-md-7 offset-md-4 offset-lg-3">
        <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
        @isset($back)
            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('account::index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
        @endisset
    </div>
</div>
