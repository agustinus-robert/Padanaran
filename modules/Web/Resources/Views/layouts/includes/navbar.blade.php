<nav class="navbar navbar-expand-sm navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand mr-0" href="{{ config('app.url') }}">
            <img src="{{ asset('img/logo/navbar.png') }}" height="65" class="d-none d-sm-inline-block mr-2">
            <img src="{{ asset('img/logo/rounded-bw-128.png') }}" width="30" height="30" class="d-inline-block d-sm-none mr-2">
        </a>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="mdi mdi-menu mdi-24px"></span>
        </button>
        @auth
            <ul class="navbar-nav ml-auto flex-row">
                <li class="nav-item">
                    <a class="nav-link pr-3 pt-1" href="javascript:;" role="button" data-toggle="modal" data-target="#navbar-apps"> <i class="mdi mdi-apps mdi-24px"></i> </a>
                    @include('web::layouts.includes.navbar-apps')
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle no-caret mr-2" href="javascript:;" role="button" data-toggle="dropdown">
                        <img src="{{ asset('img/default-avatar.svg') }}" alt="" height="30" class="rounded-circle">
                        <span class="d-none d-sm-inline pl-2">{{ auth()->user()->username }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right position-absolute">
                        <a class="dropdown-item" href="{{ route('account::index') }}">Akun saya</a>
                        <a class="dropdown-item" href="{{ route('account::user.password', ['next' => url()->full()]) }}">Ubah password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('account::auth.logout') }}" onclick="event.preventDefault(); $('#logout-form').submit();">Logout</a>
                    </div>
                </li>
            </ul>
        @endauth
    </div>
</nav>

<nav class="navbar navbar-expand-sm navbar-light bg-light py-sm-2 py-0">
    <div class="container">
        <div class="navbar-collapse py-sm-0 collapse py-2" id="navbar-menu">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"> <a class="nav-link mr-3 pl-0" href="{{ config('app.url') }}">BERANDA</a></li>
                <li class="nav-item"> <a class="nav-link mr-3" href="javascript:;">TENTANG KAMI</a></li>
                <li class="nav-item"> <a class="nav-link mr-3" href="javascript:;">PENGUMUMAN</a></li>
            </ul>
            @guest
                <div class="navbar-nav py-sm-0 ml-auto py-2">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('account::auth.login', ['next' => url()->full()]) }}"><i class="mdi mdi-login"></i> MASUK</a>
                </div>
            @endguest
        </div>
    </div>
</nav>
