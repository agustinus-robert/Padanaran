@extends('portal::layouts.user')

@extends('portal::layouts.components.navbar-user')

@section('title', 'Pengaturan Akun')

@section('navtitle', 'Pengaturan Akun')

@section('content')
	<div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column me-3">
			<!--begin::Title-->
			<h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">User</h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
				<!--begin::Item-->
				<li class="breadcrumb-item text-gray-600">
					<a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
				</li>

				<li class="breadcrumb-item text-gray-600">User</li>
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
	</div>

	@livewire('portal::account-management')
@endsection