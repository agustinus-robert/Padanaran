@extends('account::layouts.default')

@section('title', 'Ubah profil | ')

@section('content')
	<div class="row justify-content-center">
		<div class="col-xl-9">
			<div class="d-flex align-items-center">
				<a class="text-decoration-none" href="{{ request('next', route('account::index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
				<div class="ms-4">
					<h2 class="mb-1">Ubah profil</h2>
					<div class="text-muted">Perubahan informasi dibawah akan diterapkan di semua Akun Anda</div>
				</div>
			</div>
			<hr class="my-4">

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
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-xl-3 col-lg-4">
			@include('x-account::User.list-group-account-menu')
		</div>
		<div class="col-xl-6 col-lg-8">
			<div class="card mb-4">
				<div class="card-body p-4">
					<form class="form-block" action="{{ route('account::user.profile', ['next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
						@include('x-account::User.Profile.form', ['user' => Auth::user(), 'back' => true])
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
