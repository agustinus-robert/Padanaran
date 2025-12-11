<div class="row align-items-center justify-content-between g-3">

    {{-- LIMIT SELECT --}}
    <div class="col-xl-5 col-lg-6">
        <div class="d-flex align-items-center gap-2">

            <span class="fw-semibold">Menampilkan</span>

            <select class="form-select form-select-sm rounded-pill"
                style="width: auto;"
                onchange="window.location = this.value;">
                @foreach ([5, 10, 25, 50, 100] as $limiter)
                    <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['limit' => $limiter])) }}"
                        @selected(request('limit', 10) == $limiter)>
                        {{ $limiter }}
                    </option>
                @endforeach
            </select>

            <span class="fw-semibold">baris</span>

        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="col-xl-5 col-lg-6 text-end">
        @if ($paginator->hasPages())
        <div class="d-inline-flex align-items-center gap-1">

            {{-- FIRST --}}
            <a class="btn btn-sm btn-outline-primary rounded-pill {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
                href="{{ $paginator->url(1) }}">&laquo;</a>

            {{-- PREV --}}
            <a class="btn btn-sm btn-outline-primary rounded-pill {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
                href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a>

            {{-- PAGE SELECT --}}
            <select class="form-select form-select-sm rounded-pill"
                style="width: auto;"
                onchange="window.location = this.value;">
                @for ($page = 1; $page <= $paginator->lastPage(); $page++)
                    <option value="{{ $paginator->url($page) }}"
                        @selected($paginator->currentPage() == $page)>
                        {{ $page }}
                    </option>
                @endfor
            </select>

            {{-- NEXT --}}
            <a class="btn btn-sm btn-outline-primary rounded-pill {{ !$paginator->hasMorePages() ? 'disabled' : '' }}"
                href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a>

            {{-- LAST --}}
            <a class="btn btn-sm btn-outline-primary rounded-pill {{ !$paginator->hasMorePages() ? 'disabled' : '' }}"
                href="{{ $paginator->url($paginator->lastPage()) }}">&raquo;</a>

        </div>
        @endif
    </div>

</div>
