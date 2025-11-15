@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'News Subscribe')

@section('navtitle', 'News Subscribe')

@section('content')
<div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column me-3">
        <!--begin::Title-->
        <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">News & Subscribe</h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-gray-600">
                <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
            </li>

            <li class="breadcrumb-item text-gray-600">News & Subscribe</li>
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

    <div class="row">
    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total News & Subscribe</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$newsub}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {{-- <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i> --}}
                    List of News & Subscribe Data
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
        </div>

        <div class="card-body pt-0">
            @livewire('admin::datatables.new-sub-datatable') 
        </div>
    </div>
</div>
@endsection