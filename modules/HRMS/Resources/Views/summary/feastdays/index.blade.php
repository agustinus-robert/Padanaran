@extends('hrms::layouts.default')

@section('title', 'Tunjangan hari raya | ')
@section('navtitle', 'Tunjangan hari raya')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Tunjangan hari raya
                </div>
                {{-- <div class="card-body border-top">
                    <form class="form-block row gy-2 gx-2" action="{{ route('hrms::summary.feastdays.index', request()->all()) }}" method="get">
                        <div class="flex-grow-1 col-auto">
                            <div class="input-group">
                                <div class="input-group-text">Cut off</div>
                                <input class="form-control" type="date" name="cutoff_at" value="{{ $cutoff_at->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
                </div> --}}
                <div class="table-responsive">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Nama</th>
                                <th>Agama</th>
                                <th class="text-center">Periode</th>
                                <th class="text-center">THP (Rp)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($religions_mapped = collect($religions)->mapWithKeys(fn($religion) => [$religion->value => $religion->label()]))
                            @forelse($employees as $employee)
                                <tr>
                                    <td width="10">{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                    <td width="10">
                                        <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                    </td>
                                    <td nowrap>
                                        <div class="fw-bold">{{ $employee->user->name }}</div>
                                        @isset($employee->position?->position)
                                            <div class="small text-muted">{{ $employee->position?->position->name ?? '' }}</div>
                                        @endisset
                                    </td>
                                    <td>{{ $religions_mapped[$employee->user->getMeta('profile_religion')] }}</td>
                                    <td nowrap class="text-center">
                                        <h6 class="mb-0">{{ $cutoff_at->format('d-M') }}</h6> <small class="text-muted">{{ $cutoff_at->format('Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($total = $employee->dataRecapitulations->sum('result.total'))
                                            {{ number_format($total, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-2 text-end" nowrap>
                                        <a class="btn {{ $total ? 'btn-soft-warning' : 'btn-soft-primary' }} rounded px-2 py-1" href="{{ route('hrms::summary.feastdays.create', ['employee' => $employee->id, 'cutoff_at' => $cutoff_at->format('Y-m-d'), 'next' => url()->full()]) }}" data-bs-toggle="tooltip" title="Hitung"><i class="mdi {{ $total ? 'mdi-pencil-outline' : 'mdi-plus-circle-outline' }}"></i></a>
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
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('hrms::summary.feastdays.index') }}" method="get">
                        {{-- <input class="d-none" type="date" name="cutoff_at" value="{{ $cutoff_at->format('Y-m-d') }}" required> --}}
                        <div class="mb-3">
                            <label class="form-label" for="cut-off">Cut off</label>
                            <input class="form-control" id="cut-off" type="date" name="cutoff_at" value="{{ $cutoff_at->format('Y-m-d') }}" required>
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
                            <label class="form-label">Agama</label>
                            <div class="card card-body px-3 py-2">
                                @foreach ($religions as $label => $religion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="religions[]" id="religions-{{ $religion->value }}" value="{{ $religion->value }}" @checked(in_array($religion->value, request('religions', array_map(fn($religion) => $religion->value, $religions))))>
                                        <label class="form-check-label" for="religions-{{ $religion->value }}">{{ $religion->label() }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pencarian</label>
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('hrms::summary.attendances.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
