@extends('finance::layouts.default')

@section('title', 'Kelola pinjaman karyawan | ')

@section('navtitle', 'Kelola pinjaman karyawan')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <section>
                @if (request('paidoff'))
                    <div class="card card-body alert alert-primary alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Pinjaman lunas!</strong> data berikut merupakan pinjaman yang sudah lunas.
                    </div>
                @endif
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar pinjaman
                    </div>
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Nama</th>
                                    <th>Nominal</th>
                                    <th>Terbayar</th>
                                    <th>Status</th>
                                    <th>Tenor</th>
                                    <th>Terbayar</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $loan)
                                    <tr @if ($loan->trashed()) class="table-light text-muted" @endif>
                                        <td>{{ $loop->iteration + $loans->firstItem() - 1 }}</td>
                                        <td width="10">
                                            <div class="rounded-circle" style="background: url('{{ $loan->employee->user->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
                                        </td>
                                        <td nowrap>
                                            <strong>{{ $loan->employee->user->name }}</strong> <br>
                                            <small class="text-muted">{{ $loan->employee->position->name }}</small>
                                        </td>
                                        <td>
                                            <div class="text-muted"><small>{{ $loan->category->name }}</small></div>
                                            Rp {{ Str::money($loan->amount_total) }}
                                        </td>
                                        <td>Rp {{ Str::money($loan->transactions->sum('amount')) }}</td>
                                        <td>
                                            <i class="mdi {{ $loan->paid_at ? 'mdi-check text-success' : 'mdi-clock-outline text-danger' }}" style="font-size: 11pt;"></i> {{ $loan->paid_at ? 'Lunas' : 'Belum lunas' }}
                                        </td>
                                        <td>{{ implode(' ', [$loan->tenor, strtolower($loan->tenor_by->label())]) }}</td>
                                        <td>{{ implode(' ', [$loan->installments_count, strtolower($loan->tenor_by->label())]) }}</td>
                                        <td class="py-2 text-end" nowrap>
                                            @if ($loan->trashed())
                                                <form class="form-block form-confirm d-inline" action="{{ route('finance::service.loans.restore', ['loan' => $loan->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('put')
                                                    <button class="btn btn-soft-info rounded px-2 py-1" data-bs-toggle="tooltip" title="Pulihkan"><i class="mdi mdi-refresh"></i></button>
                                                </form>
                                            @else
                                                <a class="btn btn-soft-primary rounded px-2 py-1" href="{{ route('finance::service.loans.show', ['loan' => $loan->id, 'page' => 'main', 'next' => url()->current()]) }}" data-bs-toggle="tooltip" title="Lihat detail"><i class="mdi mdi-eye-outline"></i></a>
                                                <form class="form-block form-confirm d-inline" action="{{ route('finance::service.loans.destroy', ['loan' => $loan->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                                    <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">
                                            @include('components.notfound')
                                            @if (!request('trash'))
                                                @can('store', Modules\HRMS\Models\Employee::class)
                                                    <div class="mb-lg-5 mb-4 text-center">
                                                        <a class="btn btn-soft-danger" href="{{ route('finance::service.loans.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus"></i> Tambah pinjaman baru</a>
                                                    </div>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        {{ $loans->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-filter-outline"></i> Filter
                </div>
                <div class="card-body border-top">
                    <form class="form-block" action="{{ route('finance::service.loans.index') }}" method="get">
                        {{-- <div class="mb-3">
                            <label class="form-label required">Periode pengajuan</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-daterangepicker="true" data-daterangepicker-start="[name='start_at']" data-daterangepicker-end="[name='end_at']">
                                    <span class="d-inline d-sm-none"><i class="mdi mdi-sort-clock-descending-outline"></i></span>
                                    <span class="d-none d-sm-inline">Rentang waktu</span>
                                </button>
                                <input class="form-control" type="date" name="start_at" value="{{ date('Y-m-d', strtotime($start_at)) }}" required>
                                <input class="form-control" type="date" name="end_at" value="{{ date('Y-m-d', strtotime($end_at)) }}" required>
                            </div>
                        </div> --}}
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
                                <label class="form-check-label" for="trashed">Tampilkan juga pinjaman yang telah dihapus</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-filter-outline"></i> Terapkan</button>
                            <a class="btn btn-light" href="{{ route('finance::service.loans.index') }}"><i class="mdi mdi-refresh"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $unpaid_loans_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah pinanjaman {{ !request('paidoff') ? 'belum' : '' }} lunas</div>
                </div>
                <div><i class="mdi mdi-account-cash-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action" href="{{ route('finance::service.loans.create', ['next' => url()->full()]) }}"><i class="mdi mdi-plus-circle-outline"></i> Tambah pinjaman baru</a>
                    <a class="list-group-item list-group-item-action" href="{{ route('finance::service.loan-transaction-lists.index') }}"><i class="mdi mdi-clipboard-list-outline"></i> Daftar transaksi</a>
                    <a class="list-group-item list-group-item-action" href="{{ route('finance::service.loans.index', ['paidoff' => !request('paidoff')]) }}"><i class="mdi mdi-check-all"></i> Lihat pinjaman yang {{ request('paidoff') ? 'belum' : '' }} lunas</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('finance::service.loans.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat pinjaman yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
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
