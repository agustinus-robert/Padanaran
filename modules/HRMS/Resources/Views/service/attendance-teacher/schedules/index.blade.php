@extends('hrms::layouts.default')

@section('title', 'Jadwal kerja | ')
@section('navtitle', 'Jadwal kerja')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar jadwal kerja karyawan
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
                    
                    <div class="table-responsive border-top border-light">
                        <table class="table-hover mb-0 table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Nama</th>
                                    <th>Periode</th>
                                    <th class="text-center">Jumlah hari kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    @php($schedule = $employee->schedulesTeachers->first())
                                    <tr @class(['table-active' => is_null($employee->contract)])>
                                        <td>{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                        <td width="10">
                                            <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                        </td>
                                        <td nowrap>
                                            <strong>{{ $employee->user->name }}</strong> <br>
                                            <small class="text-muted">{{ $employee->contract->position?->position->name ?? '' }}</small>
                                        </td>
                                        <td>{{ strftime('%B %Y', strtotime(request('month', date('Y-m')))) }}</td>
                                        <td class="text-center">{{ $schedule?->workdays_count ?: '-' }}</td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($employee->contract)
                                                @if ($schedule)
                                                    @can('show', $schedule)
                                                        <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('hrms::service.attendance-teacher.schedules.show', ['schedule' => $schedule->id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                    @endcan
                                                @else
                                                    @can('store', Modules\HRMS\Models\EmployeeScheduleTeacher::class)
                                                        <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('hrms::service.attendance-teacher.schedules.create', ['employee' => $employee->id, 'month' => request('month', date('Y-m')), 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Buat baru"><i class="mdi mdi-plus-circle-outline"></i></a>
                                                    @endcan
                                                @endif
                                                @can('destroy', $schedule)
                                                    <form class="form-block form-confirm d-inline" action="{{ route('hrms::service.attendance-teacher.schedules.destroy', ['schedule' => $schedule->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                        <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            @include('components.notfound')
                                            @if (!request('trash'))
                                                @can('store', Modules\HRMS\Models\EmployeeScheduleTeacher::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('hrms::service.attendance-teacher.schedules.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Buat jadwal kerja baru</a>
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
                    <form class="form-block" action="{{ route('hrms::service.attendance-teacher.schedules.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required" for="month">Periode</label>
                            <input type="month" class="form-control" id="month" name="month" value="{{ request('month', date('Y-m')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="month">Pencarian</label>
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-magnify"></i> Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            @can('store', Modules\HRMS\Models\EmployeeScheduleTeacher::class)
                <a class="btn btn-outline-secondary w-100 d-flex text-dark mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('hrms::service.attendance-teacher.schedules.collective.create', ['month' => request('month', date('Y-m')), 'next' => url()->full()]) }}">
                    <i class="mdi mdi-calendar-multiple-check me-3"></i>
                    <div>Input jadwal kerja kolektif <br> <small class="text-muted">Jika Kamu ingin meregistrasikan 1 jadwal ke banyak guru</small></div>
                </a>
            @endcan
            @can('store', Modules\HRMS\Models\EmployeeSchedule::class)
                <form action="{{ route('hrms::service.attendance-teacher.schedules.generate', ['next' => url()->full()]) }}" method="POST" class="form-block form-confirm">@csrf
                    <input type="hidden" name="target_month" class="form-control d-none" value="{{ request('month', date('Y-m')) }}">
                    <button class="btn btn-outline-secondary w-100 d-flex text-dark mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;">
                        <i class="mdi mdi-calendar-multiple-check me-3"></i>
                        <div>Otomatis jadwal guru tahun {{ request('month') ? Carbon::parse(request('month'))->format('Y') : date('Y') }}<br>
                            <small class="text-muted">Buat jadwal otomatis untuk guru, <strong>pastikan hari libur sudah diisi untuk tahun {{ request('month') ? Carbon::parse(request('month'))->format('Y') : date('Y') }}</strong></small>
                        </div>
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection
