@extends('finance::layouts.default')

@section('title', 'Template gaji | ')
@section('navtitle', 'Template gaji')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar template gaji karyawan
                    </div>
                    <div class="table-responsive border-top border-light">
                        <div class="container">
                            @if(session('danger'))
                                <div class="alert alert-warning">
                                    {!! session('danger') !!}
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-warning">
                                    {!! session('success') !!}
                                </div>
                            @endif
                        </div>
                        
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
                                        <td>
                                            @isset($employee->position?->position)
                                                <span class="badge bg-dark fw-normal">{{ $employee->position?->position->name ?? '' }}</span>
                                            @endisset
                                        </td>
                                        <td class="py-2 text-end" nowrap>
                                            <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $employee->id }}">
                                                <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat daftar"><i class="mdi mdi-file-tree-outline"></i></button>
                                            </span>
                                            @can('store', Modules\HRMS\Models\EmployeeSalaryTemplate::class)
                                                <a class="btn btn-soft-success rounded px-2 py-1" href="{{ route('finance::payroll.templates.create', ['employee' => $employee->id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Tambah baru"><i class="mdi mdi-plus-circle-outline"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 p-0" colspan="5">
                                            <div class="collapse" id="collapse-{{ $employee->id }}">
                                                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                                                    <thead>
                                                        <tr class="text-muted small bg-light">
                                                            <th class="border-bottom fw-normal">Nama</th>
                                                            <th class="border-bottom fw-normal">Masa berlaku</th>
                                                            <th class="border-bottom fw-normal">Parent template</th>
                                                            <th class="border-bottom fw-normal text-center">Jumlah komponen tes</th>
                                                            <th class="border-bottom fw-normal"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($employee->salaryTemplates as $template)
                                                            <tr>
                                                                <td>{{ $template->name }}</td>
                                                                <td>{{ $template->start_at->isoFormat('LL') }} <span class="text-muted">s.d.</span> {{ $template->end_at?->isoFormat('LL') ?: '∞' }}</td>
                                                                <td>{{ $template->companyTemplate->name ?: '~' }}</td>
                                                                <td class="text-center">{{ $template->items_count }}</td>
                                                                <td class="py-2 pe-2 text-end" nowrap>
                                                                    @can('store', Modules\HRMS\Models\EmployeeSalaryTemplate::class)
                                                                        <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('finance::payroll.templates.show', ['template' => $template->id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil"></i></a>
                                                                    @endcan
                                                                    @can('destroy', $template)
                                                                        <form class="form-block form-confirm d-inline" action="{{ route('finance::payroll.templates.destroy', ['template' => $template->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                                            <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                                        </form>
                                                                    @endcan
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-muted">Tidak ada template gaji yang dibuat
                                                                    @can('store', Modules\HRMS\Models\EmployeeSalaryTemplate::class)
                                                                        , <a href="{{ route('finance::payroll.templates.create', ['employee' => $employee->id, 'next' => url()->current()]) }}">klik di sini</a> untuk menambahkan
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
                                                @can('store', Modules\HRMS\Models\EmployeeSalary::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('finance::payroll.templates.create', ['year' => request('year'), 'next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah template gaji baru</a>
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
                    <form class="form-block" action="{{ route('finance::payroll.templates.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required" for="year">Tahun</label>
                            <input type="number" min="1970" max="{{ date('Y', strtotime('+10 years')) }}" step="1" class="form-control" id="year" name="year" value="{{ request('year', date('Y')) }}" required>
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
                            <label class="form-label required" for="year">Pencarian</label>
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-magnify"></i> Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-dark" href="javascript:;" onclick="exportExcel()"><i class="mdi mdi-file-excel-outline"></i> Eksport template gaji</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('finance::payroll.templates.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat template gaji yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
    @include('finance::payroll.templates.components.excel-script')
@endpush
