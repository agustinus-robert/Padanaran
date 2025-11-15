@extends('finance::layouts.default')

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
                    <div class="table-responsive border-top border-light">
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
                                    <th></th>
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
                                        <td class="py-2 text-end" nowrap>
                                            @isset($salary->amount)
                                                <a class="btn btn-soft-info rounded px-2 py-1" href="{{ route('finance::payroll.tax-issues.show', ['salary' => $salary->id]) }}" data-bs-toggle="tooltip" title="Penerbitan PPh" target=""><i class="mdi mdi-plus-circle-outline"></i></a>
                                                <div class="dropstart d-inline">
                                                    <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                    <ul class="dropdown-menu border-0 shadow">
                                                        <li><a class="dropdown-item" style="cursor: pointer;" href="{{ route('finance::payroll.validations.edit', ['salary' => $salary->id, 'next' => url()->full()]) }}" title="Lihat detail"><i class="mdi mdi-eye-outline"></i> Lihat detail</a></li>
                                                        <li><a class="dropdown-item" style="cursor: pointer;" href="{{ route('finance::payroll.validations.show', ['salary' => $salary->id]) }}" title="Cetak slip" target="_blank"><i class="mdi mdi-printer"></i> Cetak slip</a></li>
                                                    </ul>
                                                </div>
                                            @endisset
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">
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
                    <form class="form-block" action="{{ route('finance::payroll.validations.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                    <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                    <span class="d-none d-sm-inline">Rentang waktu</span>
                                </button>
                                <input class="form-control" type="date" name="start_at" value="{{ $start_at->format('Y-m-d') }}" required>
                                <input class="form-control" type="date" name="end_at" value="{{ $end_at->format('Y-m-d') }}" required>
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
                            <a class="btn btn-light" href="{{ route('finance::payroll.validations.index', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <form class="form-block form-confirm mb-4 rounded bg-white" action="{{ route('finance::payroll.validation-options.notify-director', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}" method="post"> @csrf
                <button class="btn btn-outline-secondary w-100 d-flex text-dark py-3 text-start" style="border-style: dashed;">
                    <i class="mdi mdi-email-alert-outline me-3"></i>
                    <div>Kirim notifikasi persetujuan gaji periode
                        @if ($start_at->isSameDay($end_at))
                            {{ $end_at->formatLocalized('%d %b %Y') }}
                        @else
                            {{ $start_at->formatLocalized('%d %b') }} s.d. {{ $end_at->formatLocalized('%d %b %Y') }}
                        @endif
                        ke Direktur
                        <br> <small class="text-muted">Jika Kamu ingin memberitahu Direktur untuk persetujuan tanda tangan gaji sesuai periode yang dipilih</small>
                    </div>
                </button>
            </form>

            <form class="form-block form-confirm mb-4 rounded bg-white" action="{{ route('finance::payroll.validation-options.release', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}" method="post"> @csrf
                <button class="btn btn-outline-secondary w-100 d-flex text-dark {{ $hasApprovedSalaries ? '' : 'disabled' }} py-3 text-start" style="border-style: dashed;">
                    <i class="mdi mdi-cube-send me-3"></i>
                    <div>Rilis gaji periode
                        @if ($start_at->isSameDay($end_at))
                            {{ $end_at->formatLocalized('%d %b %Y') }}
                        @else
                            {{ $start_at->formatLocalized('%d %b') }} s.d. {{ $end_at->formatLocalized('%d %b %Y') }}
                        @endif
                        <br> <small class="text-muted">Jika Kamu ingin merilis gaji sesuai periode yang dipilih</small>
                    </div>
                </button>
            </form>
            <form class="form-block form-confirm mb-4 rounded bg-white" action="{{ route('finance::payroll.validation-options.notify-employees', ['start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d')]) }}" method="post"> @csrf
                <button class="btn btn-outline-secondary w-100 d-flex text-dark {{ $hasReleasedSalaries ? '' : 'disabled' }} py-3 text-start" style="border-style: dashed;">
                    <i class="mdi mdi-bell-alert-outline me-3"></i>
                    <div>Kirim notifikasi penggajian periode
                        @if ($start_at->isSameDay($end_at))
                            {{ $end_at->formatLocalized('%d %b %Y') }}
                        @else
                            {{ $start_at->formatLocalized('%d %b') }} s.d. {{ $end_at->formatLocalized('%d %b %Y') }}
                        @endif untuk karyawan
                        <br> <small class="text-muted">Jika Kamu ingin memberitahu seluruh karyawan mengenai gaji yang sudah dirilis sesuai periode yang dipilih</small>
                    </div>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
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
