@extends('hrms::layouts.default')

@section('title', 'Asuransi | ')
@section('navtitle', 'Asuransi')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar asuransi karyawan
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row gy-2 gx-2" action="{{ route('hrms::benefit.insurances.registrations.index') }}" method="get">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('hrms::benefit.insurances.registrations.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                            </div>
                        </form>
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
                                    <th>Jabatan saat ini</th>
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
                                            <strong>{{ $employee->user->name }}</strong>
                                        </td>
                                        <td><span class="badge bg-dark fw-normal">{{ $employee->position->position->name }}</span></td>
                                        <td class="py-2 text-end" nowrap>
                                            <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $employee->id }}">
                                                <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat daftar"><i class="mdi mdi-file-tree-outline"></i></button>
                                            </span>
                                            @can('store', Modules\HRMS\Models\EmployeeInsurance::class)
                                                <a class="btn @empty($employee->salaryTemplate) disabled btn-secondary @else  btn-soft-primary @endempty rounded px-2 py-1" href="{{ route('hrms::benefit.insurances.registrations.create', ['employee' => $employee->id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Tambah baru"><i class="mdi mdi-plus-circle-outline"></i></a>
                                            @endcan
                                            <form class="form-block form-confirm d-inline" action="{{ route('hrms::benefit.insurances.registrations.reset', ['employee' => $employee->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                <button class="btn btn-soft-warning rounded px-2 py-1" data-bs-toggle="tooltip" title="Reset data BPJS"><i class="mdi mdi-sync"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 p-0" colspan="5">
                                            <div class="collapse" id="collapse-{{ $employee->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th class="border-bottom fw-normal" width="5%"></th>
                                                            <th class="border-bottom fw-normal">Jenis asuransi</th>
                                                            <th class="border-bottom fw-normal">Gaji pokok</th>
                                                            <th class="border-bottom fw-normal">Tarif Perusahaan</th>
                                                            <th class="border-bottom fw-normal">Tarif karyawan</th>
                                                            <th class="border-bottom fw-normal"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($employee->insurances as $insurance)
                                                            <tr>
                                                                <td width="5%"></td>
                                                                <td>
                                                                    {{ $insurance->price->insurance->name }}
                                                                    <div class="text-muted">{{ $insurance->price->insurance_id == 1 ? $insurance->price->conditions['group'][0] : $insurance->price['conditions']['services'][0] }}</div>
                                                                </td>
                                                                <td>
                                                                    Rp {{ \Str::money($insurance->meta['cmp_factor']) }}
                                                                </td>
                                                                <td>Rp {{ \Str::money($insurance->cmp_price) }}</td>
                                                                <td>Rp {{ \Str::money($insurance->empl_price) }}</td>
                                                                <td class="py-2 pe-2 text-end" nowrap>
                                                                    @can('destroy', $insurance)
                                                                        <form class="form-block form-confirm d-inline" action="{{ route('hrms::benefit.insurances.registrations.destroy', ['insurance' => $insurance->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                                            <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                                        </form>
                                                                    @endcan
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-muted">Belum ada data asuransi karyawan,
                                                                    @if ($employee->salaryTemplate)
                                                                        @can('store', Modules\HRMS\Models\EmployeeInsurance::class)
                                                                            <a href="{{ route('hrms::benefit.insurances.registrations.create', ['employee' => $employee->id, 'next' => url()->current()]) }}">klik di sini</a> untuk menambahkan
                                                                        @endcan
                                                                    @endif
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
                                                @can('store', Modules\HRMS\Models\EmployeeInsurance::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('hrms::benefit.insurances.registrations.create', ['year' => request('year'), 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah distribusi cuti baru</a>
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
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $employees->count() }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah karyawan</div>
                </div>
                <div><i class="mdi mdi-account mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-format-list-bulleted"></i> Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('hrms::benefit.insurances.registrations.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat asuransi yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body"><i class="mdi mdi-cog"></i> Opsi</div>
                <div class="card-body border-top">
                    <div class="mb-3">Standar maksimum gaji BPJS jaminan pensiun</div>
                    <form class="form-block form-confirm" action="{{ route('hrms::benefit.insurances.registrations.max-salary', ['next' => url()->full()]) }}" method="POST" enctype="multipart/form-data">@csrf
                        <div class="mb-3">
                            <label for="max" class="form-label required">Maksimal gaji di BPSJ Jaminan Pensiun</label>
                            <div class="input-group">
                                <div class="input-group-text">Rp</div>
                                <input class="form-control" type="number" min="0" step="1" name="max_salary" id="max" value="{{ setting('cmp_insurance_max_salary') ?? (config('modules.hrms.features.benefit.insurance.maxSalary') ?? 0) }}">
                            </div>
                        </div>
                        <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
