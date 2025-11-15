@extends('finance::layouts.default')

@section('title', 'Penghitungan gaji | ')
@section('navtitle', 'Penghitungan gaji')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar karyawan
                    </div>
                    <div class="table-responsive border-top border-light">
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
                                    @php($amount = $employee->salaries->sum('amount'))
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
                                        <td nowrap class="text-center">
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
                                        <td class="text-center">{{ $amount ? number_format($amount, 0, ',', '.') : '-' }}</td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($amount)
                                                <a class="btn btn-soft-success rounded px-2 py-1" href="{{ route('finance::payroll.calculations.show', ['salary' => $employee->salaries->first()->id]) }}" data-bs-toggle="tooltip" title="Cetak slip" target="_blank"><i class="mdi mdi-printer"></i></a>
                                            @endif
                                            <a class="btn {{ $amount ? 'btn-soft-warning' : 'btn-soft-primary' }} rounded px-2 py-1" href="{{ route('finance::payroll.calculations.create', ['employee' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Hitung"><i class="mdi {{ $amount ? 'mdi-pencil-outline' : 'mdi-plus-circle-outline' }}"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
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
            </section>
        </div>
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('finance::payroll.calculations.index') }}" method="get">
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
                            <select class="form-select" id="select-positions" name="position_id">
                                <option value>Semua jabatan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Agama</label>
                            <div class="card card-body px-3 py-2">
                                @foreach ($religions as $label => $religion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="religions[]" id="religions-{{ $religion->value }}" value="{{ $religion->value }}" @checked(in_array($religion->value, request('religions', array_map(fn($religion) => $religion->value, $religions))))>
                                        <label class="form-check-label" for="religions-{{ $religion->value }}">{{ $religion->label() }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pencarian</label>
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('finance::payroll.calculations.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-dark" href="javascript:;"><i class="mdi mdi-clipboard-list-outline"></i> Lihat daftar penggajian</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            let listDepartmentId = () => {
                [].slice.call(document.querySelectorAll('[name="department"] option:checked')).map((select) => {
                    let c = '';
                    if (select.dataset.positions) {
                        let possition = JSON.parse(select.dataset.positions);
                        for (i in possition) {
                            c += '<option value="' + i + '" ' + (('{{ old('possition_id', -1) }}' == i) ? ' selected' : '') + '>' + possition[i] + '</option>';
                        }
                    }
                    document.querySelector('[name="position_id"]').innerHTML = c.length ? c : '<option value>Semua jabatan</option>'
                })
            }

            [].slice.call(document.querySelectorAll('[name="department"]')).map((el) => {
                el.addEventListener('change', listDepartmentId);
            });
            listDepartmentId();
        });
    </script>
@endpush
