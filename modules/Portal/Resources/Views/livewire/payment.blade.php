<div>
	<div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column me-3">
			<!--begin::Title-->
			<h1 class="d-flex text-gray-900 fw-bold my-1 fs-3">Invoice Nomor. {{$invoices}}</h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7 my-1">
				<!--begin::Item-->
				<li class="breadcrumb-item text-gray-600">
					<a href="index.html" class="text-gray-600 text-hover-primary">Dashboard</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-gray-600">Invoice Donation</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-gray-600">View Invoices</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-gray-500">Invoice Nomor. {{$invoices}}</li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
		<!--begin::Actions-->
		<div class="d-flex align-items-center py-2 py-md-1">
			<!--begin::Wrapper-->
			<div class="me-3">
				<!--begin::Menu-->
				{{-- <a href="#" class="btn btn-light fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
				<i class="ki-duotone ki-filter fs-5 text-gray-500 me-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>Filter</a> --}}
				<!--begin::Menu 1-->
				<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_66b9a54ce84e8">
					<!--begin::Header-->
					<div class="px-7 py-5">
						<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
					</div>
					<!--end::Header-->
					<!--begin::Menu separator-->
					<div class="separator border-gray-200"></div>
					<!--end::Menu separator-->
					<!--begin::Form-->
					<div class="px-7 py-5">
						<!--begin::Input group-->
						<div class="mb-10">
							<!--begin::Label-->
							<label class="form-label fw-semibold">Status:</label>
							<!--end::Label-->
							<!--begin::Input-->
							<div>
								<select class="form-select form-select-solid select2-hidden-accessible" multiple="" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_menu_66b9a54ce84e8" data-allow-clear="true" data-select2-id="select2-data-7-78xl" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
									<option></option>
									<option value="1">Approved</option>
									<option value="2">Pending</option>
									<option value="2">In Process</option>
									<option value="2">Rejected</option>
								</select><span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-8-grmz" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple form-select form-select-solid" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false"><ul class="select2-selection__rendered" id="select2-pnkc-container"></ul><span class="select2-search select2-search--inline"><textarea class="select2-search__field" type="search" tabindex="0" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" autocomplete="off" aria-label="Search" aria-describedby="select2-pnkc-container" placeholder="Select option" style="width: 100%;"></textarea></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
							</div>
							<!--end::Input-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="mb-10">
							<!--begin::Label-->
							<label class="form-label fw-semibold">Member Type:</label>
							<!--end::Label-->
							<!--begin::Options-->
							<div class="d-flex">
								<!--begin::Options-->
								<label class="form-check form-check-sm form-check-custom form-check-solid me-5">
									<input class="form-check-input" type="checkbox" value="1">
									<span class="form-check-label">Author</span>
								</label>
								<!--end::Options-->
								<!--begin::Options-->
								<label class="form-check form-check-sm form-check-custom form-check-solid">
									<input class="form-check-input" type="checkbox" value="2" checked="checked">
									<span class="form-check-label">Customer</span>
								</label>
								<!--end::Options-->
							</div>
							<!--end::Options-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="mb-10">
							<!--begin::Label-->
							<label class="form-label fw-semibold">Notifications:</label>
							<!--end::Label-->
							<!--begin::Switch-->
							<div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked">
								<label class="form-check-label">Enabled</label>
							</div>
							<!--end::Switch-->
						</div>
						<!--end::Input group-->
						<!--begin::Actions-->
{{-- 						<div class="d-flex justify-content-end">
							<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
							<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
						</div> --}}
						<!--end::Actions-->
					</div>
					<!--end::Form-->
				</div>
				<!--end::Menu 1-->
				<!--end::Menu-->
			</div>
			<!--end::Wrapper-->
			<!--begin::Button-->
			{{-- <a href="#" class="btn btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a> --}}
			<!--end::Button-->
		</div>
		<!--end::Actions-->
	</div>

	<div class="content flex-column-fluid" id="kt_content">
		<!--begin::Invoice 2 main-->
		<div class="card">
			<!--begin::Body-->
			<div class="card-body p-lg-20">
				<!--begin::Layout-->
				<div class="d-flex flex-column flex-xl-row">
					<!--begin::Content-->
					<div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
						<!--begin::Invoice 2 content-->
						<div class="mt-n1">
							<!--begin::Top-->
							<div class="d-flex flex-stack pb-10">
								<!--begin::Logo-->
								<a href="#">
									<img alt="Logo" src="{{asset('icons/dove.png')}}">
								</a>
								<!--end::Logo-->
								<!--begin::Action-->
								@if(isset($notif->transaction_status) && $notif->transaction_status == 'settlement')
									<button disabled class="btn btn-sm btn-secondary">Pay Now</a>
								@else
									<button id="pay-button" class="btn btn-sm btn-success">Pay Now</a>
								@endif
								<!--end::Action-->
							</div>
							<!--end::Top-->
							<!--begin::Wrapper-->
							<div class="m-0">
								<!--begin::Label-->
								<div class="fw-bold fs-3 text-gray-800 mb-8">Invoice #{{$invoices}}</div>
								<!--end::Label-->
								<!--begin::Row-->
								<div class="row g-5 mb-11">
									<!--end::Col-->
									<div class="col-sm-6">
										<!--end::Label-->
										<div class="fw-semibold fs-7 text-gray-600 mb-1">Dibuat Pada:</div>
										<!--end::Label-->
										<!--end::Col-->
										<div class="fw-bold fs-6 text-gray-800">{{tgl_indo(date('Y-m-d', strtotime($date)))}}</div>
										<!--end::Col-->
									</div>
									
								</div>
								<!--end::Row-->
								<!--begin::Row-->
								<div class="row g-5 mb-12">
									<!--end::Col-->
									<div class="col-sm-6">
										<!--end::Label-->
										<div class="fw-semibold fs-7 text-gray-600 mb-1">Identitas Pembayaran</div>
										<!--end::Label-->
										<!--end::Text-->
										<div class="fw-bold fs-6 text-gray-800">{{$first_name}} {{$last_name}}</div>
										<!--end::Text-->
										<!--end::Description-->
										<div class="fw-semibold fs-7 text-gray-600">{{$address}}</div>
										<!--end::Description-->
									</div>
									<!--end::Col-->
									<!--end::Col-->
									<div class="col-sm-6">
										<!--end::Label-->
										<div class="fw-semibold fs-7 text-gray-600 mb-1">Kepada</div>
										<!--end::Label-->
										<!--end::Text-->
										<div class="fw-bold fs-6 text-gray-800">Yayasan Suara Bakti Surakarta</div>
										<!--end::Text-->
										<!--end::Description-->
										<div class="fw-semibold fs-7 text-gray-600"> Jl. Damai, Wonorejo, Sariharjo, Ngaglik, Sleman, Yogyakarta (YSBY)</div>
										<!--end::Description-->
									</div>
									<!--end::Col-->
								</div>
								<!--end::Row-->
								<!--begin::Content-->
								<div class="col-md-6 mx-auto p-4">
									
									<!--begin::Container-->
									<div class="justify-content-center">
										@if(isset($notif->transaction_status) && $notif->transaction_status == 'settlement')
										<div class="alert alert-success text-center" role="alert">
											<b>Sudah dibayarkan</b>
										</div>
										@else
										<div class="alert alert-primary text-center" role="alert">
											<b>Jumlah yang harus dibayarkan</b> <br />
											<div class="text-center">
												@php $payed = ($pays + 4000) @endphp
												 Rp. {{number_format($payed, 2, ",", ".")}}
											</div>
										</div>
										@endif
									</div>
									<!--end::Container-->
								</div>
								<!--end::Content-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Invoice 2 content-->
					</div>
					<!--end::Content-->
					<!--begin::Sidebar-->
					<div class="m-0">
						<!--begin::Invoice 2 sidebar-->
						<div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
							<!--begin::Labels-->
							<div class="mb-8">
								@if(isset($notif->transaction_status) && $notif->transaction_status == 'settlement')
									<span class="badge badge-light-success">Approved</span>
								@else
									<span class="badge badge-light-warning">Pending</span>
								@endif
								{{-- <span class="badge badge-light-success me-2">Approved</span> --}}
								{{-- <span class="badge badge-light-warning">Pending Payment</span> --}}
							</div>
							<!--end::Labels-->
							<!--begin::Title-->
							<h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">PAYMENT DETAILS</h6>
							<!--end::Title-->
							<!--begin::Item-->
							<div class="mb-6">
								<div class="fw-semibold text-gray-600 fs-7">Tipe Pembayaran:</div>
								<div class="fw-bold text-gray-800 fs-6">{{ isset($notif->id) ? 'Transfer Bank' : '-' }}</div>
							</div>
							<!--end::Item-->

							<!--begin::Item-->
							<div class="mb-6">
								<div class="fw-semibold text-gray-600 fs-7">Bank:</div>
								<div class="fw-bold text-gray-800 fs-6">{{ isset($notif->id) ? getMidtransNotif($notif['notify_detailed_info'])['va_numbers'][0]['bank'] : '-' }}</div>
							</div>	
							<!--end::Item-->
							<!--begin::Item-->
							<div class="mb-15">
								<div class="fw-semibold text-gray-600 fs-7">Dibayarkan Pada:</div>
								<div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">{{ isset($notif->transaction_status) && $notif->transaction_status == 'settlement' ? getMidtransNotif($notif['notify_detailed_info'])['transaction_time'] : '-' }}</div>
							</div>
							<!--end::Item-->
							<div class="mb-15">
								<div class="fw-semibold text-gray-600 fs-7">Penambahan biaya transfer:</div>
								<div class="fw-bold fs-6 text-gray-800 d-flex align-items-center"> {{ number_format(4000, 2, ",", ".") }}</div>
							</div>
							<!--begin::Title-->
							<h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">INFO PEMBAYARAN</h6>
							<!--end::Title-->
							<!--begin::Item-->
							<div class="mb-6">
								<div class="fw-semibold text-gray-600 fs-7">Order id# {{ isset($notif->id) ?  getMidtransNotif($notif['notify_detailed_info'])['order_id'] : '-' }}</div>
								<div class="fw-bold fs-6 text-gray-800"></div>
							</div>
							<!--end::Item-->
						</div>
						<!--end::Invoice 2 sidebar-->
					</div>
					<!--end::Sidebar-->
				</div>
				<!--end::Layout-->
			</div>
			<!--end::Body-->
		</div>
		<!--end::Invoice 2 main-->
	</div>
	@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{env('MIDTRANS_CLIENT_KEY')}}"></script>
    <script type="text/javascript">
      
        document.getElementById('pay-button').onclick = function(){
                // var money = $('#valueMoney').val();
                // if(money){
                    Livewire.dispatch('call-first');
                    window.addEventListener('contentChanged', event => {
                            snap.pay(event.detail[0].snapToken, {
                      // Optional
                      onSuccess: function(result){
                            
                        Livewire.dispatch('payment', {status: 'success', paymentid: event.detail[0].pay_id, amount: result.gross_amount, order_id: result.order_id, payment_type: result.payment_type, transaction_status: result.transaction_status, transaction_id: result.transaction_id, detail: result})
                            //Livewire.dispatch('payment-success', {refreshPosts: true})
                        
                      },
                      // Optional
                      onPending: function(result){
                        Livewire.dispatch('payment', {status: 'pending', paymentid: event.detail[0].pay_id, amount: result.gross_amount, order_id: result.order_id, payment_type: result.payment_type, transaction_status: result.transaction_status, transaction_id: result.transaction_id, detail: result})
                      },
                      // Optional
                      onError: function(result){
                        Livewire.dispatch('payment', {status: 'error', paymentid: event.detail[0].pay_id, amount: result.gross_amount, order_id: result.order_id, payment_type: result.payment_type, transaction_status: result.transaction_status, transaction_id: result.transaction_id, detail: result})
                      }
                    });
                })
            // } else {
            //     Swal.fire({
            //       icon: "error",
            //       title: "Pembayaran Gagal!",
            //       text: "Masukkan nilai nominal donasi untuk melanjutkan pembayaran",
            //     });
            // }
        };  
      
    </script>
    @endpush
</div>	