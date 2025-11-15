@extends('hrms::layouts.default')

@section('title', 'Daftar presensi makan | ')
@section('navtitle', 'Daftar presensi makan')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body border-bottom">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola presensi makan karyawan
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
                                <th>#</th>
                                <th></th>
                                <th>Nama</th>
                                <th class="text-center" nowrap>Waktu scan</th>
                                <th class="text-center">IP</th>
                                <th class="text-center" nowrap>Lokasi presensi</th>
                                <th class="text-center">Agent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scanlogs as $scanlog)
                                <tr @class(['table-active' => is_null($scanlog->employee->contract)])>
                                    <td>{{ $loop->iteration + $scanlogs->firstItem() - 1 }}</td>
                                    <td width="10">
                                        <div class="rounded-circle" style="background: url('{{ $scanlog->employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                    </td>
                                    <td>
                                        <strong class="d-block">{{ $scanlog->employee->user->name }}</strong>
                                        <small class="text-muted">{{ $scanlog->employee->contract->position?->position->name ?? '' }}</small>
                                    </td>
                                    <td class="text-center" nowrap>
                                        <div>{{ $scanlog->created_at->format('H:i:s') }}</div>
                                        <small class="text-muted">{{ $scanlog->created_at->isoFormat('LL') }}</small>
                                    </td>
                                    <td class="text-center">{{ $scanlog->ip }}</td>
                                    <td class="small text-muted text-center" nowrap>
                                        @if (count($scanlog->latlong ?? []))
                                            <a href="https://www.google.com/maps/{{ '@' . $scanlog->latlong[0] }},{{ $scanlog->latlong[1] }},20z" target="_blank" data-bs-toggle="tooltip" title="{{ implode(', ', $scanlog->latlong) }}"><i class="mdi mdi-google-maps"></i>
                                                <span class="text-dark">
                                                    {{ implode(', ', $scanlog->latlong) }}
                                                </span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="d-flex align-items-center">
                                        <i class="mdi {{ $scanlog->user_agent->is_desktop ? 'mdi-monitor' : 'mdi-cellphone' }} text-muted me-3"></i>
                                        <div>
                                            <div>{{ $scanlog->user_agent->browser }} {{ $scanlog->user_agent->browser_version }}</div>
                                            <small class="text-muted">{{ $scanlog->user_agent->platform }} {{ $scanlog->user_agent->platform_version }}</small>
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
                    {{ $scanlogs->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::service.attendance.scanlogs.index') }}" method="get">
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
                            <input class="form-control" name="search" placeholder="Cari nama karyawan, ip, atau browser ..." value="{{ request('search') }}" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('hrms::service.attendance.scanlogs.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            @can('store', \Modules\HRMS\Models\EmployeeMealScanLog::class)
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-calendar-import"></i> Input presensi manual
                    </div>
                    <div class="card-body">
                        <form class="form-block" action="{{ route('hrms::service.meal.scanlogs.store') }}" method="post"> @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama karyawan</label>
                                <select class="form-select @error('employee') is-invalid @enderror" name="employee" required>
                                    @isset($employee)
                                        <option value="{{ $employee->id }}" selected>{{ $employee->user->name }}</option>
                                    @endisset
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal & waktu</label>
                                <input type="datetime-local" class="form-control @error('datetime') is-invalid @enderror" name="datetime" value="{{ old('datetime', now()) }}" required />
                                @error('datetime')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                            <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                        </form>
                    </div>
                </div>
            @endcan
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-file-document-multiple-outline"></i> Laporan
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Rekapitulasi presensi</a>
                    <a class="list-group-item list-group-item-action disabled py-3" href="javascript:;"><i class="mdi mdi-file-excel-outline"></i> Data scanlog presensi</a>
                </div>
                <div class="card-body border-top">
                    <small class="text-muted">Laporan akan di ambil berdasarkan filter yang diterapkan, yakni tanggal {{ strftime('%d %B %Y', strtotime($start_at)) }} s.d. {{ strftime('%d %B %Y', strtotime($end_at)) }}</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/daterangepicker.js') }}"></script>
    <script>
        let token = @json(json_decode(Cookie::get(config('auth.cookie')))).access_token;

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

        const searchEmployee = async () => {
            new TomSelect('[name="employee"]', {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                load: function(q, callback) {
                    fetch('{{ route('api::hrms.employees.search') }}?q=' + encodeURIComponent(q), {
                            method: 'GET',
                            headers: {
                                "Authorization": `Bearer ${token}`,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(json => {
                            callback(json.employees);
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                            callback();
                        });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('select-departments').addEventListener('change', renderPositions)
            renderPositions();
            searchEmployee();
        })
    </script>
@endpush
