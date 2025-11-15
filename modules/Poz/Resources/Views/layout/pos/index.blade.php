<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Bootstrap-ecommerce by Vosidiy">
    <title>POS</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/logos/squanchy.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/logos/squanchy.jpg">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/logos/squanchy.jpg">
    <!-- jQuery -->
    <!-- Bootstrap4 files-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="{{ asset('pos-asset/css/ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ps-sale/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('pos-asset/css/OverlayScrollbars.css') }}" type="text/css" rel="stylesheet" />
    <!-- Font awesome 5 -->
    <style>
        .avatar {
            vertical-align: middle;
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .bg-default,
        .btn-default {
            background-color: #f2f3f8;
        }

        .btn-error {
            color: #ef5f5f;
        }

        .row {
            display: flex;
            /* Atur row menjadi flex untuk pengaturan kolom berdampingan */
        }

        .close {
            float: right;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: 1 !important;
        }

        .table-container {
            max-height: 450px;
            min-height: 400px;
            /* Atur tinggi maksimum sesuai kebutuhan */
            overflow-y: auto;
            /* Memberikan scroll vertikal */
            border: 1px solid #ddd;
            /* Tambahkan border jika perlu */
        }

        .table {
            width: 100%;
            /* Pastikan tabel mengisi penuh lebar */
            border-collapse: collapse;
            /* Menghindari gap antar sel */
        }

        .table td {
            vertical-align: middle;
            /* Mengatur posisi vertikal */
            text-align: center;
            /* Mengatur posisi horizontal */
        }

        .table th,
        .table td {
            padding: 8px;
            /* Menambahkan padding */
            text-align: left;
            /* Atur teks ke kiri */
            vertical-align: middle;
            /* Menjaga vertikal tengah */
        }

        .sidebar .divider {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 8px 0;
        }

        .sidebar .divider i {
            color: #ccc;
            font-size: 18px;
            /* Atur ukuran ikon sesuai kebutuhan */
        }

        .col-md-11 {
            margin-left: 126px;
            /* Sesuaikan agar sebanding dengan lebar sidebar */
            width: calc(100% - 200px);
            /* Isi sisa lebar setelah sidebar */
            padding: 0 15px;
        }
    </style>
    <!-- custom style -->
</head>

<body>
    @livewireScripts

    @yield('content')
    <!-- ========================= SECTION CONTENT END// ========================= -->
    <script src="{{ asset('pos-asset/js/jquery-2.0.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('pos-asset/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('pos-asset/js/OverlayScrollbars.js') }}" type="text/javascript"></script>
    <script>
        $(function() {
            //The passed argument has to be at least a empty object or a object with your desired options
            //$("body").overlayScrollbars({ });
            $("#items").height(552);
            $("#items").overlayScrollbars({
                overflowBehavior: {
                    x: "hidden",
                    y: "scroll"
                }
            });
            $("#cart").height(445);
            $("#cart").overlayScrollbars({});
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
