@extends('layouts.default')

@section('title', 'Welcome to ')

@section('bodyclass', 'min-vh-100 d-flex align-items-center bg-light')

@php($is_signature = request('type') == 'signature')

@section('main')
    <div class="container">
    	<div class="row justify-content-center">
    		<div class="col-lg-10 col-xl-8">
    			<div class="card card-body border-0 p-5 mb-0 rounded-lg">
			    	<div class="text-center">
				        <div class="mb-4 d-flex justify-content-center">
				        	<div class="animate__animated animate__pulse animate__infinite">
				            	<i class="mdi mdi-file-{{ $model ? 'check' : 'cancel' }}-outline {{ $model ? 'text-success' : 'text-danger' }}" style="font-size: 124px;"></i>
				        	</div>
				        </div>
				        <div class="h1 fw-bold mb-2">{{ $is_signature ? 'Tandatangan' : 'Dokumen' }} tersebut {{ $model ? 'adalah' : 'tidak' }} valid</div>
				        <div class="text-muted mb-5">
				        	@if($model)
				        		Berikut detail informasi verifikasi dan validasi dari {{ $is_signature ? 'tandatangan' : 'dokumen' }} tersebut!
				        	@else
				        		Maaf, kami tidak dapat menemukan dokumen yang kamu maksud, pastiin kode QR nya benar!
				        	@endif
				        </div>
			    	</div>
			    	@if($model)
				        <div class="row justify-content-center">
				        	<div class="col-xl-10">
						        <table class="table align-middle mb-5">
						        	<tbody>
						        		<tr>
						        			<td class="py-3">Nama dokumen</td> <td style="width:10px;">:</td>
						        			<td class="fw-bold">{{ $model->label ?: $model->document->label }}</td>
						        		</tr>
						        		@if($model->user)
						        			<tr>
							        			<td class="py-3">Ditandatangani oleh</td> <td style="width:10px;">:</td>
							        			<td class="fw-bold">{{ $model->user->name }}</td>
							        		</tr>
						        		@endif
						        		<tr>
						        			<td class="py-3">Diregistrasikan pada</td> <td style="width:10px;">:</td>
						        			<td class="fw-bold">{{ $model->created_at->isoFormat('LLLL') }}</td>
						        		</tr>
						        	</tbody>
						        </table>
				        	</div>
				        </div>
				    @endif
				    <div class="text-center">
				    	<button class="btn btn-soft-dark text-dark rounded-pill px-3" onclick="javascript:window.close();"><i class="mdi mdi-close"></i> Tutup</button>
				    </div>
    			</div>
    		</div>
    	</div>
    </div>
@endsection