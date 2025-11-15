@section('navbars')
    <div id="kt_aside" class="aside card" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
        <!--begin::Aside menu-->
        <div class="aside-menu flex-column-fluid px-4">
            <!--begin::Aside Menu-->
            <div class="hover-scroll-overlay-y mh-100 my-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="{default: '#kt_aside_footer', lg: '#kt_header, #kt_aside_footer'}" data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="{default: '5px', lg: '75px'}">
                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_aside_menu" data-kt-menu="true">
                    <div class="menu-item pt-5">
                        <a class="menu-link {{ Route::is('portal::guest.dashboard.index') ? 'active' : '' }}" href="{{ route('portal::guest.dashboard.index') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-view-dashboard fs-2"></i>
                            </span>
                            <span class="menu-title">
                                Dashboard
                            </span>
                        </a>
                    </div>


                    <div class="menu-item pt-5">
                        <a class="menu-link {{ Route::is('portal::guest.donate.index') ? 'active' : '' }}" href="{{ route('portal::guest.donate.index') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-cash fs-2"></i>
                            </span>
                            <span class="menu-title">
                                Donator
                            </span>
                        </a>
                    </div>


                    <div class="menu-item pt-5">
                        <a class="menu-link {{ Route::is('portal::guest.volunteer.index') ? 'active' : '' }}" href="{{ route('portal::guest.volunteer.index') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-hand-heart fs-2"></i>
                            </span>
                            <span class="menu-title">
                                Relawan
                            </span>
                        </a>
                    </div>

                    <div class="menu-item pt-5">
                        <a class="menu-link {{ Route::is('portal::guest.history_partnership.index') ? 'active' : '' }}" href="{{ route('portal::guest.history_partnership.index') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-history fs-2"></i>
                            </span>
                            <span class="menu-title">
                                Riwayat Partnership
                            </span>
                        </a>
                    </div>

                    {{-- <div class="menu-item pt-5">
            <a class="menu-link" href="">
              <span class="menu-icon">
                <i class="mdi mdi-handshake fs-2"></i>
              </span>
              <span class="menu-title">
                Partnership
              </span>
            </a>
          </div> --}}

                </div>
                <!--end::Menu-->
            </div>
        </div>
        <!--end::Aside menu-->
        <div class="aside-footer flex-column-auto px-7 pb-7 pt-5" id="kt_aside_footer">
            @livewire('portal::partnership-action')
        </div>
    </div>
@endsection
