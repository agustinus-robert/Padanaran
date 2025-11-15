@extends('portal::layouts.default')

@section('title', 'Libur | ')

@include('components.tourguide', [
    'steps' => array_filter([
        [
            'selector' => '.tg-steps-holiday-date',
            'title' => 'Tanggal libur',
            'content' => 'Kolom ini diisi tanggal libur yang akan kamu ajukan.',
        ],
    ]),
])

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::holiday.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Pengajuan tanggal libur baru</h2>
            <div class="text-muted">Nggak perlu khawatir kalo ada keperluan mendadak!</div>
        </div>
    </div>
    @error('dates.*')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    @if (count($errors))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div>Maaf, terjadi kegagalan, silakan periksa kembali isian Kamu</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card border-0">
        <div class="card-body">
            <div>Form pengajuan tanggal libur</div>
        </div>
        <div class="card-body border-top">
            <div class="row justify-content-center">
                <div class="col-12">
                    <form class="form-confirm form-block" action="{{ route('portal::holiday.submission.store') }}" method="post" enctype="multipart/form-data"> @csrf
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Periode</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" name="start_at" value="{{ old('start_at', $start_at) }}" required />
                                    <input type="datetime-local" class="form-control @error('en_at') is-invalid @enderror" name="end_at" value="{{ old('end_at', $end_at) }}" />
                                </div>
                                @if ($errors->has('start_at', 'end_at'))
                                    <small class="text-danger d-block"> {{ $errors->first('start_at') ?: $errors->first('end_at') }} </small>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label required">Pilih tanggal libur</label>
                            <div class="col-md-8">
                                <div class="tg-steps-holiday-date">
                                    <div class="inputs-meta-fields" id="inputs-options">
                                        <table class="table-borderless mb-0 table">
                                            <tbody id="fields-options-tbody">
                                                <tr id="fields-options-template">
                                                    <td class="ps-0 pt-0">
                                                        <input type="date" class="form-control @error('dates') is-invalid @enderror" name="dates[]" min="{{ Carbon::parse($start_at)->format('Y-m-d') }}" max="{{ Carbon::parse($end_at)->format('Y-m-d') }}" required>
                                                    </td>
                                                    <td class="pe-0 pt-0 text-end" width="50"><button class="btn btn-light btn-delete text-danger d-none" type="button" onclick="removeRow(event)"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @error('dates')
                                            <div class="small text-danger mb-1">{{ $message }}</div>
                                        @enderror
                                        <button id="fields-options-add" type="button" class="btn btn-light text-danger"><i class="mdi mdi-plus-circle-outline"></i> Tambah tanggal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 pt-3">
                            <div class="col-lg-8 offset-lg-4 offset-xl-3">
                                <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Ajukan</button>
                                <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::holiday.submission.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tbody = document.querySelector('#fields-options-tbody');
        let quota = 6;
        let meta = {}

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('fields-options-add').addEventListener('click', addRow);
            toggleAddButtonBasedQuota();
        });

        const toggleAddButtonBasedQuota = () => {
            document.getElementById('fields-options-add').classList.toggle('disabled', !(tbody.children.length < quota))
            document.getElementById('fields-options-add').classList.toggle('text-muted', !(tbody.children.length < quota))
        }

        const addRow = () => {
            let tr = document.querySelector('#fields-options-template').innerHTML;
            if (tbody.children.length < quota) {
                tbody.insertAdjacentHTML('beforeend', tr);
                Array.from(tbody.children).forEach((el, i) => {
                    if (i > 0) {
                        el.querySelector('.btn-delete').classList.remove('d-none');
                    }
                });
            }
            toggleAddButtonBasedQuota();
        }

        const removeRow = (e) => {
            e.target.parentNode.closest('tr').remove();
            toggleAddButtonBasedQuota();
        }
    </script>
@endpush
