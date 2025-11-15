<div>
	<div class="card mb-5 mb-xl-10">
		<!--begin::Card header-->
		<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
			<!--begin::Card title-->
			<div class="card-title m-0">
				<h3 class="fw-bold m-0">Profile Details</h3>
			</div>
			<!--end::Card title-->
		</div>
		<!--begin::Card header-->
		<!--begin::Content-->
		<div id="kt_account_settings_profile_details" class="collapse show">
			<!--begin::Form-->
			<div id="kt_account_profile_details_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
				<!--begin::Card body-->
				<div class="card-body border-top p-9">
					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8">
							<!--begin::Image input-->
							<div wire:ignore class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{get_image_photo($this->photo)}}')">
								<!--begin::Preview existing avatar-->
								<div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{get_image_photo($this->photo)}}')"></div>
								<!--end::Preview existing avatar-->
								<!--begin::Label-->
								<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1">
									<i class="mdi mdi-image-edit-outline fs-7">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<!--begin::Inputs-->
									<input type="file" class="file_change" name="avatar" accept=".png, .jpg, .jpeg">
									<input type="hidden" name="avatar_remove">
									<!--end::Inputs-->
								</label>
								<!--end::Label-->
								<!--begin::Cancel-->
								<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
									<i class="mdi mdi-image-remove-outline fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<!--end::Cancel-->
								<!--begin::Remove-->
								
								<!--end::Remove-->
							</div>
							<!--end::Image input-->
							<!--begin::Hint-->
							<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
							<!--end::Hint-->
						</div>
						<!--end::Col-->
					</div>
					<!--end::Input group-->
					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8">
							<!--begin::Row-->
							<div class="row">
								<!--begin::Col-->
								<div class="col-lg-6 fv-row fv-plugins-icon-container">
									<input type="text" name="fname" wire:model="first_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="First name" value="">
								<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
								<!--end::Col-->
								<!--begin::Col-->
								<div class="col-lg-6 fv-row fv-plugins-icon-container">
									<input type="text" name="lname" wire:model="last_name" class="form-control form-control-lg form-control-solid" placeholder="Last name" value="">
								<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
								<!--end::Col-->
							</div>
							<!--end::Row-->
						</div>
						<!--end::Col-->
					</div>
					<!--end::Input group-->

					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8 fv-row">
							<input type="text" name="website" disabled wire:model="email" class="form-control form-control-lg form-control-solid" placeholder="Email" value="">
						</div>
						<!--end::Col-->
					</div>
					
					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Company</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8 fv-row fv-plugins-icon-container">
							<input type="text" name="company" wire:model="company" class="form-control form-control-lg form-control-solid" placeholder="Company name" value="">
						<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
						<!--end::Col-->
					</div>
					<!--end::Input group-->
					<!--end::Input group-->
					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label fw-semibold fs-6">
							<span class="required">Contact Phone</span>
							<span class="ms-1" data-bs-toggle="tooltip" aria-label="Phone number must be active" data-bs-original-title="Phone number must be active" data-kt-initialized="1">
								<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
						</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8 fv-row fv-plugins-icon-container">
							<input type="tel" name="phone" class="form-control form-control-lg form-control-solid" wire:model="phone" placeholder="Phone number" value="">
						<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
						<!--end::Col-->
					</div>
					<!--end::Input group-->
					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label fw-semibold fs-6">Company Site</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8 fv-row">
							<input type="text" name="website" class="form-control form-control-lg form-control-solid" wire:model="site" placeholder="Company website" value="">
						</div>
						<!--end::Col-->
					</div>
					<!--end::Input group-->

					<!--begin::Input group-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label fw-semibold fs-6">Address</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8 fv-row">
							<textarea type="text" name="website" class="form-control form-control-lg form-control-solid" wire:model="address" placeholder="Address" value=""></textarea>
						</div>
						<!--end::Col-->
					</div>
					<!--end::Input group-->

					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Role</label>
						<!--end::Label-->
						<!--begin::Col-->
						<div class="col-lg-8 fv-row fv-plugins-icon-container">
							<!--begin::Options-->
							<div class="d-flex align-items-center mt-3">
								<!--begin::Option-->
								<label class="form-check form-check-custom form-check-inline form-check-solid me-5">
									<input class="form-check-input" wire:model="communication.donator" name="communication[]" type="checkbox" value="1">
									<span class="fw-semibold ps-2 fs-6">Donator</span>
								</label>
								<!--end::Option-->
								<!--begin::Option-->
								<label class="form-check form-check-custom form-check-inline form-check-solid">
									<input class="form-check-input" wire:model="communication.volunteer" name="communication[]" type="checkbox" value="2">
									<span class="fw-semibold ps-2 fs-6">Volunteer</span>
								</label>

								<label class="form-check form-check-custom form-check-inline form-check-solid">
									<input class="form-check-input" wire:model="communication.partnership" name="communication[]" type="checkbox" value="3">
									<span class="fw-semibold ps-2 fs-6">Partnership</span>
								</label>
								<!--end::Option-->
							</div>
							<!--end::Options-->
						<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
						<!--end::Col-->
					</div>
					
				</div>
				<!--end::Card body-->
				<!--begin::Actions-->
				<div class="card-footer d-flex justify-content-end py-6 px-9">
					<button wire:click="submitForm" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
				</div>
				<!--end::Actions-->
			<input type="hidden"></div>
			<!--end::Form-->
		</div>
		<!--end::Content-->
	</div>

	<script>
		$(document).on('change', '.file_change', function(event){
			@this.upload('photo', event.target.files[0])
		})
	</script>
</div>