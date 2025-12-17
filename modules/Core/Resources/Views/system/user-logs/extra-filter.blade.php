<form class="form-block row gy-2 gx-2" action="{{ route('core::system.user-logs.index') }}" method="get">
    <input name="trash" type="hidden" value="{{ request('trash') }}">
    <div class="flex-grow-1 col-auto">
        <x-select
            name="user"
            placeholder="-- Pilih pengguna --"
            :value="request('user')"
            :options="request('user') && $user
                ? collect([
                    [
                        'value' => request('user'),
                        'label' => $user->name,
                    ]
                ])
                : collect()"
        />
    </div>

    <div class="flex-grow-1 col-auto">
        <x-input
            name="search"
            class="border p-2"
            placeholder="Cari pesan log ..."
            :value="request('search')"
            onkeyup="searchTable()"
        />
    </div>
    <div class="col-auto">
        <x-btn
            variant="light"
            size="md"
            href="{{ route('core::system.user-logs.index', request()->only('trashed', 'closed')) }}"
            title="Refresh"
        >
            <span class="material-symbols-rounded">refresh</span>
        </x-btn>
    </div>

    <div class="col-auto">
        <x-btn
            type="submit"
            variant="dark"
            size="md"
        >
            <i class="mdi mdi-magnify"></i> Cari
        </x-btn>
    </div>

</form>
