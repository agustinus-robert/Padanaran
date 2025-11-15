@extends('docs::layouts.master')

@section('title', 'Dasbor | ')

@section('navtitle', 'Dasbor')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-between">
                        <div>
                            <img class="w-100" src="{{ asset('img/manypixels/Diversity_Flatline.svg') }}" alt="" style="height: 140px;">
                        </div>
                        <div class="order-md-first text-md-start text-center">
                            <div class="px-4 py-3">
                                <h2 class="fw-normal">Selamat datang {{ Auth::user()->name }}!</h2>
                                <div class="text-muted">di {{ config('modules.docs.name') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Daftar dokumen.
                </div>
                <div class="card-body border-top border-light">
                    <form class="form-block row gy-2 gx-2" action="{{ route('docs::home', ['next' => request('next')]) }}" method="get">
                        <div class="flex-grow-1 col-auto">
                            <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('docs::home', ['next' => request('next')]) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                <th>Tipe</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $docs)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($documents->firstItem() - 1) }}</td>
                                    <td>
                                        {{ $docs->label ?? 'Tida ada nama' }} <br>
                                        {{ $docs->meta?->description ?? '' }}
                                    </td>
                                    <td nowrap>{{ \Modules\Docs\Enums\DocumentTypeEnum::tryFrom($docs->type->value)->label() }}</td>
                                    <td nowrap class="py-1 text-end">
                                        <a target="_blank" class="btn btn-soft-primary rounded px-2 py-1 pe-2" href="{{ route('docs::home.show', ['document' => $docs->id, 'next' => url()->full()]) }}" title="lihat dokuemn"><i class="mdi mdi-eye"></i></a>
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
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <i class="mdi mdi-clipboard-list-outline"></i> Kategori dokumen
                </div>
                @foreach ($types as $type)
                    <div class="list-group list-group-flush border-top">
                        <a class="list-group-item list-group-item-action p-3" href="{{ route('docs::categories.show', ['type' => $type->value]) }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">{{ $type->label() }}</div>
                                <i class="mdi mdi-chevron-right-circle-outline"></i>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            @can('docs::access')
                <div class="card mb-4 border-0">
                    <div class="card-body">
                        <i class="mdi mdi-clipboard-list-outline"></i> Menu lainnya
                    </div>
                    <div class="list-group list-group-flush border-top">
                        <a class="list-group-item list-group-item-action p-4" href="{{ route('docs::manage.documents.index') }}" style="border-style: dashed;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-inline-block bg-soft text-danger me-2 rounded text-center" style="height: 36px; width: 36px;">
                                    <i class="mdi mdi-account-key-outline mdi-24px"></i>
                                </div>
                                <div class="flex-grow-1">Kelola dokumen</div>
                                <i class="mdi mdi-chevron-right-circle-outline"></i>
                            </div>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
