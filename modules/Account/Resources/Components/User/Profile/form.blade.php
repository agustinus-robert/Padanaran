<fieldset>
    <div class="row">
        <div class="col-md-7 offset-md-4 offset-lg-3">
            <h5 class="text-muted font-weight-normal mb-3">Informasi umum</h5>
        </div>
    </div>

    {{-- GELAR DEPAN --}}
    <x-input-group :isRow="true">
        <x-label value="Gelar depan" />
        <x-col size="12">
            <x-input
                name="profile_prefix"
                value="{{ old('profile_prefix', $user->getMeta('profile_prefix')) }}"
            />
        </x-col>
    </x-input-group>

    {{-- NAMA LENGKAP --}}
    <x-input-group :isRow="true">
        <x-label value="Nama lengkap" required />
        <x-col size="12">
            <x-input
                name="name"
                value="{{ old('name', $user->name ?: $user->profile->name) }}"
                required
            />
            <small class="form-text text-muted">
                Nama lengkap (tidak boleh disingkat) diisi sesuai Akta/KTP/KK atau identitas resmi lainnya.
            </small>
        </x-col>
    </x-input-group>

    {{-- GELAR BELAKANG --}}
    <x-input-group :isRow="true">
        <x-label value="Gelar belakang" />
        <x-col size="12">
            <x-input
                name="profile_suffix"
                value="{{ old('profile_suffix', $user->getMeta('profile_suffix')) }}"
            />
        </x-col>
    </x-input-group>

    {{-- TEMPAT LAHIR --}}
    <x-input-group :isRow="true">
        <x-label value="Tempat lahir" />
        <x-col size="12">
            <x-input
                name="profile_pob"
                value="{{ old('profile_pob', $user->getMeta('profile_pob')) }}"
            />
            <small class="form-text text-muted">
                Diisi sesuai Akta/KTP/KK atau identitas resmi lainnya.
            </small>
        </x-col>
    </x-input-group>

    {{-- TANGGAL LAHIR --}}
    <x-input-group :isRow="true">
        <x-label value="Tanggal lahir" />
        <x-col size="12">
            <x-input
                type="date"
                name="profile_dob"
                value="{{ old('profile_dob', $user->getMeta('profile_dob') ? date('Y-m-d', strtotime($user->getMeta('profile_dob'))) : '') }}"
            />
            <small class="form-text text-muted">
                Diisi dengan format hh-bb-tttt dan sesuai KK / Akta kelahiran.
            </small>
        </x-col>
    </x-input-group>

    {{-- JENIS KELAMIN --}}
   <div class="row mb-3">
        {{-- JENIS KELAMIN --}}
        <div class="col-md-4">
            <label class="form-label">Jenis kelamin</label>
            <div class="btn-group w-100">
                @foreach (Modules\Account\Enums\SexEnum::cases() as $v)
                    <input
                        class="btn-check"
                        type="radio"
                        id="profile_sex{{ $v->value }}"
                        name="profile_sex"
                        value="{{ $v->value }}"
                        @checked(old('profile_sex', $user->getMeta('profile_sex')) == $v->value)
                    >
                    <label class="btn btn-outline-secondary text-dark" for="profile_sex{{ $v->value }}">
                        {{ $v->label() }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- GOLONGAN DARAH --}}
        <div class="col-md-4">
            <label class="form-label">Golongan darah</label>
            <x-select
                name="profile_blood"
                placeholder="-- Pilih --"
                :value="old('profile_blood', $user->getMeta('profile_blood'))"
                :options="collect(Modules\Account\Enums\BloodEnum::cases())
                    ->map(fn($v) => [
                        'value' => $v->value,
                        'label' => $v->label()
                    ])"
            />
        </div>

        {{-- AGAMA --}}
        <div class="col-md-4">
            <label class="form-label">Agama</label>
            <x-select
                name="profile_religion"
                placeholder="-- Pilih --"
                :value="old('profile_religion', $user->getMeta('profile_religion'))"
                :options="collect(Modules\Account\Enums\ReligionEnum::cases())
                    ->map(fn($v) => [
                        'value' => $v->value,
                        'label' => $v->label()
                    ])"
            />
        </div>
    </div>


</fieldset>

<div class="row mt-3">
    <div class="col-md-12 col-lg-12 text-center">
        <x-btn variant="dark">
            <span class="material-symbols-rounded">
                check
            </span> Simpan
        </x-btn>

        @isset($back)
            <a class="btn btn-ghost-light text-dark ms-2" href="{{ request('next', route('account::index')) }}">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        @endisset
    </div>
</div>
