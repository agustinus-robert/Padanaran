<div class="d-flex align-items-center gap-2 w-100">
    {{-- Dropdown Kategori --}}
    <div class="col-auto">
        <div class="input-group input-group-outline is-filled">
            <select name="ctg" class="form-control" style="padding: 0.5rem 0.75rem;">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $_category)
                    <option value="{{ $_category->id }}" @if (request('ctg') == $_category->id) selected @endif>
                        {{ $_category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Input Pencarian --}}
    <div class="flex-grow-1">
        <div class="input-group input-group-outline {{ request('search') ? 'is-filled' : '' }}">
            <label class="form-label">Cari deskripsi di sini...</label>
            <input class="form-control" name="search" type="text" value="{{ request('search') }}">
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex gap-1 col-auto">
        <a class="btn btn-outline-secondary btn-sm mb-0 d-flex align-items-center px-3" 
           href="{{ route('counseling::manage.cases.descriptions.index') }}" 
           title="Reset">
            <span class="material-symbols-rounded" style="font-size: 1.2rem;">restart_alt</span>
        </a>
        
        <button type="submit" class="btn btn-dark btn-sm mb-0 d-flex align-items-center gap-2 px-4">
            <span class="material-symbols-rounded" style="font-size: 1.2rem;">filter_alt</span>
            <span>Cari</span>
        </button>
    </div>
</div>