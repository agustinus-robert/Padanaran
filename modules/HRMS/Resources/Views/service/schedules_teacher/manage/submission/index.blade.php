@extends('administration::layouts.default')

@section('title', 'Kelola pengajuan | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::schedule-teacher.workshifts')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola Pengajuan Jadwal Guru</h2>
            <div class="text-muted">Kamu dapat mengunggu/menolak lembur guru!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiselect"></i> Daftar pengajuan jadwal kerja guru
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
                                <th></th>
                                <th>Karyawan</th>
                                <th>Tgl pengajuan</th>
                                <th class="text-center">Hari Kerja</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $allocate)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + $submissions->firstItem() - 1 }}</td>
                                    <td>
                                        <div class="fw-bold">
                                            {{ $allocate->employee->user->name }}
                                        </div>
                                        <span class="small">{{ $allocate->employee->position->position->name }}</span>
                                    </td>
                                    <td class="small">{{ $allocate->created_at->formatLocalized('%d %B %Y') }}</td>
                                    <td class="text-center">
                                        {{ $allocate->workdays_count }}
                                    </td>
                                    <td nowrap>
                                        @if (!empty($allocate->approved_at))
                                            <div class="badge bg-soft-success text-success fw-normal"><i class="mdi mdi-check"></i> Disetujui</div>
                                        @else
                                            <div class="badge bg-soft-secondary text-dark fw-normal"><i class="mdi mdi-clock"></i> Menunggu</div>
                                        @endif
                                    </td>
                                    <td nowrap class="py-1 text-end">
                                        <a href="{{ route('administration::service.submission.show', ['submission' => $allocate->id]) }}" class="btn btn-soft-primary px-2 py-1" data-bs-toggle="tooltip" data-bs-title="Kelola data"><i class="mdi mdi-progress-clock"></i></a>
                                        <form class="d-inline form-block form-confirm" action="{{ route('portal::schedule-teacher.submission.update', $allocate->id) }}" method="POST" id="update-form-{{ $allocate->id }}"> @csrf @method('PUT')
                                            {{-- <button class="btn @if (!empty($allocate->approved_at)) disabled btn-soft-secondary @else btn-soft-danger @endif px-2 py-1" data-bs-toggle="tooltip" data-bs-title="Setujui pengajuan"><i class="mdi mdi-check"></i></button> --}}
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">@include('components.notfound')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $submissions->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                    <div>
                        <div class="display-4">{{ $submission_count ?? 0 }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Jumlah pengajuan tertunda</div>
                    </div>
                    <div><i class="mdi mdi-timer-outline mdi-48px text-danger"></i></div>
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('administration::service.submission.index', ['pending' => !request('pending')]) }}"><i class="mdi mdi-progress-clock"></i> {{ request('pending') == 1 ? 'Tampilkan semua pengajuan' : 'Hanya tampilkan pengajuan yang tertunda' }}</a>
                </div>
            </div>
            <div class="card boder-0">
                <div class="card-body">
                    <form class="form-block" action="{{ route('administration::service.manages.import') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label><b>Upload Jadwal</b></label>
                                <input type="file" name="scheduleFile" class="form-control" />
                                <p><i>Penguploadan jadwal langsung disetujui sistem</i></p>
                            </div>

                            <div class="col-md-12 mt-2">
                                <button type="submit" class="list-group-item list-group-item-action">
                                    <i class="mdi mdi-cloud-upload-outline"></i> Unggah jadwal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <h6><b>Template Upload Jadwal (Realisasi 1 Minggu Sebelumnya)</b></h6>
                    <div class="list-group border-top border-light mt-2">
                        <a class="list-group-item list-group-item-action" href="{{ route('administration::service.template.schedule.index') }}"><i class="mdi mdi-cloud-download-outline"></i> Unduh jadwal</a>
                    </div>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('administration::service.submission.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode pengajuan</label>
                            <div class="input-group">
                                {{-- <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                    <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                    <span class="d-none d-sm-inline">Rentang waktu</span>
                                </button> --}}
                                <input class="form-control" type="date" name="start_at" value="{{ date('Y-m-d', strtotime($start_at)) }}" required>
                                <input class="form-control" type="date" name="end_at" value="{{ date('Y-m-d', strtotime($end_at)) }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="select-positions">Karyawan</label>
                            <input class="form-control" name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('portal::overtime.manage.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
@endpush
