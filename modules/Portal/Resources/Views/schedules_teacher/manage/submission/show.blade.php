@extends('portal::layouts.default')

@section('title', 'Jadwal Pengajuan kerja | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::home')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Pengajuan Jadwal kerja</h2>
            <div class="text-muted">Yuk! cek pengajuan jadwal kerjamu di sini, usahakan selalu tepat waktu ya!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-sm-12">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between py-2">
                    <div><i class="mdi mdi-calendar-multiselect"></i> Jadwal kerja </div>
                    {{-- <form class="tg-steps-presence-history" action="" method="GET">
                        <div class="input-group input-group-sm">
                            <input class="form-control" type="month" name="month" value="{{ $month->format('Y-m') }}">
                            <button class="btn btn-dark"><i class="mdi mdi-eye-outline"></i> <span class="d-none d-sm-inline">Tampilkan</span></button>
                        </div>
                    </form> --}}
                </div>
                <div class="table-responsive tg-steps-presence-calendar">
                    @php$workDay = 0;@endphp
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
                                        <td data-droppable="true" class="calendar-cell" style="height: 100px; min-height: 100px; min-width: 120px;">
                                            @if ((($week == 1 && $dayindex >= $_month->dayOfWeek) || $week > 1) && ($week != $totalWeekOfMonth || $day <= $_month->daysInMonth))
                                                <div class="position-relative h-100 float-start">
                                                    <div class="d-flex">
                                                        <div class="small flex-grow-1 position-absolute text-muted">{{ $day }}</div>
                                                    </div>
                                                </div>
                                                <div class="small mt-4">

                                                    @foreach ($workshifts as $keyShift => $workshift)
                                                        <div class="text-muted mb-2">{{ $workshift->label() }}</div>
                                                        <div class="input-group droppable-shift mb-3" data-shift-id="{{ $workshift->value }}" data-date="{{ $_date }}" style="border: 1px dashed #ddd; min-height: 40px;">

                                                            @foreach ($calendarData as $entry)
                                                                @foreach ($entry as $getEntry => $vEntry)
                                                                    @php($workDay = $vEntry->workdays_count)
                                                                    @foreach (json_decode($vEntry->dates) as $keyEntryDate => $valEntryDate)
                                                                        @if ($keyEntryDate == $_date)
                                                                            @if ($vEntry->employee->position->position->type->value == $type)
                                                                                @if (isset($valEntryDate[$keyShift]))
                                                                                    @if (!is_null($valEntryDate[$keyShift][0]))
                                                                                        <div class="d-flex align-items-center justify-content-between calendar-badge w-100 mb-2" data-date="{{ $keyEntryDate }}" data-empl_id="{{ $vEntry->empl_id }}">
                                                                                            <div class="employee-name">{{ date('H:i', strtotime($valEntryDate[$keyShift][0])) . ' - ' . date('H:i', strtotime($valEntryDate[$keyShift][1])) }}</div> <!-- You can fetch employee name here -->

                                                                                        </div>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    @endforeach

                                                </div>
                                                @php($day++)
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                {{--  @if ($scheduleCount)
                    <div class="card-body">
                        Hari efektif kerja kamu bulan {{ $month->formatLocalized('%B %Y') }} adalah <strong>{{ $scheduleCount }} hari</strong>
                    </div>
                @endif --}}
            </div>
        </div>

        <div class="col-xl-4 col-sm-12">

            <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                <div>
                    <div class="display-4">{{ $workDay }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah hari kerja</div>
                </div>
                <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
            </div>

            @foreach ($scheduleSubmission->approvables as $approvable)
                <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                    <div class="col-md-12">
                        <div class="text-muted small mb-1">
                            {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                        </div>
                        <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                    </div>
                    <div class="col-md-12">
                        @if ($approvable->userable->is($employee->position) && !$scheduleSubmission->trashed())
                            <form class="form-block" action="{{ route('portal::schedule-teacher.submissionappr.update', ['approvable' => $approvable->id, 'next' => request('next', route('portal::schedule-teacher.submission.index'))]) }}" method="post"> @csrf @method('PUT')
                                <input type="hidden" name="id" value="{{ $submissionId }}" />
                                <div class="mb-3">
                                    <select class="form-select @error('result') is-invalid @enderror" name="result">
                                        @foreach ($results as $result)
                                            @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeScheduleSubmissionTeacher::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeScheduleSubmissionTeacher::$approvable_disable_result ?? []))
                                                <option value="{{ $result->value }}" @selected($result->value == old('result', $approvable->result->value))>{{ $result->label() }}</option>
                                            @endunless
                                        @endforeach
                                    </select>
                                    @error('result')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control @error('reason') is-invalid @enderror" type="text" name="reason" placeholder="Alasan ...">{{ old('reason', $approvable->reason) }}</textarea>
                                    @error('reason')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('portal::schedule.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
                            </form>
                        @else
                            <div class="h-100 d-flex">
                                <div class="align-self-center badge bg-{{ $approvable->result->color() }} fw-normal text-white"><i class="{{ $approvable->result->icon() }}"></i> {{ $approvable->result->label() }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($approvable->history)
                    <div class="row p-0">
                        <div class="col-md-6 offset-md-6">
                            <hr class="text-muted mt-0">
                            <p class="small text-muted mb-1">Catatan riwayat sebelumnya</p>
                            {{ $approvable->history->reason }}
                        </div>
                    </div>
                @endif
                @if (!$loop->last)
                    <hr class="text-muted">
                @endif
            @endforeach
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
