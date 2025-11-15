@extends('administration::layouts.default')

@section('title', 'Kelola koordinator | ')
@section('navtitle', 'Kelola koordinator')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola koordinator
                </div>
                <div class="table-responsive">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Karyawan</th>
                                <th nowrap class="text-center">Periode</th>
                                <th nowrap class="text-center">Nominal</th>
                                <th nowrap>Tgl pengajuan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr @if ($employee->trashed()) class="text-muted" @endif>
                                    <td class="text-center" width="5%">{{ $loop->iteration + ($employees->firstItem() - 1) }}</td>
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
                                        @if (count($employee->recapSubmissions))
                                            {{ $employee->recapSubmissions->pluck('result.grand_total')->first() }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td nowrap>
                                        @if (count($employee->recapSubmissions))
                                            <div>{{ $employee->recapSubmissions ? $employee->recapSubmissions->first()->created_at->formatLocalized('%d %B %Y') : '' }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td nowrap class="py-1 text-end">
                                        @unless ($employee->trashed())
                                            @if (false)
                                                <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $employee->id }}">
                                                    <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                                </span>
                                            @endif

                                            @if (count($employee->recapSubmissions) == 0)
                                                <a class="btn btn-soft-info btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Rekap baru" href="{{ route('administration::summary.coords.create', ['employee' => $employee->id, 'start_at' => $start_at, 'end_at' => $end_at, 'next' => url()->full()]) }}"><i class="mdi mdi-plus-circle-outline"></i></a>
                                            @else
                                                @if (!empty($employee->recapSubmissions->first()->approvables->first()->result->value) &&$employee->recapSubmissions->first()->approvables->first()->result->value == 3)
                                                    <a class="btn btn-soft-warning btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat detail" href="{{ route('administration::summary.coords.show', ['employee' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'status' => 'edit', 'next' => url()->full()]) }}"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endif

                                                <a class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat detail" href="{{ route('administration::summary.coords.show', ['employee' => $employee->id, 'start_at' => $start_at->format('Y-m-d'), 'end_at' => $end_at->format('Y-m-d'), 'next' => url()->full()]) }}"><i class="mdi mdi-eye-outline"></i></a>
                                            @endif
                                            @if (count($employee->recapSubmissions) > 0)
                                                <form class="form-block form-confirm d-inline" action="{{ route('administration::summary.coords.destroy', ['employee' => $employee->id, 'next' => url()->full()]) }}" method="POST"> @csrf @method('delete')
                                                    <input type="hidden" name="start_at" value="{{ $start_at->format('Y-m-d') }}" />
                                                    <input type="hidden" name="end_at" value="{{ $end_at->format('Y-m-d') }}" />
                                                    <button type="submit" class="btn btn-soft-danger btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can"></i></button>
                                                </form>
                                            @endif
                                        @endunless
                                    </td>
                                </tr>
                                @if (false)
                                    @if ($employee->hasApprovables() && !$employee->trashed())
                                        <tr>
                                            <td class="p-0" colspan="100%">
                                                <div class="@if ($employee->hasAnyApprovableResultIn('PENDING')) show @endif collapse" id="collapse-{{ $employee->id }}">
                                                    <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                        <thead>
                                                            <tr class="text-muted small bg-light">
                                                                <th class="border-bottom fw-normal">Jenis</th>
                                                                <th class="border-bottom fw-normal" colspan="2">Persetujuan</th>
                                                                <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                                <th class="border-bottom fw-normal">Terakhir diperbarui</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($employee->approvables as $approvable)
                                                                <tr>
                                                                    <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                    <td @if ($loop->last) class="border-0" @endif>
                                                                        <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                    </td>
                                                                    <td class="small ps-0">{{ $approvable->reason }}</td>
                                                                    <td class="small">{{ $approvable->userable->getApproverLabel() }}</td>
                                                                    <td>{{ $approvable->updated_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
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
                    <form class="form-block" action="{{ route('administration::summary.coords.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode pengajuan</label>
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
                            <a class="btn btn-light" href="{{ route('administration::summary.coords.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <div class="list-group list-group-flush border-top">
                        <form class="form-block" action="{{ route('administration::summary.excel.coordinator.store') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label><b>Upload Data Koordinator</b></label>
                                    <input type="file" name="coordinatorFile" class="form-control" />
                                    <input type="hidden" name="start_at" value="{{$start_at->format('Y-m-d')}}" />
                                    <input type="hidden" name="end_at" value="{{$end_at->format('Y-m-d')}}" />
                                </div>

                                <div class="col-md-12 mt-2">
                                    <button type="submit" class="list-group-item list-group-item-action">
                                        <i class="mdi mdi-cloud-upload-outline"></i> Unggah Rekap
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi potongan</a>
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
