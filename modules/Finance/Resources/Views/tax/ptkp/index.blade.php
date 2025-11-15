@extends('finance::layouts.default')

@section('title', 'Kelola PTKP | ')
@section('navtitle', 'Kelola PTKP')

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Kelola PTKP
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
                                <th>Jenis kelamin</th>
                                <th>Status</th>
                                <th>Tanggungan</th>
                                <th>Tarif</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ptkps as $ptkp)
                                <tr @if ($ptkp->trashed()) class="text-muted" @endif>
                                    <td class="text-center" width="5%">{{ $loop->iteration + ($ptkps->firstItem() - 1) }}</td>
                                    <td>{{ $ptkp->sex->label() }}</td>
                                    <td>{{ $ptkp->mariage->label() }}</td>
                                    <td>{{ $ptkp->child }} orang</td>
                                    <td>IDR {{ number_format($ptkp->value, 0) }}</td>
                                    <td nowrap class="py-1 text-end">
                                        @if (false)
                                            <a class="btn btn-soft-warning btn-sm disabled rounded px-2 py-1" target="_blank" data-bs-toggle="tooltip" title="Lihat detail" href="{{ route('finance::tax.ptkps.show', ['ptkp' => $ptkp->id, 'next' => url()->full()]) }}"><i class="mdi mdi-pencil-outline"></i></a>
                                            <form class="form-block form-confirm d-inline" action="{{ route('finance::tax.ptkps.destroy', ['ptkp' => $ptkp->id, 'next' => url()->full()]) }}" method="post"> @csrf @method('delete')
                                                <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </form>
                                        @endif
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
                    {{ $ptkps->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center flex-row py-4">
                    <div>
                        <div class="display-4">{{ $ptkp_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Jumlah data PTKP</div>
                    </div>
                    <div><i class="mdi mdi-timer-outline mdi-48px text-muted"></i></div>
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
