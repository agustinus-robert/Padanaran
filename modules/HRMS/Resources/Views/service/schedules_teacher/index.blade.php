@extends('administration::layouts.default')

@section('title', 'Jadwal kerja | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::home')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Jadwal kerja Guru</h2>
            <div class="text-muted">Yuk! cek jadwal kerjamu di sini, usahakan selalu tepat waktu ya!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <div><i class="mdi mdi-calendar-multiselect"></i> Jadwal kerja </div>
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
                                <th class="text-center">Jumlah jadwal kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                @php($contract = $employee->contract ?: $employee->contractWithin7Days)
                                @php($schedule = $employee->schedulesTeachers->first())
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
                                    {{-- <td class="py-2 text-end" nowrap>
                                        @if (!empty($schedule->id))
                                            <form action="{{ route('administration::service.otomtic-scan', ['schedule' => $schedule->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit">Sub</button>
                                            </form>
                                        @endif
                                    </td> --}}
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
        <div class="col-xl-4 col-sm-12">
            @if ($employee_count)
                <div class="card mb-3 border-0">
                    <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                        <div>
                            <div class="display-4">{{ $employee_count }}</div>
                            <div class="small fw-bold text-secondary text-uppercase">Jumlah guru</div>
                        </div>
                        <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                    </div>
                </div>
            @endif
            <form class="form-block" action="{{ route('administration::service.schedule.index') }}" method="get">
                <div class="mb-3">
                    <label class="form-label required" for="month">Periode</label>
                    <input type="month" class="form-control" id="month" name="month" value="{{ request('month', date('Y-m')) }}" required>
                </div>
                <div>
                    <button class="btn btn-soft-danger"><i class="mdi mdi-magnify"></i> Filter</button>
                    <a class="btn btn-soft-secondary" href="{{ route('portal::schedule.manages.index') }}"><i class="mdi mdi-sync"></i> Reset</a>
                </div>
            </form>

            <div class="mt-4">
                {{-- <a class="btn btn-outline-secondary w-100 d-flex text-dark mb-3 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('administration::service.manages.index') }}">
                    <i class="mdi mdi-calendar-multiple-check me-3"></i>
                    <div>Kelola jadwal <br> <small class="text-muted">Buat jadwal untuk tim kamu di sini!</small></div>
                </a> --}}
                <a class="btn btn-outline-primary w-100 d-flex text-primary mb-3 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('administration::service.submission.index') }}">
                    <i class="mdi mdi-book-plus-multiple-outline me-3"></i>
                    <div>Kelola Pengajuan Jadwal <br> <small style="opacity: 0.7;">Kelola pengajuan jadwal tim kamu di sini!</small></div>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        table.calendar>tbody>tr>td:hover {
            background: #fafafa;
        }

        .pulse-soft-danger {
            animation: pulse-soft-danger 1s infinite;
        }

        .pulse-soft-danger:hover {
            animation: none;
        }

        @-webkit-keyframes pulse-soft-danger {
            0% {
                -webkit-box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
            }

            80% {
                -webkit-box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
            }

            100% {
                -webkit-box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
            }
        }

        @keyframes pulse-soft-danger {
            0% {
                -moz-box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
                box-shadow: 0 0 0 0 rgba(255, 217, 215, .6);
            }

            80% {
                -moz-box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
                box-shadow: 0 0 0 10px rgba(255, 217, 215, 0);
            }

            100% {
                -moz-box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
                box-shadow: 0 0 0 0 rgba(255, 217, 215, 0);
            }
        }
    </style>
@endpush
