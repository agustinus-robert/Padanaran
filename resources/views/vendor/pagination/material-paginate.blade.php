<div class="row align-items-center justify-content-between g-3">

    {{-- LIMIT SELECT --}}
    <div class="col-xl-5 col-lg-6 d-flex align-items-center gap-2">
        <span class="fw-semibold">Menampilkan</span>

        <select class="form-select form-select-sm"
                style="width: auto; height: calc(1.5em + .5rem + 2px);"
                onchange="if(this.value) window.location.href=this.value;">
            @foreach ([5, 10, 25, 50, 100] as $limiter)
                <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['limit' => $limiter])) }}"
                        @selected(request('limit', 10) == $limiter)>
                    {{ $limiter }}
                </option>
            @endforeach
        </select>

        <span class="fw-semibold">baris</span>
    </div>

    {{-- PAGINATION --}}
    <div class="col-xl-5 col-lg-6 d-flex justify-content-end align-items-center gap-1 flex-wrap">
        {{-- FIRST --}}
        <a class="btn btn-sm btn-outline-dark {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
        href="{{ $paginator->onFirstPage() ? '#' : $paginator->url(1) }}">
            &laquo; Prev
        </a>

        {{-- PREV --}}
        <a class="btn btn-sm btn-outline-dark {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
        href="{{ $paginator->previousPageUrl() ?? '#' }}">
            &lsaquo;
        </a>

        {{-- PAGES --}}
        @for ($page = 1; $page <= $paginator->lastPage(); $page++)
            <a href="{{ $paginator->url($page) }}"
            class="btn btn-sm {{ $paginator->currentPage() == $page ? 'btn-outline-dark active' : 'btn-outline-dark' }}">
                {{ $page }}
            </a>
        @endfor

        {{-- NEXT --}}
        <a class="btn btn-sm btn-outline-dark {{ !$paginator->hasMorePages() ? 'disabled' : '' }}"
        href="{{ $paginator->nextPageUrl() ?? '#' }}">
            &rsaquo;
        </a>

        {{-- LAST --}}
        <a class="btn btn-sm btn-outline-dark {{ !$paginator->hasMorePages() ? 'disabled' : '' }}"
        href="{{ $paginator->url($paginator->lastPage()) }}">
           Next &raquo;
        </a>
    </div>


</div>
