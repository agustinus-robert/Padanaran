@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Account')

@section('navtitle', 'Account')

@section('content')
	<div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column me-3">
			<!--begin::Title-->
			<h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Account</h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
				<!--begin::Item-->
				<li class="breadcrumb-item text-gray-600">
					<a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
				</li>

				<li class="breadcrumb-item text-gray-600">Account</li>
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
	</div>

	<div class="content flex-column-fluid" id="kt_content">
		@if (Session::has('msg'))
		    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1500)" x-show="show">
		        <div class="alert alert-success">
		            {{ Session::get('msg') }}
		        </div>
		    </div>
		@endif

		@if (Session::has('msg-server'))
		    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1500)" x-show="show">
		        <div class="alert alert-danger">
		            {{ Session::get('msg') }}
		        </div>
		    </div>
		@endif

		@livewire('admin::builder.account')
	</div>
@endsection