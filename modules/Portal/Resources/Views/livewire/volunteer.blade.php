<div>
	<div class="row">
		@foreach($event as $key => $val)
			{{-- <?=dd($val)?> --}}
			<div class="col-md-6 col-xl-4">
				<div href="apps/projects/project.html" class="card border-hover-primary">
					<!--begin::Card header-->
					<div class="card-header border-0 pt-9">
						<!--begin::Card Title-->
						<div class="card-title m-0">
							<!--begin::Avatar-->
							<div class="symbol symbol-50px w-50px bg-light">
								<img src="{{asset('ysby_asset/media/svg/brand-logos/plurk.svg')}}" alt="image" class="p-3">
							</div>
							<!--end::Avatar-->
						</div>
						<!--end::Car Title-->
						<!--begin::Card toolbar-->
						<div class="card-toolbar">
							@if(isset(get_content_json($val)['id']['post1']) && strtotime(date('Y-m-d')) > strtotime(get_content_json($val)['id']['post1']))
								<span class="badge badge-light-info fw-bold me-auto px-4 py-3">Pendaftaran Ditutup</span>
							@else
								<span class="badge badge-light-primary fw-bold me-auto px-4 py-3">Pendaftaran Dibuka</span>
							@endif
						</div>
						<!--end::Card toolbar-->
					</div>
					<!--end:: Card header-->
					<!--begin:: Card body-->
					<div class="card-body p-9">
						<!--begin::Name-->
						<div class="fs-3 fw-bold text-gray-900">{{isset(get_content_json($val)['id']['title']) ? get_content_json($val)['id']['title'] : ''}}</div>
						<!--end::Name-->
						<!--begin::Description-->
						<p class="text-gray-500 fw-semibold fs-5 mt-1 mb-7">{{isset(get_content_json($val)['id']['post2']) ? get_content_json($val)['id']['post2'] : ''}}</p>
						<!--end::Description-->
						<!--begin::Info-->
						<div class="d-flex flex-wrap mb-5">
							<!--begin::Due-->
							<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
								<div class="fs-6 text-gray-800 fw-bold">{{isset(get_content_json($val)['id']['post0']) && tgl_indo(get_content_json($val)['id']['post0']) ? tgl_indo(get_content_json($val)['id']['post0']) : ''}}</div>
								<div class="fw-semibold text-gray-500">Tanggal Dimulai</div>
							</div>
							<!--end::Due-->
							<!--begin::Budget-->
							<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mb-3">
								<div class="fs-6 text-gray-800 fw-bold">{{isset(get_content_json($val)['id']['post1']) && tgl_indo(get_content_json($val)['id']['post1']) ? tgl_indo(get_content_json($val)['id']['post1']) : ''}}</div>
								<div class="fw-semibold text-gray-500">Tanggal Berakhir</div>
							</div>
							<!--end::Budget-->
						</div>

						<div class="row">
							<div class="col-md-3">
								@livewire('portal::refresh-action', ['event_id' => $val->id], key($val->id))
							</div>
						</div>
						<!--end::Info-->
						
						<!--begin::Users-->
						
						<!--end::Users-->
					</div>
					<!--end:: Card body-->
				</div>
			</div>
		@endforeach

		<div class="mt-4">
			{{$event->links()}}
		</div>
	</div>
</div>