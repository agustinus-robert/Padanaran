@extends('core::layouts.default')

@section('title', 'Log | ')
@section('navtitle', 'Log')

@section('content')
    <section>
        <div class="card border-0">
            <div class="card-body">
                <i class="mdi mdi-format-list-bulleted"></i> Daftar log pengguna
            </div>
            <div class="card-body border-top border-light">
                <form class="form-block row gy-2 gx-2" action="{{ route('core::system.user-logs.index') }}" method="get">
                    <input name="trash" type="hidden" value="{{ request('trash') }}">
                    <div class="flex-grow-1 col-auto">
                        <select class="form-select" name="user" style="padding-top: 0 !important;">
                            @if (request('user') && $user)
                                <option value="{{ request('user') }}" selected>{{ $user->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="flex-grow-1 col-auto">
                        <input class="form-control" name="search" placeholder="Cari pesan log ..." value="{{ request('search') }}" onkeyup="searchTable()" />
                    </div>
                    <div class="col-auto">
                        <a class="btn btn-light" href="{{ route('core::system.user-logs.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table-hover mb-0 table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pengguna</th>
                            <th>Log</th>
                            <th>IP</th>
                            <th>UA</th>
                            <th>Waktu</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td nowrap class="fw-bold">{{ $log->user->name }}</td>
                                <td>{!! $log->message !!}</td>
                                <td class="text-muted">{{ $log->ip }}</td>
                                <td class="text-muted">{{ $log->user_agent }}</td>
                                <td nowrap>{{ $log->created_at }}</td>
                                <td class="py-2 text-end" nowrap>
                                    @can('destroy', $log)
                                        <form class="form-block form-confirm d-inline" action="{{ route('core::system.user-logs.destroy', ['log' => $log->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                            <button class="btn btn-soft-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus"><i class="mdi mdi-trash-can-outline"></i></button>
                                        </form>
                                    @endcan
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
                {{ $logs->appends(request()->all())->links() }}
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
    <style type="text/css">
        .ts-wrapper {
            padding: 0 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        new TomSelect('[name="user"]', {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            placeholder: 'Cari pengguna disini ...',
            load: function(q, callback) {
                fetch('{{ route('api::account.users.search') }}?q=' + encodeURIComponent(q))
                    .then(response => response.json())
                    .then(json => {
                        callback(json.users);
                    }).catch(() => {
                        callback();
                    });
            }
        });
    </script>
@endpush
