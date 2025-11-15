@extends('portal::layouts.default')

@section('title', 'Penggajian | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="d-flex align-items-center mb-4">
                <a class="text-decoration-none" href="{{ request('next', route('portal::dashboard-msdm.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
                <div class="ms-4">
                    <h2 class="mb-1">Penggajian</h2>
                    <div class="text-muted">Yay! Layanan ini yang paling menyenangkan untuk dilihat.</div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar-multiselect"></i> Riwayat penggajian
                    </div>
                    <input type="checkbox" class="btn-check" id="collapse-btn" autocomplete="off" @if (request('search')) checked @endif>
                    <label class="btn btn-outline-secondary text-dark btn-sm rounded px-2 py-1" data-bs-toggle="collapse" data-bs-target="#collapse-filter" for="collapse-btn"><i class="mdi mdi-filter-outline"></i> <span class="d-none d-sm-inline">Filter</span></label>
                </div>
                <div class="card-body border-top border-bottom">
                    <form class="form-block row gy-2 gx-2" action="{{ route('portal::salary.slips.index') }}" method="get">
                        <div class="flex-grow-1 col-auto">
                            <input class="form-control" type="search" name="search" placeholder="Cari nama slip di sini ..." value="{{ request('search') }}">
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('portal::salary.slips.index') }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive table-responsive-xl tg-steps-vacation-table">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>Slip</th>
                                <th class="text-center">Periode</th>
                                <th nowrap>THP (Rp)</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaries as $salary)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $employee->user->name }}</div>
                                        @isset($employee->position?->position)
                                            <div class="small text-muted">{{ $employee->position?->position->name ?? '' }}</div>
                                        @endisset
                                    </td>
                                    <td>{{ $salary->name }}</td>
                                    <td class="text-center">
                                        <div class="justify-content-center align-items-center d-flex">
                                            @if (!$salary->start_at->isSameDay($salary->end_at))
                                                <div class="">
                                                    <h6 class="mb-0">{{ $salary->start_at->format('d-M') }}</h6> <small class="text-muted">{{ $salary->start_at->format('Y') }}</small>
                                                </div>
                                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                            @endif
                                            <div class="">
                                                <h6 class="mb-0">{{ $salary->end_at->format('d-M') }}</h6> <small class="text-muted">{{ $salary->end_at->format('Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($salary->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($salary->accepted_at)
                                            <span class="badge bg-soft-success text-success"><i class="mdi mdi-check"></i> Ditandatangani</span>
                                        @else
                                            <span class="badge bg-soft-secondary text-dark"><i class="mdi mdi-clock-outline"></i> Belum tanda tangan</span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        @if ($salary->accepted_at)
                                            <a class="btn btn-soft-success rounded px-2 py-1" href="{{ route('portal::salary.slips.show', ['salary' => $salary->id]) }}" data-bs-toggle="tooltip" title="Cetak slip" target="_blank"><i class="mdi mdi-printer"></i></a>
                                        @endif
                                        <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('portal::salary.slips.edit', ['salary' => $salary->id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Lihat detail"><i class="mdi mdi-eye-outline"></i></a>
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
                    {{ $salaries->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div>

        </div>
    </div>
@endsection
