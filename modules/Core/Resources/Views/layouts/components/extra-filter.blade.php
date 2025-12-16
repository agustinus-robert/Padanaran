<div class="col-md-9">
    <div class="row g-2">
        <div class="col-md-6">
            <x-select
                name="department"
                :options="$departments->map(fn($_department) => [
                    'value' => $_department->id,
                    'label' => $_department->name
                ])"
                :value="request('department')"
                placeholder="Semua departemen"
                class="form-select form-select-sm p-2"
            />
        </div>

        <div class="col-md-6">
            <input class="form-control border p-2"
                name="search"
                placeholder="Cari nama ..."
                value="{{ request('search') }}"
                onkeyup="searchTable()"
            />
        </div>
    </div>
</div>

<div class="col-md-3 d-flex gap-2">
    <a class="btn btn-light flex-grow-1" href="{{ route('core::company.positions.index', request()->only('trashed', 'closed')) }}">
        <span class="material-symbols-rounded">refresh</span>
        <span class="d-sm-none">Reset</span>
    </a>
    <button type="submit" class="btn btn-dark flex-grow-1">
        <span class="material-symbols-rounded">search</span> Cari
    </button>
</div>

