 <form action="{{ route('counseling::manage.cases.descriptions.index') }}" method="GET">
    <div class="input-group">
        <select name="ctg" class="form-control">
            <option value="">-- Pilih --</option>
            @foreach ($categories as $_category)
                <option value="{{ $_category->id }}" @if (request('ctg') == $_category->id) selected @endif>{{ $_category->name }}</option>
            @endforeach
        </select>
        <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Cari nama disini ...">
        <div class="input-group-append">
            <a class="btn btn-outline-secondary" href="{{ route('counseling::manage.cases.descriptions.index') }}"><i class="mdi mdi-refresh"></i></a>
            <button class="btn btn-primary">Cari</button>
        </div>
    </div>
</form>
