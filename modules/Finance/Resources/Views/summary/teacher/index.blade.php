@extends('finance::layouts.default')

@section('title', 'Rekapitulasi kehadiran | ')
@section('navtitle', 'Rekapitulasi kehadiran')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::dashboard-msdm.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Rekapitulasi mengajar</h2>
            <div class="text-muted">Jangan lupa isi rekapitulasi mengajar untuk para Guru, mereka adalah pahlawan tanpa tanda jasa!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Rekapitulasi kehadiran karyawan
                </div>
                <div class="table-responsive border-top">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Nama</th>
                                <th class="text-center">Periode</th>
                                <th class="text-center">Total Mengajar</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                @php($summary = $summaries->where('empl_id', $employee->id))
                                @php($ids = $summary->pluck('id')->first())

                                <tr @class(['table-active text-muted' => is_null($employee->contract)])>
                                    <td width="1">{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                    <td width="10">
                                        <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                    </td>
                                    <td nowrap>
                                        <div class="fw-bold text-truncate">{{ $employee->user->name }}</div>
                                        <div class="small text-muted text-truncate">{{ $employee->contract->position?->position->name ?? '' }}</div>
                                    </td>
                                    <td nowrap class="text-center" width="1">
                                        <div class="justify-content-center align-items-center d-flex">
                                            <div class="">
                                                <h6 class="mb-0">{{ $start_at->format('d-M') }}</h6> <small class="text-muted">{{ $start_at->format('Y') }}</small>
                                            </div>
                                            <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                            <div class="">
                                                <h6 class="mb-0">{{ $end_at->format('d-M') }}</h6> <small class="text-muted">{{ $end_at->format('Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($summary->count())
                                            {{ $summary->first()->result->amount_total }}
                                        @else
                                            <span class="text-muted">&dash;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($summary->count())
                                            <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('finance::summary.teachings.show', ['teaching' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Persetujuan"><i class="mdi mdi-pencil-outline"></i></a>
                                        @else
                                            <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('finance::summary.teachings.create', ['teaching' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Rekap baru"><i class="mdi mdi-plus-circle-outline"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">@include('components.notfound')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $employees->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('finance::summary.teachings.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <div class="flex-grow-1 col-auto">
                                <div class="input-group">
                                    <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                        <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                        <span class="d-none d-sm-inline">Rentang waktu</span>
                                    </button>
                                    <input class="form-control" type="date" name="start_at" value="{{ $start_at->format('Y-m-d') }}" required>
                                    <input class="form-control" type="date" name="end_at" value="{{ $end_at->format('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="select-departments">Departemen</label>
                            <select class="form-select" id="select-departments" name="department">
                                <option value>Semua departemen</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected(request('department') == $department->id) data-positions="{{ $department->positions->pluck('name', 'id') }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="select-positions">Jabatan</label>
                            <select class="form-select" id="select-positions" name="position">
                                <option value>Semua jabatan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pencarian</label>
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('finance::summary.teachings.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script>
        const renderPositions = () => {
            let department = document.querySelector('#select-departments option:checked');
            let option = '<option value>Semua jabatan</option>';
            let selected = '{{ request('position') }}';
            if (department.dataset.positions) {
                let pos = JSON.parse(department.dataset.positions);
                Object.keys(pos).forEach((id) => {
                    option += `<option value="${id}" ` + (selected == id ? 'selected="selected"' : '') + `)>${pos[id]}</option>`
                })
            }
            document.getElementById('select-positions').innerHTML = option;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('select-departments').addEventListener('change', renderPositions);
            renderPositions();
        });
    </script>
@endpush
