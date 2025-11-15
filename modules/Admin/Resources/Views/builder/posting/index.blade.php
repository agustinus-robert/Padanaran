@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Posting')

@section('navtitle', 'Posting')

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('admin::builder.posting')
    @else
        <div class="toolbar mb-lg-7 mb-5" id="kt_toolbar">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column me-3">
                <!--begin::Title-->
                <h1 class="d-flex fw-bold fs-3 my-1 text-gray-900">Posting {{ $type == 7 ? 'Form' : 'Data' }}</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-1 text-gray-600">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">
                        <a href="index.html" class="text-hover-primary text-gray-600">Dashboard</a>
                    </li>

                    <li class="breadcrumb-item text-gray-600">Posting {{ $type == 7 ? 'Form' : 'Data' }}</li>
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

            @if (Session::has('msg-gagal'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert-danger alert">
                        {{ Session::get('msg-gagal') }}
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

            {{-- <div class="row">
	    <div class="px-3 mb-5">
	        <div class="row justify-content-between">
	          <div class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-end-xxl-0 border-bottom-xxl-0 border-end border-bottom pb-4 pb-xxl-0 "><span class="uil fs-5 lh-1 uil-envelope text-primary"></span>
	            <h1 class="fs-5 pt-3">0</h1>
	            <p class="fs-9 mb-0">Total Post</p>
	          </div>
	          <div class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-end-xxl-0 border-bottom-xxl-0 border-end-md border-bottom pb-4 pb-xxl-0"><span class="uil fs-5 lh-1 uil-envelope-upload text-info"></span>
	            <h1 class="fs-5 pt-3">0</h1>
	            <p class="fs-9 mb-0">Total Post On day</p>
	          </div>
	          <div class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-bottom-xxl-0 border-bottom border-end border-end-md-0 pb-4 pb-xxl-0 pt-4 pt-md-0"><span class="uil fs-5 lh-1 uil-envelopes text-primary"></span>
	            <h1 class="fs-5 pt-3">0</h1>
	            <p class="fs-9 mb-0">Total Post every 3 month</p>
	          </div>
	          <div class="col-6 col-md-4 col-xxl-2 text-center border-translucent border-start-xxl border-end-md border-end-xxl-0 border-bottom border-bottom-md-0 pb-4 pb-xxl-0 pt-4 pt-xxl-0"><span class="uil fs-5 lh-1 uil-envelope-open text-info"></span>
	            <h1 class="fs-5 pt-3">0</h1>
	            <p class="fs-9 mb-0">Total Post On year</p>
	          </div>
	        </div>
	    </div>
	</div> --}}

            <div class="card-flush card">
                <div class="card-header align-items-center gap-md-5 gap-2 py-5">
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

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        @if ($type == 7)
                            <a class="btn btn-primary" href="<?= route('admin::builder.posting_form.create') ?>?id_menu={{ $id_menu }}">
                                Create Form
                            </a>
                        @else
                            @if (empty($create_status->add) || $create_status->add == 1)
                                <a class="btn btn-primary" href="<?= route('admin::builder.posting.create') ?>?id_menu={{ $id_menu }}">
                                    Create Post
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if ($type != 7)
                        @livewire('admin::datatables.posting-datatable')
                    @else
                        @livewire('admin::datatables.posting-form-datatable')
                    @endif
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $('#datatablesSimple table').addClass('mt-5');
            $('#datatablesSimple tr th').addClass('fw-bold fs-6 text-gray-800');
        </script>
    @endif


@endsection
