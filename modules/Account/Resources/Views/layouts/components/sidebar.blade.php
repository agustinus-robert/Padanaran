<div class="nav-sidebar py-md-0 sidenav overflow-auto py-4" tabindex="-1" id="sidebar" style="max-width: 20rem;">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <div class="p-lg-4 p-md-1 text-center">
        <div class="rounded-circle mx-auto mb-4" style="background: url('{{ Auth::user()->profile_avatar_path }}') center center no-repeat; background-size: cover; width: 96px; height: 96px;"></div>
        <h6 class="mb-1">{{ Auth::user()->name }}</h6>
        <div class="text-muted">{{ Auth::user()->email_address }}</div>
    </div>
    <div class="dropdown-divider ms-md-0 border-secondary mx-3 my-3" style="opacity: .25"></div>
    <ul class="nav nav-pills flex-column">
        <li class="nav-item mb-2"><a class="nav-link text-dark" href="{{ route('account::home') }}"><i class="mdi mdi-home-variant-outline"></i> Home</a></li>
        <li class="nav-item mb-2"><a class="nav-link text-dark" href="{{ route('account::notifications') }}"><i class="mdi mdi-bell-outline"></i> Notifikasi</a></li>
        <li class="nav-item mb-2"><a class="nav-link text-dark" href="{{ route('account::user.profile') }}"><i class="mdi mdi-account-outline"></i> Profil saya</a></li>
        <li class="nav-item mb-2"><a class="nav-link text-dark" href="{{ route('account::user.avatar') }}"><i class="mdi mdi-account-circle-outline"></i> Foto profil</a></li>
        <li class="nav-item mb-2"><a class="nav-link text-dark" href="{{ route('account::user.password') }}"><i class="mdi mdi-lock-open-outline"></i> Ubah sandi</a></li>
        <div class="dropdown-divider me-md-5 ms-md-0 border-secondary mx-3 my-3" style="opacity: .25"></div>
        <li class="nav-item mb-2"><a class="nav-link text-dark" href="#" onclick="signout();"><i class="mdi mdi-power text-danger"></i> Keluar</a></li>
    </ul>
</div>

@push('styles')
    <style>
        .sidenav {
            height: 100vh;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #dfdcdc;
            overflow-x: hidden;
            overflow-y: auto;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 15px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .nav-link.active {
            box-shadow: inset 2px 0 0 var(--bs-red);
            background: white !important;
            color: inherit !important;
            border-radius: 0;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>
    <script>
        let sidebar = document.querySelector('#sidebar');

        function openNav() {
            document.getElementById("sidebar").style.width = "250px";
            if (window.innerWidth > 412) {
                document.getElementById("main").style.marginLeft = "250px";
            }
        }

        function closeNav() {
            document.getElementById("sidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }

        document.addEventListener('DOMContentLoaded', () => {
            [...document.querySelectorAll('#sidebar .nav-link')].forEach(el => {
                el.classList.toggle('active', window.location.href.includes(el.href))
            })
        });
    </script>
@endpush
