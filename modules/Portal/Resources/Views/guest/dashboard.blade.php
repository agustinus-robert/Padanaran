@extends('portal::layouts.user')

@extends('portal::layouts.components.navbar-user')

@section('title', 'User')

@section('navtitle', 'User')

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

	<div class="content flex-column-fluid" id="kt_content">
		<!--begin::Row-->
		<div class="row gx-5 gx-xl-10 mb-xl-10">
			<!--begin::Col-->
			<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
				<!--begin::Card widget 16-->
				<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 h-md-50 mb-5 mb-xl-10" style="background-color: #080655">
					<!--begin::Header-->
					<div class="card-header pt-5">
						<!--begin::Title-->
						<div class="card-title d-flex flex-column">
							<!--begin::Amount-->
							<span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">69</span>
							<!--end::Amount-->
							<!--begin::Subtitle-->
							<span class="text-white opacity-50 pt-1 fw-semibold fs-6">Active Projects</span>
							<!--end::Subtitle-->
						</div>
						<!--end::Title-->
					</div>
					<!--end::Header-->
					<!--begin::Card body-->
					<div class="card-body d-flex align-items-end pt-0">
						<!--begin::Progress-->
						<div class="d-flex align-items-center flex-column mt-3 w-100">
							<div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
								<span>43 Pending</span>
								<span>72%</span>
							</div>
							<div class="h-8px mx-3 w-100 bg-light-danger rounded">
								<div class="bg-danger rounded h-8px" role="progressbar" style="width: 72%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
						<!--end::Progress-->
					</div>
					<!--end::Card body-->
				</div>
				<!--end::Card widget 16-->
				<!--begin::Card widget 7-->
				<div class="card card-flush h-md-50 mb-5 mb-xl-10">
					<!--begin::Header-->
					<div class="card-header pt-5">
						<!--begin::Title-->
						<div class="card-title d-flex flex-column">
							<!--begin::Amount-->
							<span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">357</span>
							<!--end::Amount-->
							<!--begin::Subtitle-->
							<span class="text-gray-500 pt-1 fw-semibold fs-6">Professionals</span>
							<!--end::Subtitle-->
						</div>
						<!--end::Title-->
					</div>
					<!--end::Header-->
					<!--begin::Card body-->
					<div class="card-body d-flex flex-column justify-content-end pe-0">
						<!--begin::Title-->
						<span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Today’s Heroes</span>
						<!--end::Title-->
						<!--begin::Users group-->
						<div class="symbol-group symbol-hover flex-nowrap">
							<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
								<span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
							</div>
							<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michael Eberon">
								<img alt="Pic" src="assets/media/avatars/300-11.jpg" />
							</div>
							<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Susan Redwood">
								<span class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
							</div>
							<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Melody Macy">
								<img alt="Pic" src="assets/media/avatars/300-2.jpg" />
							</div>
							<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Perry Matthew">
								<span class="symbol-label bg-danger text-inverse-danger fw-bold">P</span>
							</div>
							<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Barry Walter">
								<img alt="Pic" src="assets/media/avatars/300-12.jpg" />
							</div>
							<a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
								<span class="symbol-label bg-dark text-gray-300 fs-8 fw-bold">+42</span>
							</a>
						</div>
						<!--end::Users group-->
					</div>
					<!--end::Card body-->
				</div>
				<!--end::Card widget 7-->
			</div>
		</div>
	</div>
@endsection