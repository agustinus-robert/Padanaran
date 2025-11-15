<div>
    <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Form Posting</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>

                <li class="breadcrumb-item text-gray-600">Form Posting</li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>

    <div class="card shadow mb-4">
    	<div>
        	<div class="card-header p-4 border-bottom bg-body">
        		 <div class="form-group col-12 mb-3">
				    <label class="form-label" for="exampleFormControlInput1"><b>Judul</b></label>
				    <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="title">
				    <label class="form-label" for="exampleFormControlInput1">@error('title') <span class="text-danger">{{ $message }}</span> @enderror</label>
				  </div>
        	</div>

			<div class="card-body">
				<div class="row">
					{{-- <div class="col-md-1">
						<button class="btn btn-info btn-sm mb-3" wire:click="add"><i class="fa fa-plus" aria-hidden="true"></i></button>
					</div> --}}

		{{-- 			<div class="col-md-1">
						<button class="btn btn-info btn-sm mb-3" wire:click="check">Cek</button>
					</div> --}}
				</div>


				@if(count($form) > 0)	

				  	@foreach($form as $key => $val)
				  		
				  		@if($errors->has('arr.'.$key.'.label') || $errors->has('arr.'.$key.'.type'))
				  			{{-- <div x-data="{show: true}" x-init="setTimeout(() => show = false, 6500)" x-show="show"> --}}
    							{{-- <div class="alert alert-danger"> --}}
							  		<div class="row mt-3">
							  			<div class="alert-danger2" role="alert">
							  				<ul class="mt-3">
							  					@if($errors->has('arr.'.$key.'.label'))
							  					<li>{{ $errors->has('arr.'.$key.'.label') ? $errors->first('arr.'.$key.'.label') : '' }}</li>
							  					@endif

							  					@if($errors->has('arr.'.$key.'.type'))
							  					<li>{{ $errors->has('arr.'.$key.'.type') ? $errors->first('arr.'.$key.'.type') : '' }}</li>
							  					@endif
							  				</ul>
							  			</div> 
							  		</div>
							  	{{-- </div> --}}
						  	{{-- </div> --}}
				  		@endif

				  		{!! $val !!}

				  		@if(isset($arr[$key]))
				  			
				  			<div class="row" style="background-color:#e6e6e6;">
					  			<fieldset class="p-4">

									<legend><i>Preview:</i></legend>

						  			<div class="form-group">
						  				@if(isset($arr[$key]['label']))
						  					<label>{{$arr[$key]['label']}}</label>
						  				@endif

						  				@if(isset($arr[$key]['type']))
							  				@if($arr[$key]['type'] == 1)
							  					<input type="text" class="form-control" placeholder="Jawaban" disabled>
							  				@endif
							  			@endif
						  			</div>
						  		</fieldset>
					  		</div>
				  		@endif
				  	@endforeach
				@endif
				
				<div class="row text-center bg-body mt-4">
					<div class="col-md-6 mx-auto p-2">
						<button class="btn btn-sm opacity-50" wire:click="add"><span class="mdi mdi-plus-box-outline mdi-48px"></span><br />Click Untuk menambahkan Inputan</button>
					</div>
				</div>
			
			</div>

			<div class="card-footer">
				<button wire:click="save" class="btn btn-primary btn-sm mb-3"><i class="fa fa-save"></i></button>
			</div>
		</div>
</div>
</div>