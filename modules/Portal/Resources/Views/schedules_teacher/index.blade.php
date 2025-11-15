@extends('portal::layouts.index')

@section('title', 'Jadwal kerja | ')

@section('contents')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="javascript:void(0)"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Jadwal kerja Guru</h2>
            <div class="text-muted">Yuk! cek jadwal kerjamu di sini, usahakan selalu tepat waktu ya!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-sm-12">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between py-2">
                    <div><i class="mdi mdi-calendar-multiselect"></i> Jadwal kerja </div>
                    <form class="tg-steps-presence-history" action="{{ route('portal::schedule.workshifts') }}" method="GET">
                        <div class="input-group input-group-sm">
                            <input class="form-control" type="month" name="month" value="{{ $month->format('Y-m') }}">
                            <button class="btn btn-dark"><i class="mdi mdi-eye-outline"></i> <span class="d-none d-sm-inline">Tampilkan</span></button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive tg-steps-presence-calendar">
                    @php($daynames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'])
                    <table class="table-bordered calendar mb-0 table">
                        <thead>
                            <tr>
                                @foreach ($daynames as $dayname)
                                    <th class="text-center">{{ $dayname }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php($_month = $month->copy()->startOfMonth())
                            @php($day = 1)
                            @php($totalWeekOfMonth = $_month->dayOfWeek >= 5 && $_month->daysInMonth >= 30 ? 6 : ($_month->dayOfWeek == 0 && $_month->daysInMonth <= 28 ? 4 : 5))
                            @for ($week = 1; $week <= $totalWeekOfMonth; $week++)
                                <tr>
                                    @foreach ($daynames as $dayindex => $dayname)
                                        @php($_date = date('Y-m-d', mktime(0, 0, 0, $_month->month, $day, $_month->year)))
                                        <td class="{{ $_date == date('Y-m-d') ? 'bg-soft-secondary' : '' }}" style="height: 100px; min-height: 100px; min-width: 120px;">
                                            @if ((($week == 1 && $dayindex >= $_month->dayOfWeek) || $week > 1) && ($week != $totalWeekOfMonth || $day <= $_month->daysInMonth))
                                                <div class="position-relative h-100 float-start">
                                                    <div class="d-flex">
                                                        <div class="small flex-grow-1 position-absolute {{ $dayindex == 0 || isset($moments[$_date]) ? 'text-danger' : 'text-muted' }}" style="opacity: .8;">{{ $day }}</div>
                                                        @isset($moments[$_date])
                                                            <div class="small position-absolute text-danger ms-3" data-bs-toggle="tooltip" title="{{ $moments[$_date]->pluck('name')->join(',', ' dan ') }}" style="opacity: .8;"><i class="mdi mdi-information-outline"></i></div>
                                                        @endisset
                                                    </div>
                                                </div>
                                                @if ($vacations->pluck('d')->contains($_date))
                                                    @php($fr = $vacations->filter(fn($i) => $i['d'] == $_date)->pluck('f'))
                                                    @php($cl = $fr[0] != null ? '--bs-warning' : '--bs-danger')
                                                    <div class="position-relative float-end">
                                                        <div class="position-absolute text-center text-white" style="transform: rotate(45deg); top: -3px; right: -23px; border-bottom: 18px solid var({{ $cl }}); font-size: .75rem; border-left: 18px solid transparent; border-right: 18px solid transparent; height: 0;">
                                                            {{ $fr[0] != null ? 'Frln' : 'Cuti' }}
                                                        </div>
                                                    </div>
                                                @endif
                                                <!-- jadwal kerja -->
                                                @if (isset($schedules[$_date]) && !$vacations->pluck('d')->contains($_date))
                                                    <div class="h-100 mb-2 mt-4 text-center">
                                                        @foreach ($schedules[$_date] as $key => $value)
                                                            <small class="fw-normal nowrap ms-2" style="font-size: .85rem;">{{ $value->in . ' - ' . $value->out }}</small>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @php($day++)
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                @if ($scheduleCount)
                    <div class="card-body">
                        Hari efektif kerja kamu bulan {{ $month->formatLocalized('%B %Y') }} adalah <strong>{{ $scheduleCount }} hari</strong>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-xl-4 col-sm-12">
            @if ($scheduleCount)
                <div class="card mb-3 border-0">
                    <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                        <div>
                            <div class="display-4">{{ $scheduleCount }}</div>
                            <div class="small fw-bold text-secondary text-uppercase">Jumlah hari kerja</div>
                        </div>
                        <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                    </div>
                </div>
            @endif
            <a class="btn btn-outline-secondary w-100 d-flex text-dark mb-3 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('portal::schedule-teacher.manages.index') }}">
                <i class="mdi mdi-calendar-multiple-check me-3"></i>
                <div>Kelola jadwal <br> <small class="text-muted">Buat jadwal untuk tim kamu di sini!</small></div>
            </a>
            <a class="btn btn-outline-primary w-100 d-flex text-primary mb-3 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('portal::schedule-teacher.submission.index') }}">
                <i class="mdi mdi-book-plus-multiple-outline me-3"></i>
                <div>Kelola Pengajuan Jadwal <br> <small style="opacity: 0.7;">Kelola pengajuan jadwal tim kamu di sini!</small></div>
            </a>
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
