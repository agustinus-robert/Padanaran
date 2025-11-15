@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Posting Image')

@section('navtitle', 'Posting Image')

@section('content')

@if(str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
	@livewire('admin::builder.posting-image')
@else
	<div>
		<main>
		    <div class="container-fluid px-4">
			    <h1 class="mt-4">Dashboard</h1>
			    
			    <ol class="breadcrumb mb-4">
			        <li class="breadcrumb-item active"><a href="">Posting Image</a></li>
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

			    <div class="card shadow-none border my-4">
			        <div class="card-header p-4 border-bottom bg-body">
			            <div class="row g-3 justify-content-between align-items-center">
			                <div class="col-md-10 p-2">
			                <h4 class="text-body mb-0"><i class="fas fa-table me-1"></i> List of Posted data</h4>
			                </div>

			                <div class="col col-md-auto">
			                    <a class="btn btn-sm btn-phoenix-primary ms-2" href="<?=route('admin::builder.posting_image.create')?>?id_menu={{$id_menu}}&post_id={{$post_id}}">
			                        <i class="fas fa-keyboard" aria-hidden="true"></i> <span class="text">Create Post</span>
			                    </a>
			                </div>
			            </div>
			        </div>
			        <div class="card-body">          
			            @livewire('admin::datatables.posting-image-datatable') 
			        </div>
			    </div>
			</div>
		</main>
	</div>
@endif


@endsection
