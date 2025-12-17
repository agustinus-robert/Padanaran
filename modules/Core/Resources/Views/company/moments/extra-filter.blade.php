<form class="form-block row gy-2 gx-2" action="{{ route('core::company.moments.index') }}" method="get">

    {{-- Tahun --}}
    <div class="flex-grow-1 col-auto">
        <div class="input-group align-items-stretch">
           <x-input
                type="number"
                name="year"
                :value="request('year', date('Y'))"
                placeholder="Tahun"
                class="ps-2 pe-2"
            />
        </div>
    </div>

    {{-- Search --}}
    <div class="flex-grow-1 col-auto">
        <x-input
            name="search"
            placeholder="Cari nama hari atau tanggal ..."
            :value="request('search')"
            onkeyup="searchTable()"
            class="ps-2"
        />
    </div>

    {{-- Reset --}}
    <div class="col-auto">
        <x-btn
            variant="light"
            size="md"
            href="{{ route('core::company.moments.index') }}"
            title="Reset"
        >
            <span class="material-symbols-rounded">refresh</span>
            <span class="d-sm-none ms-1">Reset</span>
        </x-btn>
    </div>

    {{-- Cari --}}
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
