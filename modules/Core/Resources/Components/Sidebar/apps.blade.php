@php($_route = explode('::', Route::currentRouteName())[0])
<div class="border-bottom mx-4 mb-2 py-3">
    <div class="row align-items-center g-2">
        <div class="col-4">
            <a href="{{ route('account::home') }}" class="btn btn-apps {{ $_route == 'account' ? 'active' : '' }}">
                <i class="mdi mdi-account-outline text-warning" style="padding: .3rem .6rem;"></i> <span class="btn-apps-label">Akun</span>
            </a>
        </div>
        <div class="col-4">
            {{-- <a href="{{ route('portal::home') }}" class="btn btn-apps">
                <i class="mdi mdi-account-convert-outline" style="padding: .3rem .6rem; color: #ff52ee;"></i> <span class="btn-apps-label">Portal</span>
            </a> --}}
        </div>
        <div class="col-4">
            <button class="btn btn-apps {{ $_route == '-1' ? 'active' : '' }}" onclick="signout()">
                <i class="mdi mdi-power-standby text-danger" style="padding: .3rem .6rem;"></i> <span class="btn-apps-label">Keluar</span>
            </button>
        </div>
    </div>
    <div class="collapse" id="sidebar-apps">
        <div>
            <div class="row justify-content-center align-items-center g-2 py-2">
                @can('core::access')
                    <div class="col-4">
                        <a href="{{ route('core::dashboard') }}" class="btn btn-apps {{ $_route == 'core' ? 'active' : '' }}">
                            <i class="mdi mdi-shield-star-outline text-white" style="padding: .3rem .6rem;"></i> <span class="btn-apps-label">Core</span>
                        </a>
                    </div>
                @endcan
                @can('hrms::access')
                    <div class="col-4">
                        <a href="{{ route('hrms::dashboard') }}" class="btn btn-apps {{ $_route == 'hrms' ? 'active' : '' }}">
                            <i class="mdi mdi-account-group-outline text-success" style="padding: .3rem .6rem;"></i> <span class="btn-apps-label">SDM</span>
                        </a>
                    </div>
                @endcan
                @can('finance::access')
                    <div class="col-4">
                        <a href="{{ route('finance::dashboard') }}" class="btn btn-apps {{ $_route == 'finance' ? 'active' : '' }}">
                            <i class="mdi mdi-cash" style="padding: .3rem .6rem; color: #25dbae;"></i> <span class="btn-apps-label">Finance</span>
                        </a>
                    </div>
                @endcan
                @can('administration::access')
                    <div class="col-4">
                        <a href="{{ route('administration::dashboard') }}" class="btn btn-apps {{ $_route == 'administration' ? 'active' : '' }}">
                            <i class="mdi mdi-human-male-board" style="padding: .3rem .6rem; color: #25dbae;"></i> <span class="btn-apps-label">Guru</span>
                        </a>
                    </div>
                @endcan
                <div class="col-4">
                    {{-- <a href="{{ route('support::index') }}" class="btn btn-apps">
                        <i class="mdi mdi-headphones text-info" style="padding: .3rem .6rem;"></i> <span class="btn-apps-label">Dukungan</span>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    <p class="mb-0 mt-3 text-center">
        <button class="btn btn-light btn-sm rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#sidebar-apps" role="button">Show more <i class="mdi mdi-chevron-down"></i></button>
    </p>
</div>

@push('styles')
    <style>
        .btn.btn-apps {
            padding: .5rem !important;
        }

        .btn.btn-apps .btn-apps-label {
            font-size: 10pt;
        }

        .btn.btn-apps.active {
            background: rgba(255, 255, 255, .05);
        }
    </style>
@endpush

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.getElementById('sidebar-apps').addEventListener('show.bs.collapse', function(e) {
                document.querySelector('[data-bs-target="#sidebar-apps"]').innerHTML = `Show less <i class="mdi mdi-chevron-up"></i>`
            });
            document.getElementById('sidebar-apps').addEventListener('hide.bs.collapse', function(e) {
                document.querySelector('[data-bs-target="#sidebar-apps"]').innerHTML = `Show more <i class="mdi mdi-chevron-down"></i>`
            });
        });
    </script>
@endpush
