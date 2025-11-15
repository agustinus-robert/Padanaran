@extends('docs::layouts.master')

@section('title', 'Lihat kategori | ')

@section('navtitle', 'Lihat kategori')

@section('content')
    <div class="row justify-content-center">
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
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Daftar dokumen dengan kategori {{ \Modules\Docs\Enums\DocumentTypeEnum::tryFrom(request('type'))->label() }}.
                </div>
                <div class="card-body border-top border-light">
                    <form class="form-block row gy-2 gx-2" action="{{ route('docs::categories.show', ['type' => request('type'), 'next' => request('next')]) }}" method="get">
                        <div class="flex-grow-1 col-auto">
                            <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('docs::categories.show', ['type' => request('type'), 'next' => request('next')]) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
                </div>
                @if (request('trashed'))
                    <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                        Hanya menampilkan dokuemn yang <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-trash-can-outline"></i> dihapus</div>
                    </div>
                @endif
                <div class="table-responsive table-responsive-xl">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama dokumen</th>
                                <th nowrap>Kategori</th>
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
                                        <a class="btn btn-soft-primary pe-2 rounded px-2 py-1" href="{{ route('docs::manage.documents.show', ['document' => $docs->id, 'next' => url()->full()]) }}" title="lihat dokuemn"><i class="mdi mdi-download-outline"></i></a>
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
        </div>
    </div>
@endsection
