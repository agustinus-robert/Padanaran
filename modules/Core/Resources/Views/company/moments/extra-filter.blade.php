<form class="d-flex align-items-center gap-2 w-100" action="{{ route('core::company.moments.index') }}" method="get">
    
    {{-- Filter Tahun --}}
    <div style="width: 130px;">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0 px-2">
                <span class="material-symbols-rounded" style="font-size: 1.25rem;">calendar_today</span>
            </span>
            <x-input
                type="number"
                name="year"
                :value="request('year', date('Y'))"
                placeholder="Tahun"
                class="border-start-0 ps-1"
            />
        </div>
    </div>

    {{-- Pencarian Nama/Tanggal --}}
    <div class="flex-grow-1">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0 px-2">
                <span class="material-symbols-rounded" style="font-size: 1.25rem;">search</span>
            </span>
            <x-input
                name="search"
                placeholder="Cari nama hari atau tanggal..."
                :value="request('search')"
                class="border-start-0 ps-1"
            />
        </div>
    </div>

    {{-- Grouping Tombol Aksi --}}
    <div class="d-flex gap-1">
        <x-btn
            variant="light"
            size="md"
            href="{{ route('core::company.moments.index') }}"
            class="px-3 d-flex align-items-center"
            title="Reset Filter"
        >
            <span class="material-symbols-rounded" style="font-size: 1.25rem;">restart_alt</span>
        </x-btn>

        <x-btn
            type="submit"
            variant="dark"
            size="md"
            class="px-4 d-flex align-items-center gap-2"
        >
            <span class="material-symbols-rounded" style="font-size: 1.25rem;">filter_list</span>
            <span>Cari</span>
        </x-btn>
    </div>
</form>