@extends('hrms::layouts.default')

@section('title', 'Pengaturan template gaji | ')
@section('navtitle', 'Pengaturan template gaji')

@section('content')
    <div class="row justify-content-ceter">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar pengaturan
                    </div>
                    <div class="table-responsive border-top border-light">
                        <table class="mb-0 table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Konfigurasi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                    <tr>
                                        <td width="10">{{ $loop->iteration }}</td>
                                        <td nowrap>
                                            <div class="fw-bold">{{ $setting->key }}</div>
                                        </td>
                                        <td>
                                            {{ json_encode($setting->meta) }}
                                        </td>
                                        <td>
                                            <a class="btn btn-soft-warning rounded px-2 py-1" href="{{ route('hrms::payroll.configs.show', ['config' => $setting->id]) }}" data-bs-toggle="tooltip" title="Ubah pengaturan"><i class="mdi mdi-pencil"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            @include('components.notfound')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-dark" href="{{ route('hrms::payroll.configs.create') }}"><i class="mdi mdi-plus"></i> Tambah setting</a>
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('hrms::payroll.configs.index', ['trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can-outline"></i> Lihat setting yang {{ request('trash') ? 'tidak' : '' }} dihapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
