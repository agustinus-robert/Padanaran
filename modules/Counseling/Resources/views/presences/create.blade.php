@extends('layouts.horizontal-layout')

@section('title', 'Input presensi baru - ')

@section('navtitle', 'Presensi')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@section('body-content')
@include('components.navbar-admin')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7 col-lg-8">
                <div class="card mb-4">
                    <x-card-header type="{{ config('theme.default') }}">
                        Input presensi baru
                    </x-card-header>

                    <div class="card-body">
                        <form action="{{ route('counseling::presences.create') }}" method="GET">
                        <x-input-group :isRow="true" :isInputGroup="true" label="">
                                    <div class="col-md-11">
                                        <x-select
                                            name="classroom"
                                            placeholder="Pilih rombel"
                                            :options="$acsem->classrooms->map(function($classroom) {
                                                return [
                                                    'value' => $classroom->id,
                                                    'label' => $classroom->full_name,
                                                    'selected' => request('classroom') == $classroom->id
                                                ];
                                            })->toArray()"
                                        />
                                    </div>

                                    <div class="col-md-1 p-1">
                                        {{-- <a class="btn btn-outline-secondary" href="{{ route('counseling::presences.create') }}">
                                            <i class="mdi mdi-refresh"></i>
                                        </a> --}}
                                        <x-btn type="submit" variant="dark">Cari</x-btn>
                                    </div>
                            </x-input-group>

                            <small class="text-muted">Menampilkan data presensi Tahun Ajaran <strong>{{ $acsem->full_name }}</strong></small>
                        </form>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-circle-outline mr-1"></i>
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- SESSION ERROR (optional) --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close-circle-outline mr-1"></i>
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    @isset($currentClassroom)
                        <form class="form-block form-confirm" action="{{ route('counseling::presences.store', ['next' => url()->current()]) }}" method="POST"> @csrf
                            <input type="hidden" name="classroom_id" value="{{ $currentClassroom->id }}">
                            <div class="table-responsive bg-white">
                                <table class="table-bordered table-striped table-hover mb-0 table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            @foreach ($presenceList as $v)
                                                <th class="text-center">{{ strtoupper(substr($v, 0, 1)) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($currentClassroom->stsems ?? [] as $stsem)
                                            @php($student = $stsem->student)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $student->nis }}</td>
                                                <td nowrap>{{ $student->full_name }}</td>
                                                @foreach ($presenceList as $k => $v)
                                                    <td class="clickable-radio text-center">
                                                        <div class="custom-control custom-radio" style="margin-left: 6px;">
                                                            <input type="hidden" name="presence[{{ $stsem->id }}][student_id]" value="{{ $student->id }}">
                                                            <input type="radio"
                                                                id="presence.{{ $stsem->id . '.' . $k }}"
                                                                name="presence[{{ $stsem->id }}][type]"
                                                                class="custom-control-input"
                                                                value="{{ $k }}"
                                                                @if ($loop->first) checked @endif>

                                                            <label class="custom-control-label" for="presence.{{ $stsem->id . '.' . $k }}"></label>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            @if ($loop->last)
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="py-1" colspan="{{ count($presenceList) }}">
                                                        <input type="datetime-local" class="form-control @error('presenced_at') is-invalid @enderror" name="presenced_at" value="{{ old('presenced_at', now()->format('Y-m-d\TH:i')) }}" required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="py-1" colspan="{{ count($presenceList) }}">
                                                        <button type="submit" class="btn btn-{{ $meet->props->color ?? 'primary' }} btn-block"><i class="mdi mdi-check-circle-outline"></i> Simpan</button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="{{ count($presenceList) + 3 }}">Tidak ada data siswa</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @else
                        <div class="card-body text-muted border-top">
                            Silahkan pilih rombel terlebih dahulu
                        </div>
                    @endisset
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                @include('counseling::includes.employee-info', ['employee' => $user])
                @include('account::includes.account-info')
            </div>
        </div>
    </div>
@endsection
