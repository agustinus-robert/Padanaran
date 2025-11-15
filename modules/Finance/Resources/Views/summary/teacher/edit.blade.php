@extends('portal::layouts.default')

@section('title', 'Rekapitulasi presensi | ')
@section('navtitle', 'Rekapitulasi presensi')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::dashboard-msdm.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Ubah rekap presensi baru</h2>
            <div class="text-secondary">Anda dapat mengubah rekap presensi dengan mengisi formulir di bawah</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-history"></i> Riwayat presensi
                </div>
                <div class="table-responsive border-top" style="overflow: auto; max-height: 960px;">
                    <table class="mb-0 table">
                        <tbody>
                            @forelse ($entries as $date => $shifts)
                                @foreach ($shifts as $entry)
                                    <tr>
                                        <td>{{ $loop->parent->iteration }}</td>
                                        <td>
                                            <span @if ($moment = $moments->firstWhere('date', $date)) data-bs-toggle="tooltip" title="" data-bs-placement="right" data-bs-original-title="{{ $moment->name }}" @endif @class(['fw-bold', 'text-danger' => $moment])>
                                                {{ strftime('%A, %d %b %Y', strtotime($date)) }}
                                                @if ($moment)
                                                    <i class="mdi mdi-information-outline"></i>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @php($original['times'][$date] = $entry->in?->format('H:i:s') ?? null)
                                            @php($currentday = $scanlogs[$date] ?? [])
                                            {{-- {{ implode(', ', array_map(fn($location) => $locations[$location], $entry->location)) }} --}}
                                            @foreach ($entry->location ?? [1] as $k => $v)
                                                @if ($loop->first && $loop->last)
                                                    {{ $locations[$v] }}
                                                @elseif($loop->last && !$loop->first)
                                                    @php($current = $currentday->where('location', $v))
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $current->first()->created_at->format('H:i:s') }}">
                                                        <span class="text-dark">{{ $locations[$v] }}</span>
                                                        <small>
                                                            <i class="mdi mdi-information-outline text-muted"></i>
                                                        </small>
                                                    </span>
                                                @else
                                                    {{ $locations[$v] }},
                                                @endif
                                            @endforeach
                                        </td>
                                        {{-- <td @class([
                                            'text-danger' => !$entry->ontime,
                                            'text-center',
                                        ])>{{ $entry->in?->format('H:i:s') ?? '-' }}</td> --}}
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td>@include('components.notfound-vertical')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-plus-circle-outline"></i> Buat rekapitulasi baru
                </div>
                <div class="card-body border-top border-light">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="text-muted">Nama karyawan</div>
                            <div class="fw-bold">{{ $attendance->employee->user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted">
                                <span data-bs-toggle="tooltip" data-bs-placement="right" title="Tanggal pada periode ini akan digunakan untuk penghitungan gaji, jadi pastikan tanggal yang Kamu isi adalah benar">
                                    <span>Periode</span>
                                    <i class="mdi mdi-information-outline"></i>
                                </span>
                            </div>
                            <div class="align-items-center d-flex">
                                <div>{{ $attendance->start_at->format('d-M-Y') }}</div>
                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                <div>{{ $attendance->end_at->format('d-M-Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top border-light">
                    <form class="form-block form-confirm" action="{{ route('finance::summary.teachings.update', ['teaching' => $attendance->empl_id, 'start_at' => $attendance->start_at->format('Y-m-d'), 'end_at' => $attendance->end_at->format('Y-m-d'), 'next' => request('next', route('hrms::summary.attendances.index'))]) }}" method="post"> @csrf
                        <div class="row gy-4">
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi umum periode ini</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah hari</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[days]" value="{{ $dtattd['result']->days }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Hari efektif</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[workdays]" value="{{ $dtattd['result']->workdays }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Hari libur nasional</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[holidays]" value="{{ $dtattd['result']->holidays }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <h6 class="fw-bold mb-3">Rekapitulasi perizinan</h6>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah izin</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[unpresence][leave]" value="{{ $dtattd['result']->unpresence->leave }}">
                                            <div class="input-group-text">hari</div>
                                        </div>
                                    </div>
                                </div>
                                {{-- @foreach (Modules\Core\Enums\VacationTypeEnum::cases() as $type)
                                    <div class="row align-items-center mb-2">
                                        <label class="col-form-label col-md-4" for="">Jumlah {{ $type->label() }}</label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" step="0.1" name="summary[unpresence][vacation][{{ strtolower($type->name) }}]" value="{{ $attendance->result->unpresence->vacation->{strtolower($type->name)} }}">
                                                <div class="input-group-text">hari</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach --}}
                            </div>
                            <div class="col-lg-6">
                                <h6 class="fw-bold mb-3">Rekapitulasi Kehadiran Mengajar</h6>
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam reguler</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_work]" value="{{ $attendance->result->attendance_work }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam ekstra</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="1" name="summary[additional_workdays]" value="{{ $attendance->result->additional_workdays }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah kehadiran</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_total]" value="{{ $presences->count() }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Piket</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[duty]" value="{{ $addition[17]['result']->amount_total }}">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Panitia PAT</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[pat]" value="{{ $addition[16]['result']->amount_total }}">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">UKM</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ukm]" value="{{ $addition[18]['result']->amount_total }}">
                                            <div class="input-group-text">jam</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Pengawas</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[invigilator]" value="{{ $addition[19]['result']->amount_total }}">
                                            <div class="input-group-text">soal</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah mengajar</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[attendance_total]" value="{{ $attendance->result->attendance_total }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jumlah tepat waktu</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[ontime_total]" value="{{ $attendance->result->ontime_total }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-form-label col-md-4" for="">Jumlah terlambat</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[late_total]" value="{{ $attendance->result->late_total }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Mengajar WFO</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[presence][wfo]" value="{{ $attendance->result->presence->wfo }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Mengajar WFA</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="summary[presence][wfa]" value="{{ $attendance->result->presence->wfa }}">
                                            <div class="input-group-text">kehadiran</div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="fw-bold mb-3">Jam Mengajar</h6>

                            <div class="col-lg-6">
                                <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Total jam mengajar</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="teach[amount_total]" value="{{ round($teach['result']->amount_total, 1) }}">
                                            <div class="input-group-text">Jam</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam Kerja Kelebihan</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="teach[overhour]" value="{{ round($teach->result->overhour, 1) }}">
                                            <div class="input-group-text">Jam</div>
                                        </div>
                                    </div>
                                </div> --}}

                                {{-- <div class="row align-items-center mb-2">
                                    <label class="col-form-label col-md-4" for="">Jam Kerja Extra</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" step="0.1" name="teach[extrahour]" value="{{ round($teach->result->extrahour, 1) }}">
                                            <div class="input-group-text">Jam</div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <hr>

                        {{-- @if ($userNow->position_id !== 3)
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="agreement" type="checkbox" required>
                                <label class="form-check-label" for="agreement">Dengan ini saya selaku {{ Auth::user()->employee->position->position->name ?? 'Human Resource (HR)' }} menyatakan data di atas adalah valid</label>
                            </div>

                            <div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('finance::summary.teachings.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                            <input class="position-fixed d-none" type="hidden" name="original" value='{{ json_encode($original) }}'>
                        @endif --}}
                    </form>

                    @if ($userNow->position_id == 2)
                        <div class="row">
                            <div class="col-md-6">
                                @foreach ($attendance->approvables as $approvable)
                                    <div class="row gy-2 @if (!$loop->last) mb-4 @endif">
                                        <div class="col-md-12">
                                            <div class="text-muted small mb-1">
                                                {{ ucfirst($approvable->type) }} #{{ $approvable->level }}
                                            </div>
                                            <strong>{{ $approvable->userable->getApproverLabel() }}</strong>
                                        </div>
                                        <div class="col-md-12">
                                            @if ($approvable->userable->is($userNow) && !$attendance->trashed())
                                                <form class="form-block" action="{{ route('finance::summary.summary.permission', ['next' => request('next', route('finance::summary.teachings.index'))]) }}" method="post"> @csrf @method('PUT')
                                                    <input type="hidden" name="id_attendance" value="{{ $attendance->id }}" />
    
                                                    <input type="hidden" name="id_teaching" value="{{ $teach['id'] }}" />
                                                    <input type="hidden" name="id_pat" value="{{ $addition[16]['id'] }}" />
                                                    <input type="hidden" name="id_teacherduty" value="{{ $addition[17]['id'] }}" />
                                                    <input type="hidden" name="id_ukm" value="{{ $addition[18]['id'] }}" />
                                                    <input type="hidden" name="id_teacherinvigilator" value="{{ $addition[19]['id'] }}" />
                                                   
                                                    <input type="hidden" name="id_additon" value="" />
                                                    <div class="mb-3">
                                                        <select class="form-select @error('result') is-invalid @enderror" name="result">
                                                            @foreach ($results as $result)
                                                                @unless (($approvable->cancelable && in_array($result, Modules\HRMS\Models\EmployeeRecapSubmission::$cancelable_disable_result)) || in_array($result, Modules\HRMS\Models\EmployeeRecapSubmission::$approvable_disable_result ?? []))
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
                                                    <a class="btn btn-soft-secondary text-dark" href="{{ request('next', route('finance::summary.teachings.index')) }}"><i class="mdi mdi-arrow-left-circle-outline"></i> Kembali</a>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const sumOvertimeTotal = () => {
            let overdays = parseFloat(document.querySelector('[name="summary[overtime][overdays]"]').value || 0)
            let holidays = parseFloat(document.querySelector('[name="summary[overtime][holidays]"]').value || 0)
            document.querySelector('[name="summary[overtime][total]"]').value = (works + overdays + holidays).toFixed(2);
        }
    </script>
@endpush
