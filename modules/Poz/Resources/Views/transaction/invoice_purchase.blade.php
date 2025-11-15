<!DOCTYPE html>
<html lang="en">
<head>

<style>
  .borderless tr td {
      border: none !important;
      padding: 15px !important;
  }

  .contact-left-form2 {
    background: #fff;
    box-shadow: 0 0 50px rgba(0, 0, 0, .1);
  }
</style>
<link href="{{asset('tour/css/bootstrap.css')}}" rel='stylesheet' type='text/css' /><!-- bootstrap css -->
<title>Invoice {{$purchase->reference}}</title>
</head>
<body>

<div class="container">
  <div class="mt-3">
    <div>
            {{--   <div class="row mb-3">
                <div class="col-8">
                </div>
                <div class="col-4">
                    <div class="input-group justify-content-end mb-4 flex-nowrap">
                            <div class="input-group-text border-success bg-success text-white">Status</div>
                            <div class="input-group-text border-success text-success bg-transparent">LUNAS</div>
                    </div>
                </div>
            </div> --}}
            
                <div class="card card-body border-success border">
                    <div class="row align-items-center gap-lg-0 gap-3">
                        <div class="col-lg-8">
                            <h6 class="fw-bold mb-1">Nota Penjualan</h6>
                            <div class="text-muted">
                                Terima kasih telah melakukan transaksi
                            </div>
                        </div>
                        {{--< div class="col-lg-4 text-lg-end">
                            <a class="btn text-nowrap btn-soft-success" href="" target="_blank"><i class="mdi mdi-eye-outline"></i></a>
                        </div> --}}
                    </div>
                </div>
           
            <hr>
            <div class="row mb-3">
                <div class="col-6">
                    <p>Transaksi Pembelian <strong>DIGIPEMAD</strong></p>
                </div>
                <div class="col-6 text-end">
                    <h4><strong>INVOICE</strong></h4>
                    <div>Tanggal Pembelian: <b style="color:blue;">{{date('Y-m-d', strtotime($purchase->created_at))}}</b></div>
                    <div class="fw-bold">No Invoice: {{$purchase->reference}}</div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-bordered table" style="width: 100%;">
                    <tr class="bg-dark text-white">
                        <th width="1%">No.</th>
                        <th>Barang</th>
                        <th>Kuantitas</th>
                        <th width="16%" class="text-end">Total Harga</th>
                    </tr>
                     @php 
                        $i = 1; 
                        $price = 0;
                     @endphp

                     @foreach($purchase->purchaseItems as $key => $value)
                        

                        @php 
                            $price += productItem($value->product_id)->price;
                        @endphp

                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{productItem($value->product_id)->name}}</td>
                            <td>{{$value->qty}}</td>
                            @php
                                $hitungHPP = $value->qty * productItem($value->product_id)->price;
                            @endphp
                            <td class="text-end">Rp {{number_format(round($hitungHPP, 0), 0, ",", ".")}},00</td>
                        </tr>
                        
                    @endforeach

                    @php 
                        $price_ppn = ($price * 11) / 100;
                        $grand_total = $price + $price_ppn + $purchase->discount;
                    @endphp 

               
                    <tr>
                        <td colspan="1"></td>
                        <td colspan="2" class="text-end"><b>Discount</b></td>
                        <td class="text-end"><i>Rp {{number_format(round($purchase->discount, 0), 0, ",", ".")}},00</i></td>
                    </tr>

                    <tr>
                        <td colspan="1"></td>
                        <td colspan="2" class="text-end"><b>PPN 11%</b></td>
                        <td class="text-end"><i>Rp {{number_format(round($price_ppn, 0), 0, ",", ".")}},00</i></td>
                    </tr>

                    <tr>
                        <td colspan="1"></td>
                        <td colspan="2" class="text-end"><b>Jumlah Yang Harus dibayarkan</b></td>
                        <td class="text-end"><b>Rp {{number_format(round($grand_total, 0), 0, ",", ".")}},00</b></td>
                    </tr>

                                       {{-- <tr>
                        <td colspan="3" class="small">
                            <div><cite><strong>Catatan</strong></cite></div>
                            <cite>{{ $invoice->meta?->note }}</cite>
                        </td>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">{{ Str::money($invoice->items->sum('price') + $tax, 0) }}</th>
                    </tr> --}}
                </table>
            </div>


            <div class="row mb-3">
                <div class="col-lg-6">
                    <p>Bank Account</p>
                    <div><strong></strong></div>
                    <table style="width: 100%">
                        <tr>
                            <td>Acc. Name</td>
                            <td>: </td>
                        </tr>
                        <tr>
                            <td>Acc. Number</td>
                            <td>: </td>
                        </tr>
                        <tr>
                            <td>Bank Address</td>
                            <td>: </td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>: </td>
                        </tr>
                        <tr>
                            <td>Swift Code</td>
                            <td>: </td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6 text-lg-end mt-5">
                    <div style="height: 150px;"></div>
                    <div><strong>Digipemad Purchasing</strong></div>
                    <div><i>Admin Purchasing</i></div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-8 mt-4">
                    <button class="btn btn-soft-success" onclick="window.print()"><i class="fas fa-print" aria-hidden="true"></i> Cetak invoice</button>
                </div>
            </div> 
        </div>  
    </div>
</div>


</body>
</html>
