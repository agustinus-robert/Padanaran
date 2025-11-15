@extends('portal::layouts.default')

@section('title', 'PPh 21 | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::home')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">PPh 21</h2>
            <div class="text-muted">Bukti potong PPh 21.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar-multiselect"></i> Riwayat bukti potong
                    </div>
                    <input type="checkbox" class="btn-check" id="collapse-btn" autocomplete="off" @if (request('search')) checked @endif>
                    <label class="btn btn-outline-secondary text-dark btn-sm rounded px-2 py-1" data-bs-toggle="collapse" data-bs-target="#collapse-filter" for="collapse-btn"><i class="mdi mdi-filter-outline"></i> <span class="d-none d-sm-inline">Filter</span></label>
                </div>
                <div class="card-body border-top border-bottom">
                    <form class="form-block row gy-2 gx-2" action="{{ route('portal::tax.payers.index') }}" method="get">
                        <div class="flex-grow-1 col-auto">
                            <input class="form-control" type="search" name="search" placeholder="Cari nama PPh di sini ..." value="{{ request('search') }}">
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-light" href="{{ route('portal::tax.payers.index') }}"><i class="mdi mdi-refresh"></i> <span class="d-sm-none">Reset</span></a>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-dark"><i class="mdi mdi-magnify"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive table-responsive-xl tg-steps-vacation-table">
                    <table class="mb-0 table align-middle">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <th class="text-center">Periode</th>
                                <th nowrap>PPh (Rp)</th>
                                <th>Lampiran</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($taxs as $tax)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $employee->user->name }}</div>
                                        @isset($employee->position?->position)
                                            <div class="small text-muted">{{ $employee->position?->position->name ?? '' }}</div>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        <div class="justify-content-center align-items-center d-flex">
                                            @if (!$tax->start_at->isSameDay($tax->end_at))
                                                <div class="">
                                                    <h6 class="mb-0">{{ $tax->start_at->format('d-M') }}</h6> <small class="text-muted">{{ $tax->start_at->format('Y') }}</small>
                                                </div>
                                                <div class="text-muted small mx-2">&mdash; s.d. &mdash;</div>
                                            @endif
                                            <div class="">
                                                <h6 class="mb-0">{{ $tax->end_at->format('d-M') }}</h6> <small class="text-muted">{{ $tax->end_at->format('Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td class="text-center">
                                        @if (isset($tax->file) && Storage::exists($tax->file))
                                            <a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="{{ Storage::url($tax->file) }}" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>
                                        @endif
                                    </td>
                                    <td class="py-2">
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
                    {{ $taxs->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-calendar-multiselect"></i> Formulir data wajib pajak orang pribadi
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-block form-confirm" method="POST" action="{{ route('portal::tax.payers.store', ['next' => url()->current()]) }}" enctype="multipart/form-data">@csrf
                        <div class="mb-3">
                            <label class="col-md-8 col-lg-8 col-form-label" for="npwp">Nomor Pokok Wajib Pajak (NPWP) </label>
                            <div class="col-md-12">
                                <input type="text" class="form-control @error('tax_number') is-invalid @enderror" id="npwp" name="tax_number" value="{{ $user->getMeta('tax_number') ?? '' }}">
                            </div>
                            @error('tax_number')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <label class="col-md-8 col-lg-8 col-form-label" for="name">Nama Wajib Pajak </label>
                            <div class="col-md-12">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name ?? '' }}" required>
                            </div>
                            @error('name')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <div class="col-md-12">
                                <label class="col-md-8 col-lg-8 col-form-label" for="npwp-address">Alamat sesuai NPWP</label>
                                <textarea type="text" class="form-control @error('tax_address') is-invalid @enderror" id="npwp-address" name="tax_address" required>{{ $user->getMeta('tax_address') ?? '' }}</textarea>
                            </div>
                            @error('tax_address')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <label class="col-md-8 col-lg-8 col-form-label" for="nik">Nomor induk kependudukan</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control @error('profile_nik') is-invalid @enderror" id="nik" name="profile_nik" value="{{ $user->getMeta('profile_nik') ?? '' }}" required>
                            </div>
                            @error('profile_nik')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <label class="col-md-8 col-lg-8 col-form-label" for="mariage">Status perkawinan </label>
                            <div class="col-md-12">
                                <select id="mariage" class="form-control @error('profile_mariage') is-invalid @enderror" name="profile_mariage" required>
                                    <option value=""></option>
                                    @foreach ($mariages as $mariage)
                                        <option value="{{ $mariage->value }}" @selected($mariage->value == $user->getMeta('profile_mariage'))>{{ $mariage->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('profile_mariage')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <label class="col-md-8 col-lg-8 col-form-label" for="child">Jumlah tanggungan (anak)</label>
                            <div class="col-md-12">
                                <input type="number" min="0" step="1" class="form-control @error('profile_child') is-invalid @enderror" id="child" name="profile_child" value="{{ $user->getMeta('profile_child') ?? '' }}" required>
                            </div>
                            @error('profile_child')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="required mb-3">
                            <label class="col-md-8 col-lg-8 col-form-label" for="address">Alamat sesuai KTP</label>
                            <div class="col-md-12">
                                <textarea type="text" class="form-control @error('address_address') is-invalid @enderror" id="address" name="address_address" required>{{ $user->getMeta('address_address') ?? '' }}</textarea>
                            </div>
                            @error('address_address')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-8 col-lg-8 col-form-label">Lampiran/Scan Kartu NPWP</label>
                            <div class="col-md-12">
                                <input class="form-control @error('files') is-invalid @enderror" name="files" type="file" id="upload-input" accept="image/*,application/pdf">
                                <small class="text-muted">Berkas berupa .jpg, .png atau .pdf maksimal berukuran 2mb</small>
                                @error('files')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 pt-3">
                            <div class="col-lg-8">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Simpan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::home')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">Menu lainnya</div>
                <div class="list-group list-group-flush border-top border-light">
                    <a class="list-group-item list-group-item-action text-dark" href="{{ route('portal::tax.payers.print') }}"><i class="mdi mdi-printer"></i> Cetak form</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush
