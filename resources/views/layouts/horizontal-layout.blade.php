<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>Padanaran - Digi Board</title>

    <meta name="description" content="Point Of Sale UMKM">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <meta property="og:title" content="Point Of Sale UMKM">
    <meta property="og:site_name" content="POS">
    <meta property="og:description" content="Point Of Sale UMKM">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">


    @if(config('theme.default') == 'material')
        @include('layouts.component.material-style')
    @elseif(config('theme.default') == 'skote')
        @include('layouts.component.skote-style')
    @endif

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@chgibb/css-spinners@2.2.1/css/spinners.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @stack('style')
</head>

<body data-topbar="dark" data-layout="horizontal">

    @stack('nav')
    @if(config('theme.default') == 'material')
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
            @yield('body-content')
        </main>
    @elseif(config('theme.default') == 'skote')
        <div id="layout-wrapper">
            @yield('body-content')
        </div>
    @endif


    <footer class="footer py-4  ">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                    document.write(new Date().getFullYear())
                </script>2025,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
                </div>
            </div>
            <div class="col-lg-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                    <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                    <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
                </ul>
            </div>
            </div>
        </div>
        </footer>
    @if(config('theme.default') == 'material')
        @include('layouts.component.material-extra')
    @elseif(config('theme.default') == 'skote')
        @include('layouts.component.skote-extra')
    @endif

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <!-- apexcharts -->


    <script>
        const notyf = new Notyf();

        $(document).ready(function(){
            $('.select-2').select2({
                width: '100%',
                placeholder: 'Pilih salah satu',
                dropdownCssClass: "select2-custom-dropdown",
                selectionCssClass: "select2-custom-selection"
            });
        });

        window.addEventListener('alert-success', event => {
            notyf.success(event.detail.message);
        });

        window.addEventListener('alert-danger', event => {
            notyf.error(event.detail.message);
        });
    </script>

    @stack('scripts')

    <script>
        function startProgress(key) {
            document.getElementById('progress-box').style.display = 'block';

            let interval = setInterval(() => {
                fetch('/progress?schedule_key=' + key)
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('progress-bar').style.width = data.percent + '%';

                        if (data.percent >= 100) {
                            clearInterval(interval);
                            setTimeout(() => {
                                document.getElementById('progress-box').style.display = 'none';
                                document.getElementById('progress-bar').style.width = '0%';
                            }, 800);
                        }
                    });
            }, 1000);
        }
    </script>
    <?php if (session('progress_key')): ?>
        <script>
            startProgress("<?= session('progress_key'); ?>");
        </script>
    <?php endif; ?>

</body>

</html>
