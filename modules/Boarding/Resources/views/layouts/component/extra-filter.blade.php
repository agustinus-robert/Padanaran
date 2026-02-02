 <form class="form-block row" action="{{ route($module.'::leave.manage.index') }}" method="get">
    <input name="pending" type="hidden" value="{{ request('pending') }}">
    <div class="flex-grow-1 col-auto">
        <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
    </div>
    <div class="col-auto">
        <a class="btn btn-light" href="{{ route($module.'::leave.manage.index', request()->only('pending')) }}">
            <span class="material-symbols-rounded">refresh</span>
        </a>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
    </div>
</form>
