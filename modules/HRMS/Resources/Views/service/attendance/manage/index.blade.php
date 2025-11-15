@extends('hrms::layouts.default')

@section('title', 'Kelola presensi | ')
@section('navtitle', 'Kelola presensi')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body border-bottom">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola presensi karyawan
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
                                <th class="text-center">#</th>
                                <th></th>
                                <th>Nama</th>
                                <th>Periode</th>
                                <th class="text-center" nowrap>Hari kerja efektif</th>
                                <th class="text-center">Persentase (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($daynames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'])
                            @forelse($schedules as $schedule)
                                @php($logs = $scanlogs->filter(fn($log) => $log->empl_id == $schedule->empl_id && $log->created_at->isSameMonth($schedule->period))->groupBy(fn($log) => $log->created_at->format('Y-m-d')))
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + $schedules->firstItem() - 1 }}</td>
                                    <td width="10">
                                        <div class="rounded-circle" style="background: url('{{ $schedule->employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                    </td>
                                    <td nowrap>
                                        <strong class="d-block">{{ $schedule->employee->user->name }}</strong>
                                        <small class="text-muted">{{ $schedule->position->position->name ?? '-' }}</small>
                                    </td>
                                    <td>{{ strftime('%B %Y', strtotime($schedule->period)) }}</td>
                                    <td class="text-center">
                                        {{ collect($schedule->dates)->filter(fn($v, $date) => strtotime($date) >= strtotime($start_at) && strtotime($date) <= strtotime($end_at))->flatten()->filter()->count() / 2 }} hari
                                    </td>
                                    <td class="text-center" width="150">
                                        @php($entries = collect($schedule->getEntryLogs($logs)))
                                        @php($ontime = $entries->flatten(1)->countBy('ontime'))
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-success" role="progressbar" data-bs-toggle="tooltip" title="Tepat waktu {{ $ontime[1] ?? 0 }}x" style="width: {{ (($ontime[1] ?? 0) / isset($ontime) ? $ontime->sum() : 1) * 100 }}%" aria-valuenow="{{ (($ontime[1] ?? 0) / isset($ontime) ? $ontime->sum() : 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-bar bg-danger" role="progressbar" data-bs-toggle="tooltip" title="Terlambat {{ $ontime[0] ?? 0 }}x" style="width: {{ (($ontime[0] ?? 0) / isset($ontime) ? $ontime->sum() : 1) * 100 }}%" aria-valuenow="{{ (($ontime[0] ?? 0) / isset($ontime) ? $ontime->sum() : 0) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span data-bs-toggle="collapse" data-bs-target="#collapse-{{ $schedule->id }}">
                                            <button class="btn btn-soft-primary btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat kalender kerja"><i class="mdi mdi-calendar-outline"></i></button>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="border-0 p-0">
                                        <div class="collapse" id="collapse-{{ $schedule->id }}">
                                            <table class="table-bordered calendar mb-0 table">
                                                <thead>
                                                    <tr>
                                                        @foreach ($daynames as $dayname)
                                                            <th class="text-center">{{ $dayname }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php($month = Carbon\Carbon::parse($schedule->period))
                                                    @php($_month = $month->copy()->startOfMonth())
                                                    @php($day = 1)
                                                    @php($totalWeekOfMonth = $_month->dayOfWeek >= 5 && $_month->daysInMonth > 30 ? 6 : ($_month->dayOfWeek == 0 && $_month->daysInMonth <= 28 ? 4 : 5))
                                                    @for ($week = 1; $week <= $totalWeekOfMonth; $week++)
                                                        <tr>
                                                            @foreach ($daynames as $dayindex => $dayname)
                                                                @php($_date = date('Y-m-d', mktime(0, 0, 0, $_month->month, $day, $_month->year)))
                                                                @php($a_date = (($week == 1 && $dayindex >= $_month->dayOfWeek) || $week > 1) && ($week != $totalWeekOfMonth || $day <= $_month->daysInMonth))
                                                                <td class="{{ $a_date && ($_date < Carbon::parse($start_at)->format('Y-m-d') || $_date > $end_at) ? 'bg-secondary' : ($_date == date('Y-m-d') ? 'bg-soft-secondary' : '') }}" style="height: 100px; min-height: 100px; min-width: 120px;">
                                                                    @if ($a_date)
                                                                        <div class="position-relative h-100 float-start">
                                                                            <div class="small position-absolute {{ $dayindex == 0 || isset($moments[$_date]) ? 'text-danger' : 'text-muted' }}" style="opacity: .8;">{{ $day }}</div>
                                                                            @isset($moments[$_date])
                                                                                <div class="small position-absolute text-danger bottom-0" data-bs-toggle="tooltip" title="{{ $moments[$_date]->pluck('name')->join(',', ' dan ') }}" style="opacity: .8;"><i class="mdi mdi-information-outline"></i></div>
                                                                            @endisset
                                                                        </div>
                                                                        @foreach ($logs[$_date] ?? [] as $scan)
                                                                            <div class="position-relative text-center">
                                                                                <small class="d-block {{ $scan->location == Modules\Core\Enums\WorkLocationEnum::WFO->value ? 'fw-bold' : '' }}">{{ $scan->created_at->format('H:i:s') }}</small>
                                                                                @foreach ($schedule->dates[$_date] ?? [] as $shift)
                                                                                    @if (count(array_filter($shift)))
                                                                                        @php($tolerance = Carbon\Carbon::parse(strtotime($shift[0] . '+1 minute')))
                                                                                        @if ($loop->parent->first && $scan->created_at->gte($tolerance))
                                                                                            <i class="mdi mdi-alert-circle text-danger position-absolute end-0" data-bs-toggle="tooltip" title="Terlambat {{ $scan->created_at->longAbsoluteDiffForHumans($tolerance) }}" style="top: -2px;"></i>
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        @endforeach
                                                                        @php($day++)
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">@include('components.notfound')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $schedules->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.attendance.manage.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode</label>
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
                            <label class="form-label" for="select-positions">Nama</label>
                            <input class="form-control" name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('hrms::service.attendance.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-cog-outline"></i> Lanjutan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action py-3" href="{{ route('hrms::service.attendance.scanlogs.index') }}"><i class="mdi mdi-calendar-alert"></i> Lihat daftar scanlog</a>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi presensi</a>
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Data scanlog presensi</a>
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
