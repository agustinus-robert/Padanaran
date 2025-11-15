<div class="dropdown d-inline-block">
    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img class="rounded-circle header-profile-user" src="{{ Auth::user()->profile_avatar_path }}" style="width:40px !important;" alt="Header Avatar">
        @if (session('login_as_nik'))
            <span class="d-none d-xl-inline-block ms-1" key="t-henry">Wali, {{ Auth::user()->name }}</span>
        @else
            <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ Auth::user()->name }}</span>
        @endif
        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <!-- item-->
        @if(in_array(Auth::user()?->employee?->position?->position_id, [
            \Modules\Core\Enums\PositionTypeEnum::KASIRTOKO->value,
            \Modules\Core\Enums\PositionTypeEnum::KASIRSWALAYAN->value,
            \Modules\Core\Enums\PositionTypeEnum::SUPPLIER->value,
        ])) 
            <a class="dropdown-item" href="{{ route('portal::dashboard.index') }}"><i class="bx bxs-dashboard  font-size-16 me-1 align-middle"></i> <span key="t-profile">Dashboard</span></a>
        @else
            <a class="dropdown-item" href="{{ route('portal::dashboard-msdm.index') }}"><i class="bx bxs-dashboard  font-size-16 me-1 align-middle"></i> <span key="t-profile">Dashboard</span></a>
        @endif
        <a class="dropdown-item" href="{{ route('account::user.profile') }}"><i class="bx bx-user font-size-16 me-1 align-middle"></i> <span key="t-profile">Profile</span></a>
        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bx bx-power-off font-size-16 text-danger me-1 align-middle"></i>
            <span key="t-logout">Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>