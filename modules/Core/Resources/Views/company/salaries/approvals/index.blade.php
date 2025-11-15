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
                        <form class="form-block row g-2" action="{{ route('core::company.salaries.approvals.index') }}" method="get">
                            <input name="trash" type="hidden" value="{{ request('trash') }}">
                            <div class="flex-grow-1 col-auto">
                                <input class="form-control" name="search" placeholder="Cari nama ..." value="{{ request('search') }}" />
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-light" href="{{ route('core::company.salaries.approvals.index', request()->only('trashed', 'closed')) }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
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
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th>label</th>
                                    <th class="text-center">Index</th>
                                    <th>Nama</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + $settings->firstItem() - 1 }}</td>
                                        <td>{{ $value->key }}</td>
                                        <td class="text-center">{{ $value->az }}</td>
                                        <td>{{ \Modules\Account\Models\User::find($value->meta?->prop)->name }}</td>
                                        <td></td>
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
            @can('store', Modules\Core\Models\CompanyPayrollSetting::class)
                <div class="card border-0">
                    <div class="card-body"><i class="mdi mdi-plus"></i> Tambah pengaturan baru</div>
                    <div class="card-body border-top">
                        <form class="form-block" action="{{ route('core::company.salaries.approvals.store', ['next' => url()->full()]) }}" method="post"> @csrf
                            <div class="mb-3">
                                <label class="form-label" for="key">Slip</label>
                                <select class="form-select @error('key') is-invalid @enderror" name="key" value="{{ old('key') }}" required>
                                    <option value="">Pilih</option>
                                    @foreach ($slips as $key => $value)
                                        <option value="{{ $value->kd }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('key')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="az">Index urutan</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('az') is-invalid @enderror" name="az" value="{{ old('az') }}" required>
                                    <div class="input-group-text">#</div>
                                </div>
                                @error('az')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Nama karyawan</label>
                                <select class="form-select @error('employee') is-invalid @enderror" name="employee" value="{{ old('employee') }}" required>
                                    <option value="">Pilih</option>
                                    @foreach ($employees as $key => $employee)
                                        <option value="{{ $employee->user->id }}">{{ $employee->user->name }} ({{ $employee->contract->position->position->name }})</option>
                                    @endforeach
                                </select>
                                @error('employee')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <button class="btn btn-soft-danger"><i class="mdi mdi-check"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
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
