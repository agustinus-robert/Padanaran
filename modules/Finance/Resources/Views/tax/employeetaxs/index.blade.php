@extends('finance::layouts.default')

@section('title', 'Kelola PPh 21 | ')
@section('navtitle', 'Kelola PPh 21')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola PPh 21 karyawan
                </div>
                @if (request('pending'))
                    <div class="alert alert-warning rounded-0 d-xl-flex align-items-center border-0 py-2">
                        Hanya menampilkan pengajuan yang masih tertunda/berstatus <div class="badge badge-sm bg-dark fw-normal ms-2 text-white"><i class="mdi mdi-timer-outline"></i> Menunggu</div>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Karyawan</th>
                                <th>NPWP</th>
                                <th class="text-center">Lampiran</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr @if ($employee->trashed()) class="text-muted" @endif>
                                    <td class="text-center" width="5%">{{ $loop->iteration + ($employees->firstItem() - 1) }}</td>
                                    <td>
                                        {{ $employee->user->name }}
                                        <div class="text-muted">{{ $employee->position->position->name }}</div>
                                    </td>
                                    <td>{{ $employee->user->getMeta('tax_number') }}</td>
                                    <td class="text-center" nowrap>
                                        @if (!is_null($employee->user->getMeta('tax_file')) && Storage::exists($employee->user->getMeta('tax_file')))
                                            <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($employee->user->getMeta('tax_file')) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i> NPWP</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td nowrap class="py-1 text-center">
                                        <a class="btn btn-soft-warning btn-sm rounded px-2 py-1" data-bs-toggle="tooltip" title="Lihat detail" href="{{ route('finance::tax.employeetaxs.show', ['employeetax' => $employee->id, 'next' => url()->full()]) }}"><i class="mdi mdi-pencil me-1"></i></a>
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
                    <form class="form-block" action="{{ route('finance::tax.employeetaxs.index') }}" method="get">
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
                            <label class="form-label" for="select-positions">Karyawan</label>
                            <input class="form-control" name="search" placeholder="Cari nama karyawan ..." value="{{ request('search') }}" />
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="trashed" id="trashed" value="1" @if (request('trashed', 0)) checked @endif>
                                <label class="form-check-label" for="trashed">Tampilkan juga pengajuan yang telah dihapus</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('finance::tax.employeetaxs.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-cog"></i> Menu lainnya
                </div>
                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action py-3" href="{{ route('finance::tax.employeetaxs.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah NPWP karyawan</a>
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
