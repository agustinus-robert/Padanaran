<form action="{{ route($module.'::leave.manage.index') }}" method="GET" class="d-flex align-items-center gap-2 w-100">
    <input name="pending" type="hidden" value="{{ request('pending') }}">

    <div class="flex-grow-1">
        <div class="input-group input-group-outline {{ request('search') ? 'is-filled' : '' }}">
            <label class="form-label">Cari nama atau NIP...</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}">
        </div>
    </div>

    <div class="d-flex gap-1 col-auto">
        <button type="submit" class="btn btn-dark btn-sm mb-0 d-flex align-items-center gap-2 px-4">
            <span class="material-symbols-rounded" style="font-size: 1.2rem;">search</span>
            <span>Cari</span>
        </button>

        <a class="btn btn-outline-secondary btn-sm mb-0 d-flex align-items-center px-3" 
           href="{{ route($module.'::leave.manage.index', request()->only('pending')) }}" 
           title="Reset">
            <span class="material-symbols-rounded" style="font-size: 1.2rem;">restart_alt</span>
        </a>
    </div>
</form>