@extends('portal::layouts.user')

@extends('portal::layouts.components.navbar-user')

@section('title', 'User')

@section('navtitle', 'User')

@section('content')
<div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
	<!--begin::Page title-->
	<div class="page-title d-flex flex-column me-3">
		<!--begin::Title-->
		<h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Event History</h1>
		<!--end::Title-->
		<!--begin::Breadcrumb-->
		<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
			<!--begin::Item-->
			<li class="breadcrumb-item text-gray-600">
				<a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
			</li>
			<li class="breadcrumb-item text-gray-500">Event History</li>
			<!--end::Item-->
		</ul>
		<!--end::Breadcrumb-->
	</div>
</div>

<div class="card mb-6 mb-xl-9">
	<div class="card-body pt-9 pb-0">
		<!--begin::Details-->
		<div class="d-flex flex-wrap flex-sm-nowrap mb-6">
			<!--begin::Image-->
			<div class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
				<img class="mw-50px mw-lg-75px" src="assets/media/svg/brand-logos/volicity-9.svg" alt="image">
			</div>
			<!--end::Image-->
			<!--begin::Wrapper-->
			<div class="flex-grow-1">
				<!--begin::Head-->
				<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
					<!--begin::Details-->
					<div class="d-flex flex-column">
						<!--begin::Status-->
						<div class="d-flex align-items-center mb-1">
							<a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bold me-3">History Event</a>
						</div>
						<!--end::Status-->
						<!--begin::Description-->
						<div class="d-flex flex-wrap fw-semibold mb-4 fs-5 text-gray-500">Silahkan Ikuti event, dan meriahkan acara kami</div>
						<!--end::Description-->
					</div>
					<!--end::Details-->
					<!--begin::Actions-->
					<div class="d-flex mb-4">
						<!--begin::Menu-->
						<div class="me-0">
							<button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
								<i class="ki-solid ki-dots-horizontal fs-2x"></i>
							</button>
							<!--begin::Menu 3-->
							<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
								<!--begin::Heading-->
								<div class="menu-item px-3">
									<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
								</div>
								<!--end::Heading-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link px-3">Create Invoice</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link flex-stack px-3">Create Payment 
									<span class="ms-2" data-bs-toggle="tooltip" aria-label="Specify a target name for future usage and reference" data-bs-original-title="Specify a target name for future usage and reference" data-kt-initialized="1">
										<i class="ki-duotone ki-information fs-6">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
										</i>
									</span></a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link px-3">Generate Bill</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
									<a href="#" class="menu-link px-3">
										<span class="menu-title">Subscription</span>
										<span class="menu-arrow"></span>
									</a>
									<!--begin::Menu sub-->
									<div class="menu-sub menu-sub-dropdown w-175px py-4">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">Plans</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">Billing</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">Statements</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content px-3">
												<!--begin::Switch-->
												<label class="form-check form-switch form-check-custom form-check-solid">
													<!--begin::Input-->
													<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications">
													<!--end::Input-->
													<!--end::Label-->
													<span class="form-check-label text-muted fs-6">Recuring</span>
													<!--end::Label-->
												</label>
												<!--end::Switch-->
											</div>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu sub-->
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3 my-1">
									<a href="#" class="menu-link px-3">Settings</a>
								</div>
								<!--end::Menu item-->
							</div>
							<!--end::Menu 3-->
						</div>
						<!--end::Menu-->
					</div>
					<!--end::Actions-->
				</div>
				<!--end::Head-->
				<!--begin::Info-->
				<div class="d-flex flex-wrap justify-content-start">
					<!--begin::Stats-->
					<div class="d-flex flex-wrap">
						<!--begin::Stat-->
						<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
							<!--begin::Number-->
							<div class="d-flex align-items-center">
								<i class="ki-duotone ki-arrow-down fs-3 text-danger me-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<div class="fs-4 fw-bold counted" data-kt-countup="true" data-kt-countup-value="{{$event}}" data-kt-initialized="1">{{$event}}</div>
							</div>
							<!--end::Number-->
							<!--begin::Label-->
							<div class="fw-semibold fs-6 text-gray-500">Jumlah Event Diikuti</div>
							<!--end::Label-->
						</div>
					</div>
					<!--end::Stats-->
					<!--begin::Users-->
				
					<!--end::Users-->
				</div>
				<!--end::Info-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Details-->
		<div class="separator"></div>
		<!--begin::Nav-->
		<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
			<!--begin::Nav item-->
			<li class="nav-item">
				<a class="nav-link text-active-primary py-5 me-6" href="{{route('portal::guest.volunteer.index')}}">Daftar Event</a>
			</li>
			<!--end::Nav item-->
			<!--begin::Nav item-->
			<li class="nav-item">
				<a class="nav-link text-active-primary py-5 me-6 active" href="{{route('portal::guest.history_volunteer')}}">History</a>
			</li>
		</ul>
		<!--end::Nav-->
	</div>
</div>

<div class="row g-6 g-xl-9">
	@livewire('portal::datatables.volunteer-datatable')
</div>
@endsection