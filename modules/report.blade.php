@extends('academic::layouts.default')

@section('content')
    <div class="row">
		<div class="col-md-8">
			<div class="jumbotron bg-white border py-4 mb-4">
				<h2>Assalamu'alaikum {{ \Str::title(auth()->user()->profile->full_name) }}!</h2>
				<p class="text-muted">Selamat datang di {{ config('academic.home.name') }}</p>
				<hr>
				Tahun Ajaran <strong>{{ $acsem->full_name }}</strong>
			</div>
			<div class="card">
				<div class="card-header"><i class="mdi mdi-school-outline"></i> <strong>Nilai raport</strong> T.A. <strong>{{ $acsem->full_name }}</strong></div>
				<div class="table-responsive">
					<table class="table table-bordered mb-0">
						<thead class="thead-dark">
							<tr>
								<th>No</th>
								<th>Mapel</th>
								<th>Nilai</th>
							</tr>
						</thead>
						<tbody>
							@if($stsem)
								@forelse($stsem->reports as $report)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ $report->subject->name }}</td>
										<td>{{ $report->value }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="3">Tidak ada nilai raport</td>
									</tr>
								@endforelse
							@else
								<tr>
									<td colspan="3">Tidak ada semester aktif</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-body text-center">
				    <div class="py-4">
				        <img class="rounded-circle" src="{{ asset('img/default-avatar.svg') }}" alt="" width="128">
				    </div>
				    <h5 class="mb-1"><strong>{{ $user->profile->full_name }}</strong></h5>
				    <p>
				        NIS. {{ $student->nis ?: '-' }}
				        @if($student->nisn)
				            <br> NISN. {{ $student->nisn }}
				        @endif
				    </p>
				    <h4 class="mb-0">
				        @if($user->phone->whatsapp)
				            <a class="text-primary px-1" href="https://wa.me/{{ $user->phone->number }}" target="_blank"><i class="mdi mdi-whatsapp"></i></a>
				        @endif
				        @if($user->email->verified_at)
				            <a class="text-danger px-1" href="mailto:{{ $user->email->address }}"><i class="mdi mdi-email-outline"></i></a>
				        @endif
				    </h4>
				</div>
				<div class="list-group list-group-flush border-top">
				    @foreach([
				        'Angkatan ke' => optional($student->generation)->name ?: '-',
				        'Masuk pada' => optional($student->entered_at)->diffForHumans() ?: '-',
				        'Bergabung pada' => $user->created_at->diffForHumans(),
				    ] as $k => $v)
				        <div class="list-group-item border-0">
				            {{ $k }} <br>
				            <span class="{{ $v ? 'font-weight-bold' : 'text-muted' }}">
				                {{ $v ?? 'Belum diisi' }}
				            </span>
				        </div>
				    @endforeach
				    <div class="list-group-item border-0 text-muted">
				        <i class="mdi mdi-account-circle"></i> User ID : {{ $user->id }}
				    </div>
				</div>
			</div>
		</div>
	</div>
@endsection
