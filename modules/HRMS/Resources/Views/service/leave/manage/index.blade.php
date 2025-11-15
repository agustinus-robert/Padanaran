@extends('hrms::layouts.default')

@section('title', 'Kelola izin | ')
@section('navtitle', 'Kelola izin')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body border-bottom">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola izin karyawan
                </div>

                <div class="col-12 p-2">
                        <div class="container">
                            @if (Session::has('success'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert alert-success">
                                        {!! Session::get('success') !!}
                                    </div>
                                </div>
                            @endif 

                            @if (Session::has('danger'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert-danger alert">
                                        {!! Session::get('danger') !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                <div class="table-responsive">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th nowrap>Tgl pengajuan</th>
                                <th nowrap>Waktu izin</th>
                                <th class="text-center">Lampiran</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                                <tr>
                                    <td>{{ $loop->iteration + $leaves->firstItem() - 1 }}</td>
                                    <td width="10">
                                        <div class="rounded-circle" style="background: url('{{ $leave->employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                    </td>
                                    <td nowrap>
                                        <strong class="d-block">{{ $leave->employee->user->name }}</strong>
                                        <small class="text-muted">{{ $leave->employee->contract->position->position->name ?? 'Belum ada perjanjian kerja' }}</small>
                                    </td>
                                    <td style="min-width: 200px;" class="py-3">
                                        <div>{{ $leave->category->name }}</div>
                                        <small class="text-muted">{{ $leave->description }}</small>
                                    </td>
                                    <td class="small">{{ $leave->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td style="min-width: 200px;">
                                        @foreach (collect($leave->dates)->take(3) as $date)
                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}">
                                                @isset($date['f'])
                                                    <i class="mdi mdi-account-network-outline text-danger"></i>
                                                    @endisset {{ strftime('%d %B %Y', strtotime($date['d'])) }} @isset($date['t_s'])
                                                    pukul {{ $date['t_s'] }}
                                                    @endisset @isset($date['t_e'])
                                                    s.d. {{ $date['t_e'] }}
                                                @endisset
                                            </span>
                                        @endforeach
                                        @php($remain = collect($leave->dates)->count() - 3)
                                        @if ($remain > 0)
                                            <span class="badge text-dark fw-normal user-select-none">+{{ $remain }} lainnya</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (isset($leave->attachment) && Storage::exists($leave->attachment))
                                            <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($leave->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                        @endif
                                    </td>
                                    <td nowrap> @include('portal::leave.components.status', ['leave' => $leave]) </td>
                                    <td nowrap class="py-1 text-end">
                                        @unless ($leave->trashed())
                                            @if ($leave->hasApprovables())
                                                <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $leave->id }}">
                                                    <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                                </span>
                                            @endif
                                            <a class="btn btn-soft-info btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat detail" href="{{ route('hrms::service.leave.manage.show', ['leave' => $leave->id, 'next' => url()->current()]) }}"><i class="mdi mdi-eye-outline me-1"></i></a>
                                            <a class="btn btn-soft-success btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Cetak dokumen (.pdf)" href="{{ route('hrms::service.leave.manage.print', ['leave' => $leave->id]) }}" target="_blank"><i class="mdi mdi-link me-1"></i></a>
                                        @endunless
                                    </td>
                                </tr>
                                @if ($leave->hasApprovables() && !$leave->trashed())
                                    <tr>
                                        <td class="p-0" colspan="9">
                                            <div class="@if ($leave->hasAnyApprovableResultIn('PENDING')) show @endif collapse" id="collapse-{{ $leave->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th class="border-bottom fw-normal">Jenis</th>
                                                            <th class="border-bottom fw-normal" colspan="2">Persetujuan</th>
                                                            <th class="border-bottom fw-normal">Penanggungjawab</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($leave->approvables as $approvable)
                                                            <tr>
                                                                <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                <td @if ($loop->last) class="border-0" @endif>
                                                                    <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                </td>
                                                                <td class="small ps-0">{{ $approvable->reason }}</td>
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
                                    <td colspan="9">@include('components.notfound')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $leaves->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.leave.manage.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                    <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                    <span class="d-none d-sm-inline">Rentang waktu</span>
                                </button>
                                <input class="form-control" type="date" name="start_at" value="{{ $start_at }}" required>
                                <input class="form-control" type="date" name="end_at" value="{{ $end_at }}" required>
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
                            <label class="form-label" for="select-positions">Nama</label>
                            <input class="form-control" name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                                <label class="form-check-label" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('hrms::service.leave.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi izin</a>
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni mulai tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($start_at)) }}</small>
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
