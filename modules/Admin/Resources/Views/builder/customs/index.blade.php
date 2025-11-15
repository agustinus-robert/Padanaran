@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Category')

@section('navtitle', 'Category')

@section('content')

    @php
        $arr = [
            'global' => false,
            'column' => $column,
            'ajax' => [
                'url' => route('admin::custom.custom.datatable-donation'),
                'script' => 'function(d) { ajaxDataFunction(d); }',
            ],
            'parameters' => [
                'drawCallback' => 'function() { ajaxParam(); }',
            ],
        ];
    @endphp

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('admin::builder.categoryzation')
    @else
        <div class="toolbar mb-lg-7 mb-5" id="kt_toolbar">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column me-3">
                <!--begin::Title-->
                <h1 class="d-flex fw-bold fs-3 my-1 text-gray-900">Customs</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-1 text-gray-600">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">
                        <a href="index.html" class="text-hover-primary text-gray-600">Dashboard</a>
                    </li>

                    <li class="breadcrumb-item text-gray-600">Customs</li>
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

            {{-- 	<div class="row mb-3">
			<div class="col-md-3">
				<input type="text" id="filterTitle" placeholder="Filter by Title">
			</div>

			<div class="col-md-12 mt-3">

				<button id="applyFilter">Apply Filter</button>

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
                            List of Customs
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    @livewire('admin::datatables.custom-datatable', ['arr' => $arr])
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#applyFilter').on('click', function() {
                    if ($('#filterTitle').val() == '') {
                        alert('Harap masukkan strings')
                    } else {
                        $('#dataTableBuilder').DataTable().ajax.reload();
                    }
                });


            })

            function ajaxDataFunction(d) {
                //d.filterTitle = $("#filterTitle").val();  // Sesuaikan parameter yang ingin ditambahkan
            }

            function ajaxParam() {
                var tableApi = $.fn.dataTable.Api('#dataTableBuilder');
            }
        </script>

    @endif

@endsection
