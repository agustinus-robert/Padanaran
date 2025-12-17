<x-input-group :isRow="true">
    <x-label value="Peran" required />

    <x-col size="12">
        <x-select
            name="role_id"
            placeholder="-- Pilih --"
            :value="old('role_id', $user->roles->first()->id ?? '')"
            :options="$roles->map(fn ($role) => [
                'value' => $role->id,
                'label' => $role->name,
            ])"
        />

        @error('role_id')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror

        <div class="text-muted mt-2">
            Peran saat ini adalah
            <span class="badge bg-soft-secondary text-dark">
                {{ $user->roles->first()->name ?? 'Belum ada peran' }}
            </span>
        </div>
    </x-col>
</x-input-group>

<div>
    <x-btn variant="dark">
        <span class="material-symbols-rounded">
            check
        </span> Simpan
    </x-btn>
    @isset($back)
        <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('core::system.users.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
    @endisset
</div>
