<nav class="navbar navbar-expand-sm navbar-light bg-white" style="height: 80px;">
	<div class="container-lg">
		<a class="navbar-brand" href="{{ config('app.url') }}">
			<img src="{{ asset('img/logo/logo-text.svg') }}" height="24" class="me-2">
		</a>
		<ul class="navbar-nav ms-auto flex-row align-items-center">
			<li class="nav-item dropdown">
				<a class="nav-link px-2 mx-md-2 mx-1" href="javascript:;" role="button" data-bs-toggle="dropdown">
					<i class="mdi mdi-apps" style="font-size: 20px;"></i>
				</a>
				@include('components.apps')
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link px-2 mx-md-2 mx-1" href="javascript:;" role="button" data-bs-toggle="dropdown">
					<i class="mdi mdi-headphones" style="font-size: 20px;"></i>
				</a>
				@include('components.support')
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link px-2 mx-md-2 mx-1" href="javascript:;" role="button" data-bs-toggle="dropdown">
					<i class="mdi mdi-bell-outline" style="font-size: 20px;"></i>
					@if(Auth::user()->notifications->whereNull('read_at')->count())
						<span class="bg-danger text-white float-end rounded-circle pulse-danger" style="height: 8px; width: 8px;"></span>
					@endif
				</a>
				@include('x-account::User.navbar-notifications-dropdown', ['user' => Auth::user()])
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link d-flex align-items-center ms-md-2 ms-1" href="javascript:;" role="button" data-bs-toggle="dropdown">
					<div class="rounded-circle" style="background: url('{{ Auth::user()->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 32px; height: 32px;"></div>
				</a>
				@include('x-account::User.navbar-accounts-dropdown')
			</li>
		</ul>
	</div>
</nav>