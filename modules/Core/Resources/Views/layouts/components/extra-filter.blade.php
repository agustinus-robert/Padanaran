<div class="d-flex justify-content-end w-100">
    <div class="col-md-9">
        <div class="row g-2 justify-content-end">
            <div class="col-md-4">
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

            <div class="col-md-5">
                <input class="form-control border p-2"
                    name="search"
                    placeholder="Cari nama jabatan..."
                    value="{{ request('search') }}"
                    style="height: 38px; font-size: 13px;"
                />
            </div>

            <div class="col-md-3 d-flex gap-2">
                <a class="btn btn-light mb-0 flex-grow-1 d-flex align-items-center justify-content-center" 
                   href="{{ route('core::company.positions.index') }}" title="Reset Filter">
                    <span class="material-symbols-rounded">refresh</span>
                </a>
                <button type="submit" class="btn btn-dark mb-0 flex-grow-1 d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-rounded">search</span> Cari
                </button>
            </div>
        </div>
    </div>
</div>