@extends('finance::layouts.default')

@section('title', 'Kelola PPh 21 | ')
@section('navtitle', 'Kelola PPh 21')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola PPh 21 karyawan
                </div>
                @if (request('pending'))
                    <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                        Hanya menampilkan pengajuan yang masih tertunda/berstatus <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-timer-outline"></i> Menunggu</div>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Karyawan</th>
                                <th class="text-center">Periode</th>
                                <th class="text-center">THP</th>
                                <th class="text-center">Nominal PPh</th>
                                <th nowrap class="text-center">Lampiran</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                @php($salary = $employee->salaries->first())
                                @php($pph = $employee->taxs->first())
                                <tr @if ($employee->trashed()) class="text-muted" @endif>
                                    <td class="text-center" width="5%">{{ $loop->iteration + ($employees->firstItem() - 1) }}</td>
                                    <td>
                                        {{ $employee->user->name }}
                                        @if (isset($pph->released_at))
                                            <i class="mdi mdi-check-decagram text-primary me-n2" style="font-size:.75em;"></i>
                                        @endif
                                    </td>
                                    </td>
                                    <td nowrap class="text-center" width="1">
                                        <div class="justify-content-center align-items-center d-flex">
                                            <div class="">
                                                <h6 class="mb-0">{{ isset($salary->start_at) ? $salary->start_at->format('d-M') : '' }}</h6> <small class="text-muted">{{ isset($salary->start_at) ? $salary->start_at->format('Y') : '' }}</small>
                                            </div>
                                            <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                            <div class="">
                                                <h6 class="mb-0">{{ isset($salary->end_at) ? $salary->end_at->format('d-M') : '' }}</h6> <small class="text-muted">{{ isset($salary->end_at) ? $salary->end_at->format('Y') : '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center" nowrap>Rp{{ isset($salary->amount) ? number_format($salary->amount, 0, ',', '.') : '-' }}</td>
                                    <td class="text-center" nowrap>Rp{{ isset($pph->meta->pphtotal) ? number_format($pph->meta->pphtotal, 0, ',', '.') : '-' }}</td>
                                    <td class="text-center">
                                        @if (isset($employee->file) && Storage::exists($employee->file))
                                            <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($employee->file) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                        @endif
                                    </td>
                                    <td nowrap class="py-1 text-end">
                                        @if (!is_null($salary) && !$pph)
                                            <a class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Tambah penghitungan pph21" href="{{ route('finance::tax.income-taxs.create', ['tax' => $employee->id, 'start_at' => request('start_at', $start_at), 'end_at' => request('end_at', $end_at), 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i></a>
                                        @endif
                                        @if (!is_null($pph))
                                            <form class="form-block form-confirm d-inline" action="{{ route('finance::tax.income-taxs.destroy', ['income_tax' => $pph->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </form>
                                        @endif
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
                    <form class="form-block" action="{{ route('finance::tax.income-taxs.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode pph</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                    <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                    <span class="d-none d-sm-inline">Rentang waktu</span>
                                </button>
                                <input class="form-control" type="date" name="start_at" value="{{ date('Y-m-d', strtotime($start_at)) }}" required>
                                <input class="form-control" type="date" name="end_at" value="{{ date('Y-m-d', strtotime($end_at)) }}" required>
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
                            <label class="form-label" for="select-positions">Karyawan</label>
                            <input class="form-control" name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" />
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                                <label class="form-check-label" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('finance::tax.income-taxs.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <form class="form-block form-confirm mb-4 rounded bg-white" action="{{ route('finance::tax.income-taxs.release', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}" method="post"> @csrf
                <button class="btn btn-outline-secondary w-100 d-flex text-dark py-3 text-start" style="border-style: dashed;">
                    <i class="mdi mdi-cube-send me-3"></i>
                    <div>Rilis PPh21 periode
                        @if ($start_at->isSameDay($end_at))
                            {{ $end_at->formatLocalized('%d %b %Y') }}
                        @else
                            {{ $start_at->formatLocalized('%d %b') }} s.d. {{ $end_at->formatLocalized('%d %b %Y') }}
                        @endif
                        <br> <small class="text-muted">Jika Kamu ingin merilis PPh21 sesuai periode yang dipilih</small>
                    </div>
                </button>
            </form>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action @if (!$employees->count()) disabled @endif py-3" onclick="summaryExportExcel()" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi PPh21</a>
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
    @include('finance::tax.pph.components.summary-excel-script')
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
