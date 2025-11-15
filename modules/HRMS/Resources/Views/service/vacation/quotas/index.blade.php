@extends('hrms::layouts.default')

@section('title', 'Distribusi cuti | ')
@section('navtitle', 'Distribusi cuti')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar distribusi cuti guru
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
                    
                    <div class="card-body border-top border-light">
                        <form class="form-block row gy-2 gx-2" action="{{ route('hrms::service.vacation.quotas.index') }}" method="get">
                            <input name="year" type="hidden" value="{{ request('year', date('Y')) }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('hrms::service.vacation.quotas.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                                    <th>Jabatan</th>
                                    <th nowrap class="text-center">Jumlah distribusi cuti</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    <tr>
                                        <td>{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                        <td width="10">
                                            <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                        </td>
                                        <td nowrap>
                                            <strong>{{ $employee->user->name ?? $employee->user->profile->name }}</strong> <br>
                                            @if ($employee->contract)
                                                <small class="text-muted"><i class="mdi mdi-circle {{ $employee->contract->is_active ? 'text-success' : 'text-danger' }}" style="font-size: 9pt;"></i> {{ $employee->contract->kd }}</small>
                                            @else
                                                <small class="text-muted"><i class="mdi mdi-circle text-secondary" style="font-size: 9pt;"></i> Tidak ada kontrak aktif</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($employee->contract && optional($employee->contract->position)->position)
                                                <span class="small badge bg-dark">
                                                    {{ optional(optional($employee->contract->position)->position)->name }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $employee->vacationQuotas->count() }}</td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($employee->contract)
                                                <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $employee->id }}">
                                                    <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat daftar"><i class="mdi mdi-file-tree-outline"></i></button>
                                                </span>
                                                @can('store', Modules\HRMS\Models\EmployeeVacationQuota::class)
                                                    <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('hrms::service.vacation.quotas.create', ['employee' => $employee->id, 'year' => $year, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Tambah baru"><i class="mdi mdi-plus-circle-outline"></i></a>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 p-0" colspan="5">
                                            <div class="collapse" id="collapse-{{ $employee->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th class="border-bottom fw-normal">Kategori</th>
                                                            <th class="border-bottom fw-normal">Masa berlaku</th>
                                                            <th class="border-bottom fw-normal">Kuota</th>
                                                            <th class="border-bottom fw-normal">Sisa</th>
                                                            <th class="border-bottom fw-normal"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($employee->vacationQuotas as $quota)
                                                            <tr>
                                                                <td>{{ $quota->category->name }}</td>
                                                                <td>{{ $quota->start_at->isoFormat('LL') }} <span class="text-muted">s.d.</span> {{ $quota->end_at?->isoFormat('LL') ?: '∞' }}</td>
                                                                <td>{{ $quota->quota ?: '∞' }} hari</td>
                                                                <td>{{ is_null($quota->quota) ? '∞' : abs($quota->quota - $quota->vacations->sum(fn($vacation) => count($vacation->dates))) }} hari</td>
                                                                <td class="py-2 pe-2 text-end" nowrap>
                                                                    @can('destroy', $quota)
                                                                        <form class="form-block form-confirm d-inline" action="{{ route('hrms::service.vacation.quotas.destroy', ['quota' => $quota->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                                            <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                                        </form>
                                                                    @endcan
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-muted">Tidak ada kuota cuti yang didistribusikan @can('store', Modules\HRMS\Models\EmployeeVacationQuota::class)
                                                                        , <a href="{{ route('hrms::service.vacation.quotas.create', ['employee' => $employee->id, 'next' => url()->current()]) }}">klik di sini</a> untuk menambahkan
                                                                    @endcan
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            @include('components.notfound')
                                            @if (!request('trash'))
                                                @can('store', Modules\HRMS\Models\EmployeeVacationQuota::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('hrms::service.vacation.quotas.create', ['year' => request('year'), 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah distribusi cuti baru</a>
                                                    </div>
                                                @endcan
                                            @endif
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
                    <form class="form-block" action="{{ route('hrms::service.vacation.quotas.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required" for="year">Tahun</label>
                            <input type="number" min="1970" max="{{ date('Y', strtotime('+10 years')) }}" step="1" class="form-control" id="year" name="year" value="{{ request('year', date('Y')) }}" required>
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-magnify"></i> Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            @if (false)
                <div class="card mb-3 border-0">
                    <div class="card-body">Menu lainnya</div>
                    <div class="list-group list-group-flush border-top border-light">
                        <a class="list-group-item list-group-item-action" href="{{ route('hrms::service.vacation.quotas.create', ['year' => request('year'), 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah distribusi cuti baru</a>
                    </div>
                </div>
            @endif
            @can('store', Modules\HRMS\Models\EmployeeVacationQuota::class)
                <form action="{{ route('hrms::service.vacation.quotas.batch-create', ['year' => request('year', date('Y')), 'next' => url()->full()]) }}" method="POST" class="form-block form-confirm">@csrf
                    <button class="btn btn-outline-secondary w-100 d-flex text-dark mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;">
                        <i class="mdi mdi-calendar-multiple-check me-3"></i>
                        <div>Distribusi cuti kolektif <br> <small class="text-muted">Terapkan distribusi kuota cuti otomatis untuk semua karyawan tahun {{ request('year', date('Y')) }}</small></div>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection
