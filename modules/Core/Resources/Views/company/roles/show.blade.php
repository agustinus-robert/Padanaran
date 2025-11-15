@extends('core::layouts.default')

@section('title', 'Peran | ')
@section('navtitle', 'Peran')

@section('content')
	<div class="d-flex align-items-center mb-4">
	    <a class="text-decoration-none" href="{{ request('next', route('core::company.roles.index')) }}"><i class="mdi mdi-arrow-left-circle-outline mdi-36px"></i></a>
	    <div class="ms-4">
	        <h2 class="mb-1">Lihat detail peran</h2>
	        <div class="text-secondary">Menampilkan informasi peran dan hak akses untuk diterapkan ke pengguna.</div>
	    </div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="card border-0">
				<div class="card-body"><i class="mdi mdi-pencil"></i> Ubah peran</div>
				<div class="card-body border-top">
					<form class="form-block" action="{{ route('core::company.roles.update', ['role' => $role->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
				        <div class="mb-3 required">
				            <label class="col-form-label text-md-right pt-0">Kode peran</label>
				            <input type="text" class="form-control{{ $errors->has('kd') ? ' is-invalid' : '' }}" name="kd" value="{{ old('kd', $role->kd) }}" required @cannot('update', $role) readonly disabled @endcannot>
				            @if ($errors->has('kd')) 
				                <span class="invalid-feedback"> {{ $errors->first('kd') }} </span>
				            @endif
				        </div>
				        <div class="mb-3 required">
				            <label class="col-form-label text-md-right pt-0">Nama peran</label>
				            <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $role->name) }}" required @cannot('update', $role) readonly disabled @endcannot>
				            @if ($errors->has('name')) 
				                <span class="invalid-feedback"> {{ $errors->first('name') }} </span>
				            @endif
				        </div>
				        <div class="mb-4">
				        	<div class="mb-1">Dibuat pada</div>
				        	<strong>{{ $role->created_at->isoFormat('LLLL') }}</strong>
				        </div>
				        <div class="mb-0">
					        @can('update', $role)
					            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
					        @endcan
					        <a class="btn btn-soft-light text-dark" href="{{ request('next', route('core::company.roles.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
					    </div>
				    </form>
				</div>
			</div>
            <div class="card card-body py-4 border-0 d-flex flex-row justify-content-between align-items-center">
                <div>
                    <div class="display-4">{{ $role->users_count }}</div>
                    <div class="small fw-bold text-secondary text-uppercase">Jumlah anggota</div>
                </div>
                <div><i class="mdi mdi-shield-star-outline mdi-48px text-light"></i></div>
            </div>
		</div>
		<div class="col-sm-8">
			<div class="card border-0">
				<div class="card-body"><i class="mdi mdi-star-box-outline"></i> Hak akses</div>
				<div class="card-body border-top">
					<form class="form-block" action="{{ route('core::company.roles.permissions', ['role' => $role->id, 'next' => request('next')]) }}" method="POST"> @csrf @method('PUT')
				        <div class="mb-3">
				            @if ($errors->has('permissions.0')) 
				                <div>
				                    <small class="text-danger"> {{ $errors->first('permissions.0') }} </small>
				                </div>
				            @endif
				            @foreach ($permissions->groupBy(['module', 'model']) as $module => $models)
				            	<div @if(!$loop->last) class="mb-2" @endif>
						            <div class="fw-bold">{{ $module }}</div>
					            	@foreach ($models as $model => $_permissions)
						            	<div class="row @if(!$loop->last) mb-2 @endif">
						            		<div class="col-md-4">
								            	<div>{{ $model }}</div>
						            		</div>
						            		<div class="col-md-8">
									            @foreach ($_permissions->chunk(2) as $chunk)
									                <div class="row">
									                    @foreach ($chunk as $permission)
									                        <div class="col-md-6">
									                            <div class="form-check">
									                                <input class="form-check-input autocheck" type="checkbox" id="permissions-{{ $permission->id }}" value="{{ $permission->id }}" name="permissions[]" autocheck="{{ $permission->group }}" @checked($role->hasAnyPermissionsTo([$permission->key])) @cannot('update', $role) disabled @endcannot>
									                                <label class="form-check-label" for="permissions-{{ $permission->id }}">{{ $permission->name ?: $permission->key }}</label>
									                            </div>
									                        </div>
									                    @endforeach
									                </div>
									            @endforeach
						            		</div>
						            	</div>
					            	@endforeach
				            	</div>
							@endforeach
				        </div>
					    <div class="mb-0">
					        @can('update', $role)
					            <button class="btn btn-soft-danger" type="submit"><i class="mdi mdi-check"></i> Simpan</button>
					        @endcan
					        <a class="btn btn-soft-light text-dark" href="{{ request('next', route('core::company.roles.index')) }}"><i class="mdi mdi-arrow-left"></i> Kembali</a>
					    </div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection