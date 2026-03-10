@extends('layouts.horizontal-layout')

@section('title', 'Data siswa - ')

@section('breadcrumb')
    <li class="breadcrumb-item">Kesiswaan</li>
    <li class="breadcrumb-item"><a href="{{ request('next', route('administration::scholar.students.index')) }}">Siswa</a></li>
    <li class="breadcrumb-item active">{{ $student ? 'Ubah' : 'Tambah' }}</li>
@endsection

@push('nav')
    @include('administration::layouts.includes.navbar-administration')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-md-10">
                {{-- <h2 class="mb-4">
                    <a class="text-decoration-none small" href="{{ request('next', route('administration::scholar.students.index')) }}">
                        <i class="mdi mdi-arrow-left-circle-outline"></i>
                    </a>
                    {{ $student ? 'Ubah siswa' : 'Tambah siswa' }}
                </h2> --}}

                <div class="card mb-4">
                    <x-card-header type="{{ config('theme.default') }}">
                        {{ isset($student) ? 'Edit Rombel' : 'Tambah Rombel' }}
                    </x-card-header>

                    <div class="card-body">
                        <form action="{{ $student
                            ? route('administration::scholar.students.update', $student)
                            : route('administration::scholar.students.store', ['next' => request('next', route('administration::scholar.students.index'))])
                        }}" method="POST" class="form-block">
                            @csrf
                            @if($student)
                                @method('PUT')
                            @endif

                            {{-- Tahun ajaran --}}
                            <x-input-group label="Tahun ajaran masuk" :required="true">
                                <x-select name="acdmc_id" :value="old('acdmc_id', $student?->acdmc_id)" :options="$acdmcs->map(fn($a)=>['value'=>$a->id,'label'=>$a->name])"/>
                            </x-input-group>

                            {{-- Nama --}}
                            <x-input-group label="Nama lengkap siswa" :required="true">
                                <x-input name="name" placeholder="Nama siswa" :value="old('name', $student?->user->profile->name)"/>
                            </x-input-group>

                            {{-- NIS --}}
                            <x-input-group label="NIS" :required="true">
                                <x-input type="number" name="nis" placeholder="NIS" :value="old('nis', $student?->nis)"/>
                            </x-input-group>

                            {{-- NISN --}}
                            <x-input-group label="NISN">
                                <x-input type="number" name="nisn" placeholder="NISN" :value="old('nisn', $student?->nisn)"/>
                            </x-input-group>

                            <hr>

                            {{-- NIK --}}
                            <x-input-group label="NIK">
                                <x-input type="number" name="nik" placeholder="NIK" :value="old('nik', $student?->nik)"/>
                            </x-input-group>

                            {{-- Tempat lahir --}}
                            <x-input-group label="Tempat lahir">
                                <x-input name="pob" placeholder="Tempat lahir" :value="old('pob', $student?->pob)"/>
                            </x-input-group>

                            {{-- Tanggal lahir --}}
                            <x-input-group label="Tanggal lahir">
                                <x-input name="dob" placeholder="Tanggal lahir" :value="old('dob', $student?->dob)" data-mask="00-00-0000"/>
                                <small class="form-text text-muted">Format hh-bb-tttt (ex: 23-02-2001)</small>
                            </x-input-group>

                            {{-- Jenis kelamin --}}
                            <x-input-group label="Jenis kelamin">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    @foreach (\Modules\Account\Models\UserProfile::$sex as $k => $v)
                                        <label class="btn btn-outline-secondary @if(old('sex', $student?->user->profile->sex ?? -1) == $k) active @endif">
                                            <input type="radio" name="sex" value="{{ $k }}" autocomplete="off" @if(old('sex', $student?->user->profile->sex ?? -1) == $k) checked @endif> {{ $v }}
                                        </label>
                                    @endforeach
                                </div>
                            </x-input-group>

                            {{-- Hobi --}}
                            <x-input-group label="Hobi">
                                <x-select name="hobby_id" :value="old('hobby_id', $student?->hobby_id)" :options="$hobbies->map(fn($h)=>['value'=>$h->id,'label'=>$h->name])" placeholder="-- Pilih --"/>
                            </x-input-group>

                            {{-- Cita-cita --}}
                            <x-input-group label="Cita-cita">
                                <x-select name="desire_id" :value="old('desire_id', $student?->desire_id)" :options="$desires->map(fn($d)=>['value'=>$d->id,'label'=>$d->name])" placeholder="-- Pilih --"/>
                            </x-input-group>

                            <hr>

                            {{-- Tanggal masuk --}}
                            <x-input-group label="Tanggal masuk">
                                <x-input name="entered_at" placeholder="Tanggal masuk" :value="old('entered_at', $student?->entered_at)" data-mask="00-00-0000"/>
                            </x-input-group>

                            {{-- Tombol --}}
                            <x-input-group>
                                <x-btn type="submit" variant="success">{{ $student ? 'Perbarui' : 'Simpan' }}</x-btn>
                                <x-btn href="{{ request('next', route('administration::scholar.students.index')) }}" variant="secondary">Kembali</x-btn>
                            </x-input-group>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
