<div>
	<div class="d-flex flex-column flex-column-fluid">
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<div class="card mb-6 mb-xl-9">
				<div class="card-header mt-5">
					<!--begin::Card title-->
					<div class="card-title flex-column">
						<h3 class="fw-bold mb-1">{{$action}} Inquiry</h3>
					</div>
				</div>
				<form wire:submit="save">
					<div class="card-body pt-9 pb-0">
						<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll">
							<div class="fv-row mb-7">
								<label class="fw-semibold fs-6 mb-2">Nomor Referensi (Generate Otomatis)</label>
								<input type="text" wire:model="form.reference" name="user_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Reference Number" disabled />
								@error('form.reference')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
							</div>

							<div class="fv-row mb-7">	
								<label class="required fw-semibold fs-6 mb-2">Tipe Pekerjaan</label>
						
								<select class="form-select" wire:model="form.type_job">
									<option value="">Pilih Tipe Pekerjaan</option>
									<option value="1">Tender</option>
									<option value="2">Non Tender</option>
									<option value="3">Swakelola</option>
								</select>
								@error('form.type_job')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
							</div>

							<div class="fv-row mb-7">
								<label class="required fw-semibold fs-6 mb-2">Judul Pekerjaan</label>
								<input type="text" wire:model="form.title" name="user_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Judul Pekerjaan" value="" />
								@error('form.title')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
							</div>


							<div class="fv-row mb-7">
								<label class="required fw-semibold fs-6 mb-2">Client Status</label>
								<select wire:model="form.client_status" wire:change="checkClientStatus" class="form-select">
									<option value="">Pilih Client Status</option>
									<option value="1">Baru</option>
									<option value="2">Sudah Ada</option>
								</select>
								@error('form.client_status')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
							</div>

							<!-- Input tambahan yang muncul jika Client Status == 2 -->
							@if($showClientSelect == 1)
							    <div class="fv-row mb-7">
							        <label class="required fw-semibold fs-6 mb-2">Isi Client</label>
							        <input type="text" class="form-control" wire.model="form.client_id" />
							        @error('form.client_id')
							            <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
							        @enderror
							    </div>
							@elseif($showClientSelect == 2)
								<div x-data="{
							            init() {
							                this.initSelect2();
							            },
							            initSelect2() {
							                let select = $('#cntid');
							                select.select2();
							                select.on('change', (event) => {
							                    @this.set('form.client_id', event.target.value);
							                });
							            }
							        }"
							        x-init="init()"
							        wire:ignore.self
							        class="fv-row mb-7"
							    >
								    <label class="required fw-semibold fs-6 mb-2">Pilih Client</label>
								    <select class="form-select" id="cntid">
								        <option value="">Pilih Client</option>
								        @foreach($client as $key => $val)
								            <option value="{{ $val->id }}" {{ $form['client_id'] == $val->id ? 'selected' : '' }}>{{ $val->company }}</option>
								        @endforeach
								    </select>
								    @error('form.client_id')
								        <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
								    @enderror
								</div>
							@endif

							<div class="fv-row mb-7">
								<label class="required fw-semibold fs-6 mb-2">Tanggal Inquiry</label>
								<input type="date" wire:model="form.inquiry_date" class="form-control" />
								@error('form.inquiry_date')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
							</div>

							<div class="fv-row mb-7">
								<label class="fw-semibold fs-6 mb-2">Upload Dokumen Inquiry</label>
								<input type="file" wire:model="form.document" class="form-control"/>
								@if(isset($form['document']) && !empty($form['document']))
									<div class="mt-3">
										<i class="bi bi-arrow-down-circle"></i> <a href="{{asset($form['document'])}}">Download File</a>
									</div>
								@endif

								@error('form.document')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
							</div>

							<div class="fv-row mb-7" wire:ignore>
								<label class="required fw-semibold fs-6 mb-2">Project Manager</label>
								<select class="form-select" id="pmid">
									<option value="">Pilih Project Manager</option>
									@foreach($empl as $key => $val)
									<option {{ $form['pm_id'] == $val->id ? 'selected' : '' }} value="{{$val->id}}">{{$val->name}}</option>
									@endforeach
								</select>
								@error('form.pm_id')
                                    <span class="text-danger mt-2"><i class="bi bi-exclamation-triangle text-danger"></i> {{ $message }}</span>
                                @enderror
							</div>
						</div>
					</div>

					<div class="card-footer text-center">
						<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
							<span class="indicator-label">Submit</span>
						</button>
					</div>
				</form>
			</div>
		<div>
	</div>

	@push('scripts')
		<script type="text/javascript">
			document.addEventListener('livewire:load', function () {
			    Livewire.hook('message.processed', (message, component) => {
			        // Pastikan elemen select ada sebelum menginisialisasi Select2
			        if (document.querySelector('#cntid')) {
			            $('#cntid').select2(); // Inisialisasi Select2

			            // Hubungkan perubahan Select2 ke Livewire
			            $('#cntid').off('change').on('change', function (e) { // Gunakan off untuk menghindari penggandaan event
			                var data = $(this).val();
			                @this.set('form.client_id', data);
			            });
			        }
			    });
			});


			document.addEventListener('DOMContentLoaded', function () {
			    // Inisialisasi Select2 pertama kali
			    $('#pmid').select2();
			   
			    Livewire.hook('message.processed', (message, component) => {
			        $('#pmid').select2();
			    });


			    // Menghubungkan perubahan Select2 ke Livewire
			    $('#pmid').on('change', function (e) {
			        var data = $(this).val();
			        @this.set('form.pm_id', data);
			    });

			});
	</script>
	@endpush	
</div>