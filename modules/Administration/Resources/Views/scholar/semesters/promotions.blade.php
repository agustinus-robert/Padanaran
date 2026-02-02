@extends('layouts.horizontal-layout')

@section('title', 'Registrasi semester - ')

@section('navtitle', 'Promosi')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.semesters.index')) }}">Registrasi semester</a></li>
    <li class="breadcrumb-item active">Kenaikan kelas</li>
@endsection

@push('nav')
@include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')
    <div class="row container-fluid">
        @include('components.navbar-admin')
        <div class="col-md-8">
            <div class="card mb-4">
                <x-card-header type="{{ config('theme.default') }}">
                    Kenaikan Kelas
                </x-card-header>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if (session('success'))
                                <div id="flash-success" class="alert alert-success mt-4">
                                    {!! session('success') !!}
                                </div>
                            @endif

                            @if (session('danger'))
                                <div id="flash-danger" class="alert alert-danger mt-4">
                                    {!! session('danger') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    <form id="filter-form" action="{{ route('administration::scholar.semesters.promotions') }}" method="GET">
                        <x-input-group isForm="true" :isRow="true" required>
                            <x-label>Siswa T.A.</x-label>
                            <x-select
                                name="acsem"
                                required
                                placeholder="-- Pilih tahun ajaran --"
                                :value="request('acsem', $acsem->id)"
                                data-live-search="true"
                                class="form-control mb-sm-0 mr-sm-3 mb-2"
                                :options="$acsems->where('open', 1)->map(fn($_acsem) => [
                                    'value' => $_acsem->id,
                                    'label' => $_acsem->full_name,
                                ])"
                            />
                        </x-input-group>
                    </form>
                    <form action="{{ route('administration::scholar.semesters.promote') }}" method="POST"> @csrf
                        <x-input-group isForm="true" :isRow="true" required>
                            <x-select
                                name="students"
                                multiple
                                size="10"
                                :value="old('students', [])"
                                :options="$stsems->map(fn($stsem) => [
                                    'value' => $stsem->student->id,
                                    'label' => $stsem->classroom->name
                                        ? $stsem->classroom->name . ' - ' . $stsem->student->user->profile->full_name
                                        : $stsem->student->user->profile->full_name,
                                ])"
                            />
                            @error('students.0')
                                <span class="text-danger">Siswa yang Anda pilih tidak valid</span>
                            @enderror
                        </x-input-group>

                        <x-input-group isForm="true" :isRow="true" required>
                            <x-label>Tahun Ajaran Baru</x-label>

                            <x-select
                                name="semester_id"
                                required
                                :value="old('semester_id')"
                                :options="$acsems->where('open', 1)->map(fn($_acsem) => [
                                    'value' => $_acsem->id,
                                    'label' => $_acsem->full_name,
                                    'attributes' => [
                                        'data-classrooms' => $_acsem->classrooms,
                                    ],
                                ])"
                            />

                            @error('semester_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-input-group>

                        <x-input-group isForm="true" :isRow="true" required>
                            <x-label>Rombel yang dituju</x-label>
                            <x-select
                                name="classroom_id"
                                placeholder="-- Pilih rombel --"
                                :value="old('classroom_id')"
                                :class="$errors->has('classroom_id') ? 'is-invalid' : ''"
                                :options="collect($aclassRoom ?? [])->map(fn($room) => [
                                    'value' => $room->id,
                                    'label' => $room->name,
                                ])"
                            />

                            @error('classroom_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </x-input-group>

                        <div class="alert alert-info d-none" id="msg-alert">
                            Anda akan meregesitrasikan <strong><span id="msg-count">0</span> siswa</strong> ke kelas <strong><span id="msg-classroom"></span></strong>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a class="btn btn-secondary" href="{{ request('next', route('administration::scholar.semesters.index')) }}"> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="text-black">Lanjutan</h6>
                </div>

                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::scholar.students.index') }}"><i class="mdi mdi-account-group-outline"></i> Data siswa</a>
                    <a class="list-group-item list-group-item-action text-black" href="{{ route('administration::scholar.semesters.index') }}"><i class="mdi mdi-account-group-outline"></i> Registrasi semester</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-duallistbox.min.css') }}">
    <script src="{{ asset('js/bootstrap-duallistbox.min.js') }}"></script>

    <script>
        $(() => {
            setClassrooms();

            $('[name="acsem"]').on('change', (e) => {
                $('#filter-form').submit();
            });

            $('[name="semester_id"]').on('change', (e) => {
                setClassrooms();
                setAlert();
            });

            $('[name="students[]"]').bootstrapDualListbox({
                moveOnSelect: false,
                nonSelectedListLabel: 'Siswa Tahun Ajaran <strong>{{ $acsem->full_name }}</strong>',
                selectedListLabel: 'Siswa yang dinaikkan kelasnya'
            });

            $('[name="students[]"],[name="classroom_id"]').on('change', (e) => {
                setAlert();
            })

            function setClassrooms() {
                var s = $('[name="semester_id"]');
                var c = '';
                for (i of s.children('option:selected').data('classrooms')) {
                    c += '<option value="' + i.id + '">' + i.name + '</option>'
                }
                $('[name="classroom_id"]').html(c);
            }

            function setAlert() {
                let count = $('[name="students[]"] :selected').length;
                $('#msg-count').html(count);
                $('#msg-classroom').html($('[name="classroom_id"] :selected').text() + ' - ' + $('[name="semester_id"] :selected').text());
                count ? $('#msg-alert').removeClass('d-none') : $('#msg-alert').addClass('d-none');
            }
        })
    </script>
@endpush
