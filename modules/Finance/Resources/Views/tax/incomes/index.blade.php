@extends('finance::layouts.default')

@section('title', 'Kelola bukti potong PPh 21 | ')
@section('navtitle', 'Kelola bukti potong PPh 21')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola bukti potong PPh21
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
                                <th>Kategori</th>
                                <th class="text-center">Periode</th>
                                <th nowrap>Nominal PPh</th>
                                <th nowrap class="text-center">Lampiran</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($taxs as $tax)
                                <tr @if ($tax->trashed()) class="text-muted" @endif>
                                    <td class="text-center" width="5%">{{ $loop->iteration + ($taxs->firstItem() - 1) }}</td>
                                    <td>
                                        {{ $tax->employee->user->name }}
                                        @if ($tax->released_at)
                                            <i class="mdi mdi-check-decagram text-primary me-n2" style="font-size:.75em;"></i>
                                        @endif
                                    </td>
                                    <td>{{ $tax->type->label() }}
                                    </td>
                                    <td nowrap class="text-center" width="1">
                                        <div class="justify-content-center align-items-center d-flex">
                                            <div class="">
                                                <h6 class="mb-0">{{ $tax->start_at->format('d-M') }}</h6> <small class="text-muted">{{ $tax->start_at->format('Y') }}</small>
                                            </div>
                                            <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                            <div class="">
                                                <h6 class="mb-0">{{ $tax->end_at->format('d-M') }}</h6> <small class="text-muted">{{ $tax->end_at->format('Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td nowrap>IDR {{ $tax->meta?->pay ?? ' - ' }}</td>
                                    <td class="text-center">
                                        @if (isset($tax->file) && Storage::exists($tax->file))
                                            <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($tax->file) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                        @endif
                                    </td>
                                    <td nowrap class="py-1 text-end">
                                        <a class="btn btn-soft-info btn-sm disabled rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat detail" href="{{ route('finance::tax.incomes.show', ['income' => $tax->id, 'next' => url()->full()]) }}"><i class="mdi mdi-eye-outline me-1"></i></a>
                                        <form class="form-block form-confirm d-inline" action="{{ route('finance::tax.incomes.destroy', ['income' => $tax->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                            <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                        </form>
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
                    {{ $taxs->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('finance::tax.incomes.index') }}" method="get">
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
                            <a class="btn btn-light" href="{{ route('finance::tax.incomes.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"> Menu lainnya</div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action py-3" href="{{ route('finance::tax.incomes.create', ['start_at' => request('start_at', $start_at), 'end_at' => request('end_at', $end_at), 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah bukti potong PPh 21</a>
                    {{-- <a class="list-group-item list-group-item-action py-3" href="{{ route('finance::tax.income-taxs.index', ['start_at' => request('start_at', $start_at), 'end_at' => request('end_at', $end_at)]) }}"><i class="mdi mdi-plus"></i> Tambah PPh 21</a> --}}
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Silakan filter tanggal yang digunakan terlebih dahulu.</small>
                </div>
            </div>
            <form class="form-block form-confirm mb-4 rounded bg-white" action="{{ route('finance::tax.incomes.release', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}" method="post"> @csrf
                <button class="btn btn-outline-secondary w-100 d-flex text-dark py-3 text-start" style="border-style: dashed;">
                    <i class="mdi mdi-cube-send me-3"></i>
                    <div>Rilis bukti potong PPh21 periode
                        @if ($start_at->isSameDay($end_at))
                            {{ $end_at->formatLocalized('%d %b %Y') }}
                        @else
                            {{ $start_at->formatLocalized('%d %b') }} s.d. {{ $end_at->formatLocalized('%d %b %Y') }}
                        @endif
                        <br> <small class="text-muted">Jika Kamu ingin merilis bukti potong PPh21 sesuai periode yang dipilih</small>
                    </div>
                </button>
            </form>
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
