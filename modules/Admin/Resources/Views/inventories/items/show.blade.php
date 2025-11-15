@extends('asset::layouts.default')

@section('title', 'Lihat inventaris | ')

@section('navtitle', 'Lihat inventaris')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('asset::inventories.items.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Kelola inventaris</h2>
            <div class="text-muted">Detail informasi inventaris {{ $item->name }} !</div>
        </div>
    </div>
    @if ($item->trashed())
        <div class="alert alert-danger border-0">
            <strong>Perhatian!</strong> inventaris ini telah dihapus, Anda tidak lagi dapat mengelola inventaris ini.
        </div>
    @endif
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-eye-outline"></i> Detail inventaris</div>
                    @if (!$item->trashed())
                        <a class="btn btn-soft-success btn-sm rounded px-2 py-1" href="{{ route('asset::inventories.items.print', ['item' => $item->id]) }}" target="_blank"><i class="mdi mdi-printer-outline"></i> <span class="d-none d-sm-inline">Cetak dokumen (.pdf)</span></a>
                    @endif
                </div>
                <div class="card-body border-top">
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">Tanggal pembelian</div>
                            <div class="fw-bold"> {{ $item->bought_at->formatLocalized('%A, %d %B %Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Proposal pembelian</div>
                            <div class="fw-bold"> {{ $item->proposal->name ?? 'Tanpa pengajuan' }} {{ isset($item->proposal->user) ? $item->proposal->user->name : '' }}</div>
                        </div>
                    </div>
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted mb-1">Nama inventaris</div>
                            <div class="fw-bold">{{ $item->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted mb-1">No inventaris</div>
                            <div class="fw-bold">{{ $item->meta->inv_num ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted mb-1">Kategori</div>
                            <div class="fw-bold">{!! $item->meta->ctg_name ?? '-' !!}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted mb-1">Lokasi inventaris</div>
                            <div class="fw-bold">{!! $item->placeable->name ?? '-' !!}</div>
                        </div>
                    </div>
                    <div class="row gy-4 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted mb-1">Harga pembelian</div>
                            <div class="fw-bold">Rp. {{ number_format($item->bought_price, 2) }}</div>
                            <small class="cite">Terbilang: {{ inwords($item->bought_price) ?? '-' }} rupiah</small>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted mb-1">Registrasi</div>
                            <div class="fw-bold">{{ \Modules\Core\Enums\InventoryRegistrationTypeEnum::tryFrom($item->meta->register)->label() ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Kondisi</div>
                        <div class="fw-normal">
                            <div class="condition-label mb-2">{!! $item->condition->label() ?? '-' !!} <a href="javascript:;" class="text-warning cndt-ico rounded" id="condition-{{ $item->id }}" data-bs-toggle="tooltip" title="ubah kondisi"><i class="mdi mdi-pencil-outline"></i></a></div>
                            <div class="condition-form d-none col-md-5 border-light rounded">
                                <form class="form-block" action="{{ route('asset::inventories.items.condition', ['item' => $item->id, 'next' => url()->current()]) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                                    <div class="mb-3">
                                        <div class="overflow-auto rounded" style="max-height: 300px;">
                                            @foreach ($conditions as $condition)
                                                <label class="list-group-item border-secondary d-flex align-items-center py-2">
                                                    <input class="form-check-input" type="radio" name="condition" id="condition-{{ $condition->value }}" value="{{ $condition->value }}" @checked($condition->value == $item->condition->value)>
                                                    <div>
                                                        <div class="mb-0 ms-2"> {{ $condition->label() }}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description">Keterangan</label>
                                        <textarea name="description" class="form-control" id="description" rows="4"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-soft-danger btn-sm"><i class="mdi mdi-content-save-outline"></i> Update</button>
                                        <a href="javascript:;" class="btn btn-secondary btn-sm" onclick="resetToggleCondition(event)"><i class="mdi mdi-sync"></i> Batal</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Masa pakai</div>
                        <div class="fw-bold">{{ isset($item->meta->usefull) ? $item->meta?->usefull . ' Bulan' : '-' }} </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Masa habis pakai</div>
                        <div class="fw-bold">{{ isset($item->meta->usefull)
                            ? Carbon::parse($item->bought_at)->addMonths($item->meta?->usefull)->formatLocalized('%A, %d %B %Y')
                            : '-' }} </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Keterangan</div>
                        <div class="fw-bold">{!! $item->meta->description ?? 'Tanpa keterangan' !!} </div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1">Lampiran dari user</div>
                        <div class="fw-normal">
                            <form class="form-block" action="{{ route('asset::inventories.items.attachment', ['item' => $item->id, 'next' => url()->current()]) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                                @if (isset($item->attachments->items))
                                    <div class="mb-3">
                                        @foreach ($item->attachments->items as $key => $_item)
                                            <div class="card card-body mb-1">
                                                <input type="hidden" name="existing_attachments[]" value="{{ $loop->index }}">
                                                <div class="d-flex align-items-center justify-content-between gap-3">
                                                    <div style="max-width: 48px;">
                                                        <a href="{{ Storage::url($_item->url) }}" target="_blank">
                                                            <div class="rounded" style="background: url('{{ Storage::url($_item->url) }}') top center no-repeat; background-size: cover; width: 48px; height: 48px;"></div>
                                                        </a>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div><strong>Lampiran {{ $loop->iteration }}</strong></div>
                                                        <div class="small text-muted text-break" style="max-width: 100%;">{{ $_item->name }}</div>
                                                    </div>
                                                    <div class="pe-2">
                                                        <button type="button" class="btn btn-soft-danger btn-sm text-now" onclick="removeExistsingAttachment(this)"><i class="mdi mdi-trash-can"></i> <span class="d-none d-md-inline">Hapus</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <div id="files" class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="file" class="form-control @error('files.0') is-invalid @enderror file-input me-2" name="files[]" accept="image/jpeg,image/png,application/pdf" />
                                            <button type="button" class="btn btn-light text-danger rounded-circle btn-add px-2 py-1" onclick="addAttachment(event)"><i class="mdi mdi-plus"></i></button>
                                        </div>
                                    </div>
                                    <small class="form-text">Maksimal berkas adalah 5MB, berkas dapat berupa jpeg, jpg, png, atau pdf</small>
                                    @error('files.*')
                                        <small class="text-danger d-block"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-soft-danger btn-sm"><i class="mdi mdi-content-save-outline"></i> Upload</button>
                                    <a href="javascript:;" class="btn btn-secondary btn-sm" onclick="resetToggleFileUploads(this)"><i class="mdi mdi-sync"></i> Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted"></i> Log perangkat
                </div>
                <div class="table-responsive border-top">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center"></th>
                                <th>Aksi</th>
                                <th>Judul</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $k => $value)
                                <tr>
                                    <td width="5%" valign="top">{{ $loop->iteration }}</td>
                                    <td style="width: 120px;" valign="top">
                                        {{ $value->action->label() }}
                                    </td>
                                    <td valign="top">
                                        {{ $value->label }}
                                    </td>
                                    <td valign="top">
                                        {{ $value->description }}
                                    </td>
                                    <td class="py-2 pe-2" valign="top" nowrap>
                                        {{ $value->created_at->isoFormat('LLL') }}
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $logs->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            @php
                $list = [
                    'Karyawan' => $item->user->name ?: $item->pic->name,
                    'Posisi' => $item->user->employee->position->position->name ?: $item->pic->employee->position->position->name,
                    'Departemen' => $item->user->employee->position->position->department->name ?: $item->pic->employee->position->position->department->name,
                    'Tanggal pengajuan' => $item->bought_at->formatLocalized('%A, %d %B %Y'),
                ];
            @endphp
            <div class="card border-0">
                <div class="card-body">
                    <i class="mdi mdi-account-box-multiple-outline"></i> Pengguna/penanggung jawab
                </div>
                <div class="list-group list-group-flush border-top">
                    @foreach ($list as $key => $value)
                        <div class="list-group-item">
                            <div class="row d-flex align-items-center">
                                <div class="col-sm-6 col-xl-12">
                                    <div class="small text-muted">
                                        {{ $key }}
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-12 fw-bold">
                                    {{ $value }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if ($item)
                <a class="btn btn-outline-primary w-100 d-flex align-items-center mb-3 bg-white py-3 text-start" href="{{ route('asset::inventories.items.edit', ['item' => $item->id, 'next' => url()->current()]) }}">
                    <i class="mdi mdi-pencil-outline text-primary me-3"></i>
                    <div class="change-inv">
                        <div class="text-primary">Ubah inventaris</div>
                        <small class="text-muted">klik untuk mengubah inventaris.</small>
                    </div>
                </a>
                <form method="POST" class="form-block form-confirm" enctype="multipart/form-data" action="{{ route('asset::inventories.items.destroy', ['item' => $item->id, 'next' => request('next', route('asset::inventories.items.index'))]) }}"> @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100 text-danger d-flex align-items-center bg-white py-3 text-start" style="cursor: pointer;">
                        <i class="mdi mdi-trash-can me-3"></i>
                        <div>Hapus inventaris <br> <small class="text-muted">klik untuk menghapus inventaris.</small></div>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <div id="files-template" class="d-none">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="file" class="form-control file-input me-2" name="files[]" accept="image/jpeg,image/png,application/pdf" />
            <button type="button" class="btn btn-secondary rounded-circle btn-remove px-2 py-1" onclick="removeAttachment(event)"><i class="mdi mdi-minus"></i></button>
        </div>
    </div>
    <script>
        const removeExistsingAttachment = (el) => el.closest('.card.card-body').remove();

        const toggleCondition = (e) => {
            let el = e.currentTarget.parentNode;
            if (el) {
                el.nextElementSibling.classList.remove('d-none');
                el.classList.add('d-none');
            }
        }

        const resetToggleCondition = (e) => {
            let el = e.currentTarget.parentNode.parentNode.parentNode;
            if (el) {
                el.classList.add('d-none');
                el.previousElementSibling.classList.remove('d-none');
            }
        }

        const addAttachment = (e) => {
            let el = e.currentTarget.parentNode.parentNode;
            if (el) {
                el.insertAdjacentHTML('beforeend', document.getElementById('files-template').innerHTML);
                el.querySelector('.btn-add').classList.toggle('disabled', el.querySelectorAll('input').length >= 3);
            }
        }

        const removeAttachment = (e) => {
            let el = e.currentTarget.parentNode;
            let parent = e.currentTarget.parentNode.parentNode;
            el.remove();
            parent.querySelector('.btn-add').classList.toggle('disabled', parent.querySelectorAll('input').length >= 3);
        }

        const resetToggleFileUploads = (e) => {
            [...e.parentNode.previousElementSibling.querySelectorAll('.file-input')].map(x => x.value = '');
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelector(".cndt-ico").addEventListener('click', toggleCondition);
        });
    </script>
@endpush
