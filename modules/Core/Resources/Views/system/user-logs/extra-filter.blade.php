<form class="d-flex align-items-center gap-2 w-100" action="{{ route('core::system.user-logs.index') }}" method="get">
    <input name="trash" type="hidden" value="{{ request('trash') }}">
    
    {{-- Select Pengguna dengan lebar yang lebih proporsional --}}
    <div style="min-width: 200px;">
        <x-select
            name="user"
            placeholder="-- Pilih pengguna --"
            :value="request('user')"
            :options="request('user') && $user
                ? collect([['value' => request('user'), 'label' => $user->name]])
                : collect()"
        />
    </div>

    {{-- Input Search yang fleksibel --}}
    <div class="flex-grow-1">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <span class="material-symbols-rounded text-sm">search</span>
            </span>
            <x-input
                name="search"
                class="border-start-0 ps-0"
                placeholder="Cari pesan log..."
                :value="request('search')"
            />
        </div>
    </div>

    {{-- Grouping Tombol Aksi --}}
    <div class="d-flex gap-1">
        <x-btn
            variant="light"
            size="md"
            href="{{ route('core::system.user-logs.index', request()->only('trashed', 'closed')) }}"
            class="px-3"
            title="Refresh"
        >
            <span class="material-symbols-rounded">refresh</span>
        </x-btn>

        <x-btn
            type="submit"
            variant="dark"
            size="md"
            class="px-4 d-flex align-items-center gap-2"
        >
            <span class="material-symbols-rounded text-sm">filter_alt</span>
            Cari
        </x-btn>
    </div>
</form>