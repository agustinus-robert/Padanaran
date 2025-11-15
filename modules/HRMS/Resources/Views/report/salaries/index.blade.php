@extends('hrms::layouts.default')

@section('title', 'Penerbitan gaji | ')
@section('navtitle', 'Penerbitan gaji')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar karyawan
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
                                    <th class="text-center">Tgl terbit</th>
                                    <th class="text-center">Disetujui</th>
                                    <th class="text-center">Tgl rilis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    @php($salary = $employee->salaries->first())
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
                                                @if (!$start_at->isSameDay($end_at))
                                                    <div class="">
                                                        <h6 class="mb-0">{{ $start_at->format('d-M') }}</h6> <small class="text-muted">{{ $start_at->format('Y') }}</small>
                                                    </div>
                                                    <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                                @endif
                                                <div class="">
                                                    <h6 class="mb-0">{{ $end_at->format('d-M') }}</h6> <small class="text-muted">{{ $end_at->format('Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ isset($salary->amount) ? number_format($salary->amount, 0, ',', '.') : '-' }}</td>
                                        <td class="text-center">
                                            @isset($salary->validated_at)
                                                <span class="badge bg-light fw-normal text-dark">{{ $salary->validated_at->isoFormat('DD MMM YYYY') }}</span>
                                            @else
                                                -
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @isset($salary->approved_at)
                                                <span class="badge bg-soft-info fw-normal text-info">{{ $salary->approved_at->isoFormat('DD MMM YYYY') }}</span>
                                            @else
                                                -
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @isset($salary->released_at)
                                                <span class="badge bg-soft-success fw-normal text-success">{{ $salary->released_at->isoFormat('DD MMM YYYY') }}</span>
                                            @else
                                                -
                                            @endisset
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
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
                    <form class="form-block" action="{{ route('hrms::report.salaries.index') }}" method="get">
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
                            <label class="form-label">Pencarian</label>
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('hrms::report.salaries.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <button class="list-group-item list-group-item-action @if (!$employees->count()) disabled @endif py-3" onclick="exportExcel()"><i class="mdi mdi-file-excel-outline me-3"></i> Unduh laporan rincian penggajian</button>
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($end_at)) }}</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
    @include('hrms::report.salaries.components.excel-script')
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
