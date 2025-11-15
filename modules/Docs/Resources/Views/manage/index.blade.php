@extends('docs::layouts.master')

@section('title', 'Kelola | ')

@section('navtitle', 'Kelola dokumen')

@section('content')
    <div class="row">
        <div class="col-xxl-12 col-xl-12">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('docs::home')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Daftar dokumen</h2>
                    <div class="text-secondary">Silakan pilih dokumen di bawah ini.</div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            @if (request('trashed'))
                <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                    Hanya menampilkan dokuemn yang <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-trash-can-outline"></i> dihapus</div>
                </div>
            @endif
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Daftar dokumen.
                </div>
                <div class="table-responsive table-responsive-xl">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama dokumen</th>
                                <th>Kategori</th>
                                <th nowrap>Di unggah pada</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $docs)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($documents->firstItem() - 1) }}</td>
                                    <td>
                                        {{ $docs->label ?? 'Tida ada nama' }} <br>
                                        <span class="fw-lighter">{{ $docs->meta->description ?? '' }}</span>
                                    </td>
                                    <td>{{ \Modules\Docs\Enums\DocumentTypeEnum::tryFrom($docs->type->value)->label() }}</td>
                                    <td>{{ $docs->created_at->isoFormat('LL') }}</td>
                                    <td nowrap class="py-1 text-end">
                                        <a target="_blank" class="btn btn-soft-primary pe-2 rounded px-2 py-1" href="{{ route('docs::manage.documents.show', ['document' => $docs->id, 'next' => url()->full()]) }}" title="lihat dokumen"><i class="mdi mdi-eye"></i></a>
                                        <a class="btn btn-soft-warning pe-2 rounded px-2 py-1" href="{{ route('docs::manage.documents.edit', ['document' => $docs->id, 'next' => url()->full()]) }}" title="lihat dokumen"><i class="mdi mdi-pencil-outline"></i></a>
                                        @can('destroy', $docs)
                                            <form class="form-block form-confirm d-inline" action="{{ route('docs::manage.documents.destroy', ['document' => $docs->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">@include('components.notfound')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $documents->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                    <div>
                        <div class="display-4">{{ $document_count ?? 0 }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Jumlah dokumen</div>
                    </div>
                    <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('docs::manage.documents.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label" for="select-type">Kategori</label>
                            <select class="form-select" id="select-type" name="type">
                                <option value>Semua dokumen</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->value }}" @selected(request('type') == $type->value)>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="label">Nama dokumen</label>
                            <input class="form-control" id="label" name="search" placeholder="Cari nama dokumen..." value="{{ request('search') }}" />
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                                <label class="form-check-label" for="trashed">Tampilkan juga dokumen yang telah dihapus</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('docs::manage.documents.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            @can('docs::access')
                <div class="card mb-4 border-0">
                    <div class="card-body">
                        <i class="mdi mdi-clipboard-list-outline"></i> Menu lainnya
                    </div>
                    <div class="list-group list-group-flush border-top">
                        <a class="list-group-item list-group-item-action p-4" href="{{ route('docs::manage.documents.create') }}" style="border-style: dashed;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-inline-block bg-soft text-danger me-2 rounded text-center" style="height: 36px; width: 36px;">
                                    <i class="mdi mdi-note-plus-outline mdi-24px"></i>
                                </div>
                                <div class="flex-grow-1">Tambah dokumen</div>
                                <i class="mdi mdi-chevron-right-circle-outline"></i>
                            </div>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
