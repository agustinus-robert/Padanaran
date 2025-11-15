@extends('portal::layouts.default')

@section('title', 'Kelola pengajuan | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::home')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola jadwal</h2>
            <div class="text-muted">Kamu dapat membuat jadwal kerja untuk tim kamu di sini!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Jadwal kerja
                </div>
                @if (request('pending'))
                    <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                        Hanya menampilkan pengajuan yang masih tertunda/berstatus <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-timer-outline"></i> Menunggu</div>
                    </div>
                @endif
                <div class="table-responsive table-responsive-xl">
                    <table class="mb-0 table align-middle">
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
                                @php($contract = $employee->contract ?: $employee->contractWithin7Days)
                                @php($schedule = $contract?->schedules->first())
                                <tr @class(['table-active' => is_null($contract)])>
                                    <td>{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                    <td width="10">
                                        <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                    </td>
                                    <td nowrap>
                                        <strong>{{ $employee->user->name }}</strong> <br>
                                        <small class="text-muted">{{ $contract->position?->position->name ?? '' }}</small>
                                    </td>
                                    <td>{{ strftime('%B %Y', strtotime(request('month', date('Y-m')))) }}</td>
                                    <td class="text-center">{{ $schedule?->workdays_count ?: '-' }}</td>
                                    <td class="py-2 text-end" nowrap>
                                        @if ($contract)
                                            @if ($schedule)
                                                @can('show', $schedule)
                                                    <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('portal::schedule.manage.show', ['schedule' => $schedule->id, 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-pencil-outline"></i></a>
                                                @endcan
                                            @else
                                                @can('store', Modules\HRMS\Models\EmployeeContractSchedule::class)
                                                    <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('portal::schedule.manage.create', ['contract' => $contract->id, 'month' => request('month', date('Y-m')), 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Buat baru"><i class="mdi mdi-plus-circle-outline"></i></a>
                                                @endcan
                                            @endif
                                            @can('destroy', $schedule)
                                                <form class="form-block form-confirm d-inline" action="{{ route('portal::schedule.manage.destroy', ['schedule' => $schedule->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
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
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                    <div>
                        <div class="display-4">{{ $employee_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Jumlah karyawan</div>
                    </div>
                    <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('portal::schedule.manage.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required" for="month">Periode</label>
                            <input type="month" class="form-control" id="month" name="month" value="{{ request('month', date('Y-m')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required" for="month">Pencarian</label>
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                        </div>
                        <div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-magnify"></i> Filter</button>
                            <a class="btn btn-soft-secondary" href="{{ route('portal::schedule.manage.index') }}"><i class="mdi mdi-sync"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
