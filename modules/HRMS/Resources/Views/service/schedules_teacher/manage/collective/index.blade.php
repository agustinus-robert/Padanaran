@extends('hrms::layouts.default')

@section('title', 'Jadwal kerja | ')
@section('container-type', 'container-fluid px-5')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body border-bottom">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola Jadwal Piket
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

                            @if (Session::has('error'))
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                                    <div class="alert-danger alert">
                                        {!! Session::get('error') !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                <div class="table-responsive">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Hari Kerja</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                    <td>
                                        {{ $employee->user->name }}
                                    </td>
                                    <td>
                                        {{ $employee->schedulesDutyTeacher->first()->workdays_count ?? 0 }}
                                    </td>
                                    <td>
                                        @if ($employee)
                                            @if(isset($employee->schedulesDutyTeacher->first()->workdays_count) > 0)
                                                @can('show', $employee)
                                                    <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('hrms::service.teacher.duty.show', ['duty' => $employee->id, 
                                                    'start_at' => request('start_at'),
                                                    'end_at'   => request('end_at'),
                                                    'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Ubah"><i class="mdi mdi-eye-outline"></i></a>
                                                @endcan
                                            @endif
                                        @endif
                                        @can('destroy', $employee)
                                            @if(isset($employee->schedulesDutyTeacher->first()->workdays_count) > 0)
                                                <form action="{{ route('hrms::service.teacher.duty.destroy', ['duty' => $employee->id,
                                                'start_at' => request('start_at'),
                                                'end_at'   => request('end_at'),
                                                'next' => url()->full()]) }}" 
                                                    method="POST" 
                                                    class="form-block form-confirm d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-soft-danger rounded px-2 py-1" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Hapus">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">@include('components.notfound')</td>
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
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.teacher.duty.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label required">Periode</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                    <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                    <span class="d-none d-sm-inline">Rentang waktu</span>
                                </button>
                                <input class="form-control" type="date" name="start_at" value="{{ $start_at }}" required>
                                <input class="form-control" type="date" name="end_at" value="{{ $end_at }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="select-positions">Nama</label>
                            <input class="form-control" name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                                <label class="form-check-label" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('hrms::service.teacher.duty.create') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <a class="btn btn-outline-primary w-100 d-flex text-primary mb-4 rounded bg-white py-3 text-start" style="border-style: dashed;" href="{{ route('hrms::service.teacher.duty.create', [
                'start_at' => request('start_at'),
                'end_at' => request('end_at'),
            ]) }}">
                <i class="mdi mdi-calendar-multiple-check me-3"></i>
                <div>Absensi jadwal piket kolektif <br> <small style="opacity: 0.6;"></small></div>
            </a>

            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi izin</a>
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni mulai tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($start_at)) }}</small>
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
