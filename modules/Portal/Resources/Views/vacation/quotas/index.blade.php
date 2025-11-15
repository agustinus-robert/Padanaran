@extends('portal::layouts.default')

@section('title', 'Sisa kuota cuti/libur hari raya departmenen | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Sisa kuota cuti/libur hari raya departemen</h2>
            <div class="text-muted">Berikut adalah informasi sisa kuota cuti/libur hari raya karyawan!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('portal::vacation.quotas.index') }}" method="get">
                        <div class="mb-3">
                            <label class="form-label" for="select-departments">Departemen</label>
                            <select class="form-select" id="select-departments" name="department">
                                <option value>Pilih departemen</option>
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
                            <label class="form-label" for="search">Cari nama</label>
                            <input class="form-control" name="search" placeholder="Cari nama atau nip ..." value="{{ request('search') }}" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Filter jenis cuti</label>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="quota" value="1" name="quota" @if (old('quota', request('quota'))) checked @endif>
                                    <label class="form-check-label" for="quota">
                                        <span id="quota-text">
                                            @if (old('quota', request('quota')))
                                                Tampilkan semua daftar kuota cuti.
                                            @else
                                                Tampilkan hanya cuti tahunan dan libur hari raya.
                                            @endif
                                        </span>
                                    </label>
                                </div>
                                @error('quota')
                                    <small class="text-danger d-block"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('portal::vacation.quotas.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="list-group mb-4">
                @include('portal::vacation.quotas.excel.download')
            </div>
        </div>
        <div class="col-md-8 order-md-first">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar distribusi cuti karyawan
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table-bordered mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Nama</th>
                                <th nowrap>Kategori cuti</th>
                                <th nowrap>Masa berlaku</th>
                                <th nowrap>Kuota cuti</th>
                                {{-- <th nowrap>Kompensasi</th> --}}
                                <th nowrap>Sisa kuota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                @php($quotas = $employee->vacationQuotas)
                                <tr>
                                    <td rowspan="{{ $employee->vacationQuotas->count() ?: 1 }}">{{ $loop->iteration + $employees->firstItem() - 1 }}</td>
                                    <td rowspan="{{ $employee->vacationQuotas->count() ?: 1 }}" width="10">
                                        <div class="rounded-circle" style="background: url('{{ $employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                    </td>
                                    <td rowspan="{{ $employee->vacationQuotas->count() ?: 1 }}">
                                        <strong>{{ $employee->user->name }}</strong> <br>
                                        {{ $employee->contract->position->position->name }}
                                    </td>
                                    @if ($quota = $quotas->shift())
                                        <td>{{ $quota->category->name }}</td>
                                        <td nowrap>
                                            <div class="justify-content-center align-items-center d-flex">
                                                <div class="">
                                                    <h6 class="mb-0">{{ $quota->start_at->format('d-M') }}</h6> <small class="text-muted">{{ $quota->start_at->format('Y') }}</small>
                                                </div>
                                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                                <div class="">
                                                    <h6 class="mb-0">{{ $quota->end_at?->format('d-M') }}</h6> <small class="text-muted">{{ $quota->end_at->format('Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $quota->quota ?: '∞' }} hari</td>
                                        {{-- <td class="text-center">{{ $quota->vacations->filter(fn($vacation) => isset($vacation->dates->first()['cashable']))->pluck('dates')->flatten(1)->pluck('d')->count() }}</td> --}}
                                        <td class="text-center">{{ is_null($quota->quota) ? '∞' : abs($quota->quota - $quota->vacations->sum(fn($vacation) => count($vacation->dates))) }} hari</td>
                                    @else
                                        <td colspan="4" class="text-muted">Tidak ada kuota cuti yang didistribusikan</td>
                                    @endif
                                </tr>
                                @foreach ($quotas ?? [] as $quota)
                                    <tr>
                                        <td>{{ $quota->category->name }}</td>
                                        <td nowrap>
                                            <div class="justify-content-center align-items-center d-flex">
                                                <div class="text-center">
                                                    <h6 class="mb-0">{{ $quota->start_at->format('d-M') }}</h6> <small class="text-muted">{{ $quota->start_at->format('Y') }}</small>
                                                </div>
                                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                                <div class="text-center">
                                                    <h6 class="mb-0">{{ $quota->end_at?->format('d-M') }}</h6> <small class="text-muted">{{ $quota->end_at->format('Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $quota->quota ?: '∞' }} hari</td>
                                        {{-- <td class="text-center">{{ $quota->vacations->filter(fn($vacation) => isset($vacation->dates->first()['cashable']))->pluck('dates')->flatten(1)->pluck('d')->count() }}</td> --}}
                                        <td class="text-center">{{ is_null($quota->quota) ? '∞' : abs($quota->quota - $quota->vacations->sum(fn($vacation) => count($vacation->dates))) }} hari</td>

                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="7">
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
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/excel/excel.min.js') }}"></script>
    <script src="{{ asset('js/vendor/moment.min.js') }}"></script>
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
            document.querySelector('#quota').addEventListener('change', (e) => {
                document.querySelector('#quota-text').innerHTML = e.target.checked ? 'Tampilkan semua daftar kuota cuti.' : 'Tampilkan hanya cuti tahunan dan libur hari raya.'
            });
            document.getElementById('select-departments').addEventListener('change', renderPositions);
            renderPositions();
        });
    </script>
@endpush
