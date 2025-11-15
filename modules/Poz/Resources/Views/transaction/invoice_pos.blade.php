<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>

    <style>

        /* Reset dan Set margin dan padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            color: #333;
        }

        p {
            font-size: 7px;
        }


        /* Wrapper utama */
        #wrapper {
            width: 100%;
            max-width: 80mm; /* Ukuran untuk thermal printer 80mm */
            padding: 10px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        /* Receipt Data */
        #receiptData {
            margin-bottom: 20px;
        }

        /* Logo dan Header */
        .text-center {
            margin-bottom: 15px;
        }

        .text-center img {
            width: 100px; /* Sesuaikan ukuran logo */
            margin-bottom: 10px;
        }

        .text-center h3 {
            text-transform: uppercase;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .text-center p {
            font-size: 7px;
            color: #777;
        }

        /* Tabel */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table td, .table th {
            padding: 5px 10px;
            font-size: 7px;
            text-align: left;
        }

        .table .no-border {
            border: none;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .table .text-right {
            text-align: right;
        }

        /* Footer dan Teks Terima Kasih */
        #receiptData .text-center p:last-child {
            margin-top: 15px;
            font-size: 12px;
            color: #333;
            font-weight: bold;
        }

        /* Button Print */
        #buttons {
            text-transform: uppercase;
            text-align: center;
            padding-top: 10px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            margin: 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-default {
            background-color: #f0f0f0;
            color: black;
        }

        /* Menyembunyikan tombol print saat mencetak */
        .print-button {
            display: block;
        }

        /* Pengaturan margin dan padding untuk menghindari konten bergeser */
/* Pengaturan margin dan padding untuk menghindari konten bergeser */
@media print {
    body {
        margin: 0;
        padding: 0;
    }

    #wrapper {
        max-width: 58mm; /* Ukuran thermal printer 58mm */
        margin: 0 auto; /* Pastikan konten terpusat */
        padding: 0;
        box-shadow: none;
        border-radius: 0;
    }

    /* Menyembunyikan tombol print saat pencetakan */
    .print-button {
        display: none;
    }

    /* Mengatur margin dan lebar agar pas dengan ukuran kertas 58mm */
    html, body {
        width: 58mm; /* Tentukan ukuran kertas 58mm */
        height: auto;
    }

    /* Menghilangkan margin pada body untuk menghindari pergeseran konten */
    body {
        padding: 0;
        margin: 0;
    }

    /* Menyusun halaman cetak agar konten pas di tengah */
    .print-container {
        display: block;
        text-align: center;
        margin: 0 auto;
        padding: 10px;
    }

    /* Tentukan batas kertas untuk thermal printer (58mm) */
    @page {
        size: 58mm auto; /* Ukuran kertas untuk thermal printer */
        margin: 0;
    }
}
    </style>
</head>
<body>

<div id="wrapper">
    <div id="receiptData">
        <!-- Logo and Header Section -->
        <div class="text-center">
            <h6>Kasir 1 (Shift 1)</h6>
            <table class="table">
                <tbody>
                    <tr>
                        <td><b>Jl. Sleman Ngangklik</b></td>
                    </tr>

                    <tr>
                        <td><b>Tel. 0123456789</b></td>
                    </tr>
                    <tr>
                        <td><b>{{tgl_indo(date('Y-m-d', strtotime($sale->created_at)))}}</b></td>
                    </tr>

                    <tr>
                        <td><b>Customer: Walk-in Customer</b></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>

        <table class="table">
            <tbody>
                @php 
                    $i = 1; 
                    $price = 0;
                @endphp

              @foreach($sale->saleItems as $key => $value)
                @php 
                  $price += productItem($value->product_id)->price;
                @endphp

                <tr>
                    <td colspan="2" class="no-border">#{{$i}}: &nbsp;&nbsp;{{productItem($value->product_id)->name}}</span></td>
                </tr>
                <tr>
                    <td class="no-border border-bottom">{{$value->qty}}pcs x {{productItem($value->product_id)->price}} </td>
                   @php
                    $hitungHPP = $value->qty * productItem($value->product_id)->price;
                  @endphp
                    <td class="no-border border-bottom text-right">Rp {{number_format(round($hitungHPP, 0), 0, ",", ".")}},00</td>
                </tr>
            @endforeach

            </tbody>
            @php 
            $price_ppn = ($price * 11) / 100;
            $grand_total = $price + $price_ppn + $sale->discount;
          @endphp

            <tfoot>
                <tr>
                    <th>PPN 11%</th>
                    <th class="text-right">Rp {{number_format(round($price_ppn, 0), 0, ",", ".")}},00</th>
                </tr>
                <tr>
                    <th>Discount</th>
                    <th class="text-right">Rp {{number_format(round($sale->discount, 0), 0, ",", ".")}},00</th>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <th class="text-right">Rp {{number_format(round($grand_total, 0), 0, ",", ".")}},00</th>
                </tr>
            </tfoot>
        </table>

        <!-- Payment Details -->
        <br />

        <!-- Thank You Message -->
        <p>Terima kasih telah berbelanja</p>
    </div>

    <!-- Print Buttons -->
    <div id="buttons">
        <hr>
        <button class="btn btn-soft-success print-button" onclick="window.print()"><i class="fas fa-print" aria-hidden="true"></i> Cetak Nota</button>
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript" src="https://sma.tec.sh/themes/default/admin/assets/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="https://sma.tec.sh/themes/default/admin/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://sma.tec.sh/themes/default/admin/assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://sma.tec.sh/themes/default/admin/assets/js/custom.js"></script>
<script type="text/javascript">
    function printDot() {
        var mywindow = window.open('', 'sma_pos_print');
        mywindow.document.write('<html><head><title>CashDrawer</title>');
        mywindow.document.write('</head><body>.</body></html>');
        mywindow.print();
        mywindow.close();
        return true;
    }
</script>

</body>
</html>
