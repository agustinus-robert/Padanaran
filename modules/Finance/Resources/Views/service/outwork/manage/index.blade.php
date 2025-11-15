@extends('finance::layouts.default')

@section('title', 'Kelola kegiatan lainnya | ')
@section('navtitle', 'Kelola kegiatan lainnya')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola kegiatan lainnya
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
                                <th width="20%">Karyawan</th>
                                <th width="25%">Kegiatan</th>
                                <th nowrap>Tgl dan waktu</th>
                                <th class="text-center">Lampiran</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outworks as $outwork)
                                <tr @if ($outwork->trashed()) class="text-muted" @endif>
                                    <td class="text-center" width="5%">{{ $loop->iteration + ($outworks->firstItem() - 1) }}</td>
                                    <td class="fw-bold">{{ $outwork->employee->user->name }}</td>
                                    <td class="py-3">
                                        <div class="fw-bold">{{ $outwork->category->name }}</div>
                                        <div>{{ $outwork->name }}</div>
                                        <div class="small text-muted">{{ Str::words($outwork->description, 6) }}</div>
                                        <div class="small cite badge bg-soft-secondary fw-normal text-dark">Diajukan: {{ $outwork->created_at->formatLocalized('%d %B %Y') }}</div>
                                    </td>
                                    <td style="max-width: 280px;" class="small">
                                        @foreach ($outwork->dates->take(3) as $date)
                                            <span class="badge bg-soft-secondary text-dark fw-normal user-select-none {{ isset($date['c']) ? 'text-decoration-line-through' : '' }}" @isset($date['f']) data-bs-toggle="tooltip" title="Sebagai freelancer" @endisset>
                                                @isset($date['f'])
                                                    <i class="mdi mdi-account-network-outline text-danger"></i>
                                                @endisset
                                                {{ strftime('%d %B %Y', strtotime($date['d'])) }}
                                                @isset($date['t_s'])
                                                    pukul {{ $date['t_s'] }}
                                                @endisset
                                                @isset($date['t_e'])
                                                    s.d. {{ $date['t_e'] }}
                                                @endisset
                                            </span>
                                        @endforeach
                                        @php($remain = $outwork->dates->count() - 3)
                                        @if ($remain > 0)
                                            <span class="badge text-dark fw-normal user-select-none">+{{ $remain }} lainnya</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (isset($outwork->attachment) && Storage::exists($outwork->attachment))
                                            <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($outwork->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                        @endif
                                    </td>
                                    <td nowrap>@include('portal::outwork.components.status', ['outwork' => $outwork])</td>
                                    <td nowrap class="py-1 text-end">
                                        @unless ($outwork->trashed())
                                            @if ($outwork->hasApprovables())
                                                <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $outwork->id }}">
                                                    <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Status pengajuan"><i class="mdi mdi-progress-clock"></i></button>
                                                </span>
                                            @endif
                                            <div class="dropstart d-inline">
                                                <button class="btn btn-soft-secondary text-dark rounded px-2 py-1" type="button" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
                                                <ul class="dropdown-menu border-0 shadow">
                                                    <li><a class="dropdown-item" href="{{ route('finance::service.outwork.manage.show', ['outwork' => $outwork->id, 'next' => request('next')]) }}"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>
                                                    @if (isset($outwork->attachment) && Storage::exists($outwork->attachment))
                                                        <li><a class="dropdown-item" href="{{ Storage::url($outwork->attachment) }}" target="_blank"><i class="mdi mdi-file-link-outline me-1"></i> Lihat lampiran</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endunless
                                    </td>
                                </tr>
                                @if ($outwork->hasApprovables() && !$outwork->trashed())
                                    <tr>
                                        <td class="p-0" colspan="8">
                                            <div class="@if ($outwork->hasAnyApprovableResultIn('PENDING')) show @endif collapse" id="collapse-{{ $outwork->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th class="text-center" width="5%"></th>
                                                            <th class="border-bottom fw-normal" width="20%">Jenis</th>
                                                            <th class="border-bottom fw-normal" colspan="2" width="25%">Persetujuan</th>
                                                            <th class="border-bottom fw-normal" width="30%">Penanggungjawab</th>
                                                            <th class="border-bottom fw-normal">Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($outwork->approvables as $approvable)
                                                            <tr>
                                                                <td></td>
                                                                <td class="small {{ $approvable->cancelable ? 'text-danger' : 'text-muted' }}">{{ ucfirst($approvable->type) }} #{{ $approvable->level }}</td>
                                                                <td @if ($loop->last) class="border-0" @endif>
                                                                    <div class="badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                                                                </td>
                                                                <td class="small ps-0">{{ $approvable->reason }}</td>
                                                                <td class="small">{{ $approvable->userable->getApproverLabel() }}</td>
                                                                <td class="small">{{ $approvable->result != Modules\Core\Enums\ApprovableResultEnum::PENDING ? $approvable->updated_at->isoFormat('LLL') : '-' }}</td>
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
                                    <td colspan="5">@include('components.notfound')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $outworks->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('finance::service.outwork.manage.index') }}" method="get">
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
                            <a class="btn btn-light" href="{{ route('finance::service.outwork.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                    <div>
                        <div class="display-4">{{ $pending_outworks_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Jumlah pengajuan tertunda</div>
                    </div>
                    <div><i class="mdi mdi-timer-outline mdi-48px text-muted"></i></div>
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('finance::service.outwork.manage.index', ['pending' => !request('pending')]) }}"><i class="mdi mdi-progress-clock"></i> {{ request('pending') == 1 ? 'Tampilkan semua pengajuan' : 'Hanya tampilkan pengajuan yang tertunda' }}</a>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    Menu lainnya
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action py-3" href="{{ route('finance::service.outwork.manage.create', ['next' => route('finance::service.outwork.manage.index')]) }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah pengajuan kegiatan lainnya</a>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi kegiatan lainnya</a>
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($start_at)) }}</small>
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
