<style>
    /* 1. TOMBOL SYNC - SEKARANG TAMPIL TERUS */
    #sync-fab-main {
        position: fixed !important;
        bottom: 100px; 
        right: 30px;
        z-index: 1050 !important;
        display: block !important; 
    }
    
    #sync-fab-main button {
        width: 45px !important;
        height: 45px !important;
        border: none;
    }

    /* 2. SIDEBAR KHUSUS SYNC (MANDIRI) */
    #sidebar-sync-custom {
        position: fixed;
        top: 0;
        right: -400px; /* Sembunyi di kanan */
        width: 350px;
        height: 100%;
        background: #fff;
        z-index: 1100;
        transition: 0.3s ease;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
        padding: 20px;
    }

    #sidebar-sync-custom.active {
        right: 0; /* Muncul cok */
    }

    .sync-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        z-index: 1099;
        display: none;
    }

    .sync-overlay.active { display: block; }

    /* Animasi putar icon */
    .sync-spin { animation: spin 2s linear infinite; }
    @keyframes spin { 100% { transform: rotate(360deg); } }

    #sync-task-container {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>

<div id="sync-fab-main">
    <button type="button" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center p-0" 
            onclick="toggleSyncSidebar(true)">
        <i class="material-symbols-rounded fs-4" id="sync-icon-status">sync</i>
    </button>
</div>

<div class="sync-overlay" onclick="toggleSyncSidebar(false)"></div>
<div id="sidebar-sync-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Proses Sinkronisasi</h5>
        <button class="btn btn-link text-dark p-0" onclick="toggleSyncSidebar(false)">
            <i class="material-symbols-rounded">close</i>
        </button>
    </div>
    <div id="sync-task-container">
        {{-- <div class="alert alert-light border d-flex align-items-center" role="alert">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            <span class="text-xs">Menghubungkan ke server...</span>
        </div> --}}
    </div>
</div>

<div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-symbols-rounded py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Silahkan Kelola Modul Anda</h5>
          <p>Daftar modul</p>
        </div>
        <div class="float-end mt-4">
            <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
              <i class="material-symbols-rounded">clear</i>
            </button>
        </div>
      </div>
      <hr class="horizontal dark my-1">

      <div class="card-body pt-0">
        <div class="container-fluid px-0">
            <div class="row text-center">
                <div class="col-4 border border-light">
                    <a class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5"
                    href="{{ route('core::dashboard') }}">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">settings</span>
                        <span class="menu-label" style="font-size:15px;">Setting</span>
                    </a>
                </div>
                <div class="col-4 border border-light">
                    <a href="{{ route('administration::dashboard') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">apartment</span>
                        <span class="menu-label" style="font-size:15px;">Administrasi</span>
                    </a>
                </div>
                <div class="col-4 border border-light">
                    <a href="{{ route('teacher::home') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">menu_book</span>
                        <span class="menu-label" style="font-size:15px;">Guru</span>
                    </a>
                </div>
                <div class="col-4 border border-light">
                    <a href="{{ route('academic::home') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">school</span>
                        <span class="menu-label" style="font-size:15px;">Akademik</span>
                    </a>
                </div>
                <div class="col-4 border border-light">
                    <a href="{{ route('counseling::home') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">record_voice_over</span>
                        <span class="menu-label" style="font-size:15px;">Konseling</span>
                    </a>
                </div>
                <div class="col-4 border border-light">
                    <a href="{{ route('hrms::dashboard') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">badge</span>
                        <span class="menu-label" style="font-size:15px;">HRMS</span>
                    </a>
                </div>
                <div class="col-4 border border-light">
                    <a href="{{ route('portal::dashboard-msdm.index') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">captive_portal</span>
                        <span class="menu-label" style="font-size:15px;">Portal</span>
                    </a>
                </div>
                <div class="col-4 border border-light">
                    <a href="{{ route('finance::dashboard') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">payments</span>
                        <span class="menu-label" style="font-size:15px;">Finance</span>
                    </a>
                </div>
                <div class="col-4 border border-light">
                    <a href="{{ route('boarding::dashboard') }}" class="d-flex flex-column align-items-center justify-content-center bg-body-secondary rounded p-5">
                        <span class="material-symbols-rounded menu-icon fs-4 mb-2">home_work</span>
                        <span class="menu-label" style="font-size:15px;">Pondok</span>
                    </a>
                </div>
            </div>
        </div>

        <hr class="horizontal dark my-3">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('account::user.profile') }}" class="btn bg-gradient-info d-flex align-items-center gap-2 bg-body-secondary rounded w-100">
                    <span class="material-symbols-rounded fs-4">account_circle</span>
                    <span class="menu-label" style="font-size:15px;">Profil anda</span>
                </a>
            </div>
            <div class="col-12">
                <a href="javascript:void(0)" onclick="signout();" class="btn bg-gradient-primary d-flex align-items-center gap-2 bg-body-secondary rounded w-100">
                    <span class="material-symbols-rounded fs-4">logout</span>
                    <span class="menu-label" style="font-size:15px;">Keluar</span>
                </a>
            </div>
        </div>
      </div>
    </div>
</div>

<script src="{{ asset('material/js/core/popper.min.js') }}"></script>
<script src="{{ asset('material/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('material/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{ asset('material/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script src="{{ asset('material/js/material-dashboard.js')}}"></script>

<script>
    // Fungsi untuk buka tutup sidebar custom di extra
    function toggleSyncSidebar(show) {
        const sidebar = $('#sidebar-sync-custom');
        const overlay = $('.sync-overlay');
        const icon = $('#sync-icon-status');

        if (show) {
            sidebar.addClass('active');
            overlay.addClass('active');
            icon.addClass('sync-spin'); // Gear berputar
        } else {
            sidebar.removeClass('active');
            overlay.removeClass('active');
            // Berhenti berputar hanya jika tidak ada progress aktif
            if ($('.progress-bar-animated').length === 0) {
                icon.removeClass('sync-spin');
            }
        }
    }

    // Fungsi tambahan lo (signout, dll) tetep di sini
    const signout = (e) => {
        if (confirm('Apakah Anda yakin?')) {
            document.getElementById('signout-form').submit();
        }
    }
</script>

<form id="signout-form" action="{{ route(config('modules.auth.signout.route')) }}" method="POST" style="display: none;"> @csrf </form>