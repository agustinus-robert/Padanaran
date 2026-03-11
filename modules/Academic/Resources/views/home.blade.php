@extends('layouts.horizontal-layout')

@section('title', 'Dasbor | ')

@section('titleTemplate', config('account.admin.name'))

@section('navtitle', 'Dasbor')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('academic::layouts.includes.navbar-academic')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">Academic</li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('body-content')
    @if(config('theme.default') == 'material')
        @include('layouts.component.material-admin-top-nav')
    @endif

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @include('components.alertion-message')
            </div>
            
            <div class="col-lg-8">
                {{-- WELCOME BANNER --}}
                <div class="card card-body border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1 text-dark">
                                Assalamu'alaikum {{ session('login_as_nik') ? 'Wali,' : '' }} {{ \Str::title($user->name) }}!
                            </h3>
                            <p class="text-muted mb-0 small">Selamat datang di {{ config('academic.home.name') }}</p>
                        </div>
                        <div class="ms-auto text-end">
                            <span class="badge bg-gradient-primary">T.A. {{ $acsem->full_name }}</span>
                        </div>
                    </div>
                </div>

                {{-- DATA NILAI (ACCORDION STYLE) --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent pb-0 border-0">
                        <h6 class="mb-0 font-weight-bolder"><i class="material-symbols-rounded align-middle me-2">assessment</i>Data Nilai</h6>
                    </div>
                    <div class="card-body px-0 pt-2">
                        <div class="accordion accordion-flush" id="accordionAssessment">
                            @if (!empty($stsem))
                                @forelse($stsem->classroom->meets as $meet)
                                    <div class="accordion-item border-bottom">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button @if (!$loop->first) collapsed @endif text-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $meet->id }}">
                                                <i class="material-symbols-rounded me-2 text-{{ $meet->props->color ?? 'primary' }} opacity-7">label</i>
                                                {{ $meet->subject->name }}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $meet->id }}" class="accordion-collapse collapse @if ($loop->first) show @endif" data-bs-parent="#accordionAssessment">
                                            <div class="accordion-body p-0">
                                                @php($assessments = $stsem->assessments->where('subject_id', $meet->subject_id))
                                                <ul class="list-group list-group-flush">
                                                    @forelse($assessments as $assessment)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-100-soft border-0 mx-3 my-1 border-radius-sm">
                                                            <span class="text-xs font-weight-bold">{{ $assessment->type_name }}</span>
                                                            <span class="badge badge-sm bg-white text-dark shadow-xs border-radius-sm">{{ $assessment->value }}</span>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item text-xs text-muted ps-4 py-3">Belum ada data nilai</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-sm text-muted">Tidak ada jadwal yang diterapkan</div>
                                @endforelse
                            @else
                                <div class="p-4 text-center text-sm text-danger">Tidak ada semester aktif</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- DATA KASUS --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent pb-0 border-0">
                        <h6 class="mb-0 font-weight-bolder text-danger"><i class="material-symbols-rounded align-middle me-2">gavel</i>Data Kasus / Kedisiplinan</h6>
                    </div>
                    <div class="card-body p-3">
                        @if (!empty($stsem))
                            @forelse($stsem->cases as $case)
                                <div class="d-flex bg-gray-100 p-3 border-radius-lg mb-3 align-items-center">
                                    <div class="icon icon-shape bg-white shadow-sm text-center border-radius-md me-3">
                                        <i class="material-symbols-rounded text-danger opacity-10">warning</i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-sm mb-0 fw-bold text-dark">{{ $case->category->name }}</h6>
                                        <p class="text-xs text-secondary mb-1">{{ $case->description }}</p>
                                        <p class="text-xxs text-muted mb-0">Saksi: {{ $case->witness }} • {{ \Carbon\Carbon::parse($case->break_at)->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-center ms-3">
                                        <h4 class="text-danger mb-0 fw-bold">{{ $case->point ?: '0' }}</h4>
                                        <p class="text-xxs text-uppercase font-weight-bolder text-secondary mb-0">Poin</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-3">
                                    <i class="material-symbols-rounded text-success display-6 d-block mb-2">check_circle</i>
                                    <span class="text-sm text-muted">Alhamdulillah, tidak ada catatan kasus.</span>
                                </div>
                            @endforelse
                        @endif
                    </div>
                </div>

                {{-- DATA PRESTASI --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent pb-0 border-0">
                        <h6 class="mb-0 font-weight-bolder text-success"><i class="material-symbols-rounded align-middle me-2">emoji_events</i>Data Prestasi</h6>
                    </div>
                    <div class="card-body p-3">
                        @forelse(optional(optional($student)->user)->achievements ?? [] as $achievement)
                            <div class="d-flex justify-content-between align-items-center p-3 border border-radius-lg mb-2">
                                <div>
                                    <span class="badge bg-light text-dark text-xxs mb-1">{{ $achievement->type->name }}</span>
                                    <h6 class="text-sm mb-0">{{ $achievement->name }}</h6>
                                    <p class="text-xs text-secondary mb-0">Peringkat {{ $achievement->num->name }} di {{ $achievement->territory->name }} ({{ $achievement->year }})</p>
                                </div>
                                <div class="d-flex gap-2">
                                    @if (Storage::exists($achievement->file))
                                        <a href="{{ Storage::url($achievement->file) }}" target="_blank" class="btn btn-link text-primary p-0 mb-0"><i class="material-symbols-rounded">visibility</i></a>
                                    @endif
                                    <form action="{{ route('administration::scholar.students.achievements.destroy', ['student' => $student->id, 'achievement' => $achievement->id]) }}" method="POST" class="form-confirm">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 mb-0"><i class="material-symbols-rounded">delete</i></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center border border-dashed border-radius-lg">
                                <p class="text-xs text-muted mb-0">Belum ada data prestasi yang tercatat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- SIDEBAR PROFIL --}}
            <div class="col-lg-4">
                @if (!empty($student->nis))
                    <div class="card card-profile shadow-sm border-0 mb-4">
                        <div class="card-body text-center p-4">
                            <img src="{{ asset('img/default-avatar.svg') }}" class="rounded-circle shadow-lg mb-3" width="100">
                            <h5 class="mb-1 text-dark fw-bold">{{ $user->profile->full_name }}</h5>
                            <p class="text-xs text-secondary mb-3">NIS. {{ $student->nis }} | NISN. {{ $student->nisn ?? '-' }}</p>
                            
                            <div class="text-start bg-gray-100 p-3 border-radius-lg mb-3">
                                <div class="d-flex justify-content-between mb-2 text-xs">
                                    <span class="text-secondary">Angkatan:</span>
                                    <span class="fw-bold">{{ optional($student->generation)->name ?: '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-xs">
                                    <span class="text-secondary">Masuk:</span>
                                    <span class="fw-bold">{{ optional($student->entered_at)->diffForHumans() ?: '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between text-xs">
                                    <span class="text-secondary">Status Akun:</span>
                                    <span class="text-success fw-bold">Aktif</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                @if (!empty($user->phone->whatsapp))
                                    <a href="https://wa.me/{{ $user->phone->number }}" target="_blank" class="btn btn-sm btn-outline-success">WA Orang Tua</a>
                                @endif
                                @if (!empty($user->email->verified_at))
                                    <a href="mailto:{{ $user->email->address }}" class="btn btn-sm btn-outline-danger">Email</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info border-0 text-white shadow-sm text-sm" role="alert">
                        User ini tidak terdaftar sebagai murid aktif.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection