@extends('portal::layouts.default')

@section('title', 'Pinjaman | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::home')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Pinjaman</h2>
            <div class="text-muted">Ajukan pinjaman jika mempunyai keperluan mendadak!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body py-4 text-center">
                    <div class="my-4">
                        <a class="btn btn-soft-danger rounded-circle d-flex justify-content-center align-items-center mx-auto" href="{{ route('portal::loan.submission.create', ['next' => url()->full()]) }}" style="width: 100px; height: 100px;"><i class="mdi mdi-exit-to-app mdi-48px"></i></a>
                    </div>
                    <h4 class="mb-1">Pengajuan baru</h4>
                    <p class="text-muted mb-0">Silakan tekan tombol di atas untuk mengisi pengajuan pinjaman</p>
                </div>
                <div class="card-body border-top">
                    <a href="{{ route('portal::loan.submission.index', ['paidoff' => !request('paidoff')]) }}" class="text-success text-center"><i class="mdi mdi-information-outline"></i> {{ request('paidoff') == 1 ? 'Tampilkan pinjaman berjalan' : 'Tampilkan pinjaman yang sudah lunas' }}</a>
                </div>
            </div>
            @if ($approvers->contains($employee->position->id))
                <div class="list-group mb-4">
                    <a class="list-group-item list-group-item-action p-4" href="{{ route('portal::loan.manage.index', ['next' => url()->current()]) }}" style="border-style: dashed;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-inline-block bg-soft-secondary text-danger me-2 rounded text-center" style="height: 36px; width: 36px;">
                                <i class="mdi mdi-calendar-check-outline mdi-24px"></i>
                            </div>
                            <div class="flex-grow-1">Kelola pengajuan pinjaman</div>
                            <i class="mdi mdi-chevron-right-circle-outline"></i>
                        </div>
                    </a>
                </div>
            @endif
        </div>
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar-multiselect"></i> Riwayat pinjaman
                    </div>
                    <input type="checkbox" class="btn-check" id="collapse-btn" autocomplete="off" @if (request('search')) checked @endif>
                    <label class="btn btn-outline-secondary text-dark btn-sm rounded px-2 py-1" data-bs-toggle="collapse" data-bs-target="#collapse-filter" for="collapse-btn"><i class="mdi mdi-filter-outline"></i> <span class="d-none d-sm-inline">Filter</span></label>
                </div>
                <div class="card-body border-top border-bottom">
                    <form class="form-block row gy-2 gx-2" action="{{ route('portal::loan.submission.index') }}" method="get">
                        <div class="col-12 flex-grow-1 my-0">
                            <div class="@if (request('search')) show @endif collapse" id="collapse-filter">
                                <input class="form-control" type="search" name="search" placeholder="Cari nama/deskripsi di sini ..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="flex-grow-1 col-auto">
                            <div class="input-group">
                                <div class="input-group-text"><span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span><span class="d-none d-sm-inline">Periode</span></div>
                                <button type="button" class="btn btn-light dropdown-toggle d-none d-sm-block" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">Rentang waktu</button>
                                <input class="form-control" type="date" name="start_at" value="{{ request('start_at') }}">
                                <input class="form-control" type="date" name="end_at" value="{{ request('end_at') }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('portal::loan.submission.index') }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
                </div>
                @if (request('pending'))
                    <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                        Hanya menampilkan pengajuan yang masih tertunda/berstatus <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-timer-outline"></i> Menunggu</div>
                    </div>
                @endif
                @if (request('paidoff'))
                    <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                        Hanya menampilkan pinjaman yang sudah <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-check"></i> Lunas</div>
                    </div>
                @endif
                <div class="table-responsive table-responsive-xl">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kategori</th>
                                <th nowrap>Tgl pengajuan</th>
                                <th class="text-center">Angsuran</th>
                                <th>Status</th>
                                <th>Nominal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $loan)
                                <tr class="@if ($loan->trashed()) text-muted @endif @if ($loan->childrens->count()) border-bottom-0 border-white @endif">
                                    <td class="text-center">{{ $loop->iteration + ($loans->firstItem() - 1) }}</td>
                                    <td width="35%" class="py-3">
                                        <div>{{ $loan->category->name }}</div>
                                        <small class="text-muted">{{ Str::words($loan->description, 6) }}</small>
                                    </td>
                                    <td class="small">
                                        <div class="badge bg-soft-secondary text-dark fw-normal">
                                            {{ $loan->submission_at->formatLocalized('%d %B %Y') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ $loan->installments->count() }}
                                    </td>
                                    <td nowrap>
                                        @include('portal::loan.components.status', ['loan' => $loan])
                                    </td>
                                    <td style="min-width: 200px;">
                                        Rp. {{ number_format($loan->amount_total, 2) }}
                                    </td>
                                    <td nowrap class="py-1 text-end">
                                        @unless ($loan->trashed())
                                            @if ($loan->hasApprovables())
                                                <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loan->id }}">
                                                    <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                                </span>
                                            @endif
                                            <div class="dropstart d-inline">
                                                <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                <ul class="dropdown-menu border-0 shadow">
                                                    <li><a class="dropdown-item" href="{{ route('portal::loan.submission.show', ['loan' => $loan->id, 'next' => request('next', url()->full())]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                    @if (isset($loan->attachment) && Storage::exists($loan->attachment))
                                                        <li><a class="dropdown-item" href="{{ Storage::url($loan->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline me-1"></i> Lihat lampiran</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endunless
                                    </td>
                                </tr>
                                @if ($loan->childrens)
                                    @foreach ($loan->childrens as $children)
                                        <tr class="border-top-0 border-light">
                                            <td></td>
                                            <td width="35%" class="py-3">
                                                <div class="text-dark">{{ $children->category->name }}</div>
                                                <small class="text-muted">{{ $children->category->name }} atas {{ $children->parent->category->name }} {{ Str::words($children->description, 6) }}</small>
                                            </td>
                                            <td class="small">
                                                <div class="badge bg-soft-secondary text-dark fw-normal">
                                                    {{ $children->submission_at->formatLocalized('%d %B %Y') }}
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $children->installments->count() }}</td>
                                            <td nowrap>
                                                @include('portal::loan.components.status', ['loan' => $children])
                                            </td>
                                            <td>Rp. {{ number_format($children->amount_total, 2) }}</td>
                                            <td nowrap class="py-1 text-end">
                                                <div class="dropstart d-inline">
                                                    <button class="btn btn-soft-secondary text-dark me-1 rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                    <ul class="dropdown-menu border-0 shadow">
                                                        <li><a class="dropdown-item" href="{{ route('portal::loan.submission.show', ['loan' => $children->id, 'next' => request('next', url()->full())]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                        @if (isset($children->attachment) && Storage::exists($children->attachment))
                                                            <li><a class="dropdown-item" href="{{ Storage::url($children->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline me-1"></i> Lihat lampiran</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($loan->hasApprovables() && !$loan->trashed())
                                    <tr>
                                        <td class="p-0" colspan="100%">
                                            <div class="@if ($loan->hasAnyApprovableResultIn('PENDING')) show @endif collapse" id="collapse-{{ $loan->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th width="5%"></th>
                                                            <th class="border-bottom fw-normal">Jenis</th>
                                                            <th class="border-bottom fw-normal" colspan="2">Persetujuan</th>
                                                            <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($loan->approvables as $approvable)
                                                            <tr>
                                                                <td></td>
                                                                <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                <td @if ($loop->last) class="border-0" @endif>
                                                                    <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                </td>
                                                                <td class="ps-0 small">{{ $approvable->reason }}</td>
                                                                <td class="small">{{ $approvable->userable->getApproverLabel() }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5">@include('components.notfound')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $loans->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
@endpush
