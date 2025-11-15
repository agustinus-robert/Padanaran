@extends('portal::layouts.default')

@section('title', 'Kompensasi cuti | ')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a class="text-decoration-none" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
        <div class="ms-4">
            <h2 class="mb-1">Pengajuan kompensasi cuti</h2>
            <div class="text-muted">Kalau Kamu masih punya sisa cuti tahunan bisa diuangkan dengan cara mengajukan kompensasi melalui form di bawah ini.</div>
        </div>
    </div>
    <div class="card card-body border-0">
        <div class="row justify-content-center">
            <div class="col-xl-11 col-xxl-9">
                @error('dates.*')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @enderror
                <form class="form-confirm form-block" action="{{ route('portal::vacation.cashable.store') }}" method="post"> @csrf
                    <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label required">Jenis cuti</label>
                        <div class="col-md-8">
                            <div class="card mb-0">
                                @foreach ($quotas->groupBy(fn($quota) => $quota->category->type->label()) as $type => $_quotas)
                                    <div class="card-header border-bottom-0 text-muted small text-uppercase" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($type) }}" style="cursor: pointer;">{{ $type }} <i class="mdi mdi-chevron-down float-end"></i></div>
                                    <div class="list-group list-group-flush collapse {{ $_quotas->first()->category->type->quotaVisibility() == true ? 'show' : '' }}" id="collapse-{{ Str::slug($type) }}">
                                        @foreach ($_quotas as $quota)
                                            @php($is_remain = !is_null($quota->quota) && $quota->remain <= 0)
                                            <label class="list-group-item d-flex align-items-center {{ $is_remain ? 'text-muted bg-soft-secondary' : '' }}">
                                                <input class="form-check-input me-3" type="radio" name="quota_id" value="{{ $quota->id }}" data-quota="{{ is_null($quota->quota) ? '∞' : ($quota->remain <= 0 ? 0 : $quota->remain) }}" data-limit="{{ $quota->remain < $quota->category->meta->cashable_limit ? $quota->remain : $quota->category->meta->cashable_limit }}" @if ($is_remain) disabled @endif required>
                                                <div>
                                                    <div class="fw-bold mb-0">{{ $quota->category->name }}</div>
                                                    <div class="small text-muted">Sisa kuota {{ is_null($quota->quota) ? '∞' : ($quota->remain <= 0 ? 0 : $quota->remain) }} hari, maksimal yang bisa diuangkan adalah {{ $quota->remain < $quota->category->meta->cashable_limit ? $quota->remain : $quota->category->meta->cashable_limit }} hari</div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @error('quota_id')
                                <div class="small text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3">
                            <div class="alert alert-info mb-0">
                                <h6 class="fw-bold">Informasi!</h6>
                                <div>Pengajuan ini hanya tersedia satu kali saja di tahun ini (2022), untuk tahun depan, jika Kamu memiliki sisa <strong>Cuti Tahunan</strong> akan otomatis kami tarik maksimal 5 hari untuk dikompensasikan, lalu jika masih memiliki sisa akan diakumulasikan pada tahun berikutnya, terima kasih.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label required">Jumlah hari</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control @error('days') is-invalid @enderror" name="days" min="1" max="" onkeyup="countRemainQuota()">
                            <div class="form-text">Jumlah hari cuti yang akan diuangkan</div>
                            @error('days')
                                <div class="small text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label">Sisa hari cuti</label>
                        <div class="col-md-4">
                            <div class="fw-bold my-1 py-1"><span id="remain">0</span> hari</div>
                            <div class="form-text">Sisa hari cuti yang diakumulasikan ke tahun depan</div>
                        </div>
                    </div>
                    <div class="row mb-3 pt-3">
                        <div class="col-lg-8 offset-lg-4 offset-xl-3">
                            <button class="btn btn-soft-danger"><i class="mdi mdi-exit-to-app"></i> Ajukan</button>
                            <a class="btn btn-ghost-light text-dark" href="{{ request('next', route('portal::vacation.submission.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tbody = document.querySelector('#fields-options-tbody');
        let limit = 0;

        document.addEventListener('DOMContentLoaded', () => {
            [].slice.call(document.querySelectorAll('[name="quota_id"]')).map((e, i) => {
                if (i == 0) {
                    e.checked = true;
                    setQuota(e);
                }
                e.addEventListener('click', (e) => {
                    setQuota(e.target)
                });
            });
        });

        const setQuota = (el) => {
            if (el.dataset.limit) {
                limit = JSON.parse(el.dataset.limit);
                limit = limit < 0 ? 365 : limit;

                document.querySelector('[name="days"]').max = limit;
            }
            countRemainQuota();
        }

        const countRemainQuota = () => {
            let days = document.querySelector('[name="days"]');
            if (days.value > limit) {
                days.value = limit;
            }
            let result = document.querySelector('[name="quota_id"]:checked').dataset.quota - days.value;
            document.querySelector('#remain').innerHTML = result;
        }
    </script>
@endpush
