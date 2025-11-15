@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('admin::builder.menu')
    @else
        <div class="toolbar mb-lg-7 mb-5" id="kt_toolbar">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column me-3">
                <!--begin::Title-->
                <h1 class="d-flex fw-bold fs-3 my-1 text-gray-900">Menu</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-1 text-gray-600">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">
                        <a href="index.html" class="text-hover-primary text-gray-600">Dashboard</a>
                    </li>

                    <li class="breadcrumb-item text-gray-600">Menu</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>

        <div class="content flex-column-fluid" id="kt_content">
            @if (Session::has('msg'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-success">
                        {{ Session::get('msg') }}
                    </div>
                </div>
            @endif

            @if (Session::has('msg-server'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert-danger alert">
                        {{ Session::get('msg') }}
                    </div>
                </div>
            @endif

            <!--begin::Products-->
            <div class="card-flush card">
                <!--begin::Card header-->
                <div class="card-header align-items-center gap-md-5 gap-2 py-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            {{-- <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
							<span class="path1"></span>
							<span class="path2"></span>
						</i> --}}
                            List Of Post Data
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Add product-->
                        <a href="{{ route('admin::builder.menu.create') }}" class="btn btn-primary"> Add Menu</a>
                        <!--end::Add product-->
                    </div>
                    <!--end::Card toolbar-->
                </div>

                <div class="card-body pt-0">
                    @livewire('admin::datatables.menu-datatable')
                </div>
            </div>
        </div>
    @endif


@endsection
