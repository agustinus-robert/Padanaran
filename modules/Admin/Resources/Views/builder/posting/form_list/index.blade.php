@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Posting Form List')

@section('navtitle', 'Posting Form List')

@section('content')
	<div>
		<main>
		    <div class="container-fluid px-4">
			    <h1 class="mt-4">Dashboard</h1>
			    
			    <ol class="breadcrumb mb-4">
			        <li class="breadcrumb-item active"><a href="">Posting Form List</a></li>
			    </ol>
			   
			@if (Session::has('msg'))
			    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1500)" x-show="show">
			        <div class="alert alert-success">
			            {{ Session::get('msg') }}
			        </div>
			    </div>
			@endif

			@if (Session::has('msg-gagal'))
			    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1500)" x-show="show">
			        <div class="alert alert-danger">
			            {{ Session::get('msg-gagal') }}
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
			</div>

			    <div class="card shadow-none border my-4">
			        <div class="card-header p-4 border-bottom bg-body">
			            <div class="row g-3 justify-content-between align-items-center">
			                <div class="col-md-10 p-2">
			                <h4 class="text-body mb-0"><i class="fas fa-table me-1"></i> List of Posted Form List data</h4>
			                </div>

			                <div class="col col-md-auto">
			                	
			                </div>
			            </div>
			        </div>
			        <div class="card-body">  
			            @livewire('admin::datatables.posting-form-list-datatable')
			        </div>
			    </div>
			</div>
		</main>
	</div>
@endsection