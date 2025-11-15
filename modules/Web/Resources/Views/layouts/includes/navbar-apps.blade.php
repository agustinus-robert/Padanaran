@push('script')
    <div class="modal page-fade" id="navbar-apps" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <div class="modal-title">Pintasan</div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        {{-- <div class="col-6 col-sm-4 text-center px-2 pb-3">
						<a class="btn btn-light btn-block" href="{{ route('web::index') }}">
							<i class="mdi mdi-home mdi-36px"></i> <br>
							<small class="font-weight-bold">Utama</small>
						</a>
					</div> --}}
                        <div class="col-6 col-sm-4 px-2 pb-3 text-center">
                            <a class="btn btn-light btn-block" href="{{ route('account::index') }}">
                                <i class="mdi mdi-account-circle mdi-36px"></i> <br>
                                <small class="font-weight-bold">Akun saya</small>
                            </a>
                        </div>
                        {{-- @if (auth()->user()->isTeacher()) --}}
                        <div class="col-6 col-sm-4 px-2 pb-3 text-center">
                            <a class="btn btn-light btn-block" href="{{ route('teacher::index') }}">
                                <i class="mdi mdi-account-badge mdi-36px"></i> <br>
                                <small class="font-weight-bold">Ruang Guru</small>
                            </a>
                        </div>
                        {{-- @endif --}}
                        {{-- @if (auth()->user()->isCounselor()) --}}
                        <div class="col-6 col-sm-4 px-2 pb-3 text-center">
                            <a class="btn btn-light btn-block" href="{{ route('counseling::index') }}">
                                <i class="mdi mdi-account-badge-horizontal mdi-36px"></i> <br>
                                <small class="font-weight-bold">Ruang BK</small>
                            </a>
                        </div>
                        {{-- @endif --}}
                        {{-- @if (auth()->user()->isStudent()) --}}
                        <div class="col-6 col-sm-4 px-2 pb-3 text-center">
                            <a class="btn btn-light btn-block" href="{{ route('academic::index') }}">
                                <i class="mdi mdi-school mdi-36px"></i> <br>
                                <small class="font-weight-bold">Akademik</small>
                            </a>
                        </div>
                        {{-- @endif --}}
                        <div class="col-6 col-sm-4 px-2 pb-3 text-center">
                            <a class="btn btn-light btn-block" href="https://perpus.masunanpandanaran.com">
                                <i class="mdi mdi-library mdi-36px"></i> <br>
                                <small class="font-weight-bold">Perpus</small>
                            </a>
                        </div>
                        <div class="col-6 col-sm-4 px-2 pb-3 text-center">
                            <a class="btn btn-light btn-block disabled" href="#">
                                <i class="mdi mdi-help-box mdi-36px"></i> <br>
                                <small class="font-weight-bold">Bantuan</small>
                            </a>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 text-center">{{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </div>
@endpush
