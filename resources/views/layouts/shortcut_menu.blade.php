<div class="dropdown d-none d-lg-inline-block ms-1">
    <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bx bx-customize"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <div class="px-lg-2">
            <div class="row g-0">
                @if(isset(auth()->user()->roles()->first()->id) && auth()->user()->roles()->first()->id == 1)
                    @can('portal::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('portal::dashboard-msdm.index') }}">
                                <i class="bx bxs-user-rectangle" style='font-size:30px;'></i>
                                <span>Dashboard</span>
                            </a>
                        </div> 
                    @endcan

                    @can('core::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('core::dashboard') }}">
                                <i class="bx bxs-wrench" style='font-size:30px;'></i>
                                <span>Setting</span>
                            </a>
                        </div>
                    @endcan

                    @can('hrms::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('hrms::dashboard') }}">
                                <i class="mdi mdi-account-group" style='font-size:30px;'></i>
                                <span>HRMS</span>
                            </a>
                        </div>
                    @endcan
                @else
                    @can('core::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('core::dashboard') }}">
                                <i class="bx bxs-wrench" style='font-size:30px;'></i>
                                <span>Setting</span>
                            </a>
                        </div>
                    @endcan

                    @can('administration::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('administration::dashboard') }}">
                                <i class="bx bxs-buildings" style='font-size:30px;'></i>
                                <span>Tata Usaha</span>
                            </a>
                        </div>
                    @endcan

                    @can('boarding::access')
                    <div class="col-md-4">
                        <a class="dropdown-icon-item" href="{{ route('boarding::dashboard') }}">
                            <i class="bx bx-home-circle" style='font-size:30px;'></i>
                            <span>Pondok</span>
                        </a>
                    </div>
                    @endcan
            
                    @can('teacher::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('teacher::home') }}">
                                <i class="bx bxs-book-content" style='font-size:30px;'></i>
                                <span>Guru</span>
                            </a>
                        </div>
                    @endcan

                    @can('academic::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('academic::home') }}">
                                <i class="bx bxs-chalkboard" style='font-size:30px;'></i>
                                <span>Akademik</span>
                            </a>
                        </div>
                    @endcan

                    @can('counseling::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('counseling::home') }}">
                                <i class="bx bx-user-voice" style='font-size:30px;'></i>
                                <span>Konseling</span>
                            </a>
                        </div>
                    @endcan

                    @can('hrms::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('hrms::dashboard') }}">
                                <i class="mdi mdi-account-group" style='font-size:30px;'></i>
                                <span>HRMS</span>
                            </a>
                        </div>
                    @endcan

                    @can('is-casier')
                        {{-- <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('portal::dashboard.index') }}">
                                <i class="bx bx-cart-alt" style='font-size:30px;'></i>
                                <span>Dashboard POS</span>
                            </a>
                        </div>  --}}
                    @endcan

                    @can('finance::access')
                    <div class="col-md-4">
                        <a class="dropdown-icon-item" href="{{ route('finance::dashboard') }}">
                            <i class="bx bx-money" style='font-size:30px;'></i>
                            <span>Finance</span>
                        </a>
                    </div>
                    @endcan
                


                    @can('portal::access')
                        <div class="col-md-4">
                            <a class="dropdown-icon-item" href="{{ route('portal::dashboard-msdm.index') }}">
                                <i class="bx bxs-user-rectangle" style='font-size:30px;'></i>
                                <span>Dashboard MSDM</span>
                            </a>
                        </div> 
                    @endcan
                @endif
            </div>
            
        </div>
    </div>
</div>
