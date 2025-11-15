@extends('core::layouts.default')

@section('title', 'Pengaturan slip gaji | ')
@section('navtitle', 'Pengaturan slip gaji')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <section>
                <div class="card border-0">
                    <div class="card-body">
                        <i class="mdi mdi-format-list-bulleted"></i> Daftar persetujuan slip gaji
                    </div>
                    <div class="card-body border-top border-light">
                        <form class="form-block row g-2" action="{{ route('core::company.salaries.configs.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::company.salaries.configs.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                            </div>
                        </form>
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
                    
                    @if (request('trash'))
                        <div class="card-body">
                            <div class="alert alert-warning fade show" role="alert">
                                <strong>Setting terhapus!</strong><br> Menampilkan data setting yang sudah dihapus.
                            </div>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th>label</th>
                                    <th>Tipe</th>
                                    <th>Konfigurasi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + $settings->firstItem() - 1 }}</td>
                                        <td>{{ $value->key }}</td>
                                        <td nowrap>{{ $value->az->label() }}</td>
                                        <td>{{ json_encode($value->meta) }}</td>
                                        <td>
                                            <form action="{{ route('core::company.salaries.configs.destroy', ['config' => $value->id, 'next' => route('core::company.salaries.configs.index')]) }}" class="d-inline form-block form-confirm" method="POST" enctype="multipart/form-data">@csrf @method('delete')
                                                <button class="btn btn-danger text-light rounded px-2 py-1"><i class="mdi mdi-trash-can"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        {{ $settings->appends(request()->all())->links() }}
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <div class="card card-body d-flex justify-content-between align-items-center flex-row border-0 py-4">
                <div>
                    <div class="display-4">{{ $setting_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah pengaturan</div>
                </div>
                <div><i class="mdi mdi-file-tree-outline mdi-48px text-light"></i></div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-danger" href="{{ route('core::company.salaries.configs.index', ['next' => url()->current(), 'trash' => !request('trash')]) }}"><i class="mdi mdi-trash-can"></i> Tampilkan setting {{ request('trash') ? 'tidak' : '' }} dihapus!</a>
                </div>
            </div>
            @can('store', Modules\Core\Models\CompanyPayrollSetting::class)
                <a class="btn btn-outline-primary w-100 text-primary d-flex align-items-center bg-white py-3 text-start" style="cursor: pointer;" href="{{ route('core::company.salaries.configs.create', ['next' => url()->current()]) }}">
                    <i class="mdi mdi-plus-outline me-3"></i>
                    <div>Tambah pengaturan <br> <small class="text-muted">Klik di sini untuk menambah pengaturan!</small></div>
                </a>
            @endcan
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
    <style type="text/css">
        .ts-wrapper {
            padding: 0 !important;
        }

        .ts-control {
            border: 1px solid hsla(0, 0%, 82%, .2) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        new TomSelect('[name="employee"]', {

        });
    </script>
@endpush
