<div>
    <div class="row">
        <div class="card">

            <div class="card-body">
                <div class="card-title">
                    <h4 class="card-title mb-4">Jadwal Hari Ini</h4>
                </div>

                <table class="table-sm table" style="font-size: 0.85rem; border-collapse: collapse;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            @foreach ($lessons as $value)
                                <th>{{ $value->label() }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @php($today = date('Y-m-d'))
                        
                        @foreach ($employees as $empl)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $empl->user->name }}</td>

                                @foreach ($lessons as $valles)
                                    <td>
                                        @php($lessonDisplayed = false)
                                        @php($hoursCount = 0)
                                            
                                        @foreach ($empl->schedulesTeachers as $schedule)
                                            @if (isset($schedule->dates[$today][$valles->value]))
                                                @foreach ($schedule->dates[$today] ?? [] as $keyPresence => $todayPresence)
                                                    @if (isset($todayPresence[$valles->value]) && !empty($todayPresence['lesson'][0]))
                                                        
                                                        @php($hoursCount += 2)

                                                        @if (!$lessonDisplayed)
                                                            {{ $schedule->lesson($todayPresence['lesson'][0])?->name ?? '-' }}
                                                            <br />

                                                            @php($statusValidasi = $todayPresence[$valles->value]['lesson'][1] ?? false)

                                                            <small class="text-muted">
                                                                <span style="width: 8px; height: 8px; border-radius: 50%; display: inline-block; background-color: {{ $statusValidasi ? '#28a745' : '#ffc107' }};"></span>
                                                                {{ $statusValidasi ? 'Sudah divalidasi' : 'Belum divalidasi' }}
                                                            </small>

                                                            <div class="mt-3">
                                                                <button class="btn btn-sm btn-outline-primary"
                                                                    wire:click="presenceShortcut('{{ $schedule->id }}', '{{ $valles->value }}', '{{ $todayPresence['lesson'][0] }}')"
                                                                    wire:target="presenceShortcut('{{ $schedule->id }}', '{{ $valles->value }}', '{{ $todayPresence['lesson'][0] }}')"
                                                                    wire:loading.attr="disabled" {{ $statusValidasi ? 'disabled' : '' }}>
                                                                    Validasi
                                                                </button>

                                                                <div wire:loading wire:target="presenceShortcut('{{ $schedule->id }}', '{{ $valles->value }}', '{{ $todayPresence['lesson'][0] }}')">
                                                                    <div class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></div>
                                                                </div>
                                                            </div>

                                                            @php($lessonDisplayed = true)
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title mb-4">Guru Piket Hari Ini</h4>
                        </div>

                        @if (count($duty) > 0)
                            @foreach ($duty as $duties => $dty)
                                @if (isset($dty->dates[$today][0]) && !empty($dty->dates[$today][0]))
                                    <div class="table-responsive mt-4">
                                        <table class="mb-0 table align-middle">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h5 class="font-size-14 mb-1">{{ $dty->employee->user->name }}</h5>
                                                        <p class="text-muted mb-0">{{ $dty->employee->position->position->name }}</p>
                                                    </td>

                                                    <td>
                                                        <p class="text-muted mb-1">Jadwal</p>
                                                        @if (isset($dty->dates[$today][0]))
                                                            <span class="badge badge-pill badge-soft-warning font-size-11">{{ $dty->dates[$today][0] }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="bx bxs-time text-primary display-4"></i>
                                </div>
                                <h3>{{ count($duty) }}</h3>
                                <p>Jumlah Guru Piket Hari Ini</p>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="mb-0 table text-center align-middle">
                                    <tbody>
                                        <tr>
                                            <td colspan="2">Tidak ada guru piket</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
