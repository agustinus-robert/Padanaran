@extends('hrms::layouts.default')

@section('title', 'Gaji ke-13 | ')
@section('navtitle', 'Gaji ke-13')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Gaji ke-13
                </div>
                <div class="card-body border-top">
                    <form class="form-block row gy-2 gx-2" action="{{ route('hrms::summary.postyears.index', request()->all()) }}" method="get">
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
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('hrms::summary.postyears.index', request()->only('trashed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Nama</th>
                                <th class="text-center">Periode</th>
                                <th class="text-center">THP (Rp)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td width="10">{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                    <td width="10">
                                        <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                    </td>
                                    <td nowrap>
                                        <div class="fw-bold">{{ $employee->user->name }}</div>
                                        @isset($employee->position?->position)
                                            <div class="small text-muted">{{ $employee->position?->position->name ?? '' }}</div>
                                        @endisset
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
                                        @if ($total = $employee->dataRecapitulations->sum('result.total'))
                                            {{ number_format($total, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-2 text-end" nowrap>
                                        <a class="btn {{ $total ? 'btn-soft-warning' : 'btn-soft-primary' }} rounded px-2 py-1" href="{{ route('hrms::summary.postyears.create', ['employee' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Hitung"><i class="mdi {{ $total ? 'mdi-pencil-outline' : 'mdi-plus-circle-outline' }}"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        @include('components.notfound')
                                    </td>
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
                    <form class="form-block" action="{{ route('hrms::summary.postyears.index') }}" method="get">
                        <input class="d-none" type="date" name="start_at" value="{{ $start_at->format('Y-m-d') }}" required>
                        <input class="d-none" type="date" name="end_at" value="{{ $end_at->format('Y-m-d') }}" required>
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
                            <a class="btn btn-light" href="{{ route('hrms::summary.postyears.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
