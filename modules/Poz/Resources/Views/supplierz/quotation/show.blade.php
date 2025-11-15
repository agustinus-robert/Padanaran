@extends('poz::layout.index_supplier')

@section('title', env('APP_NAME') . ' Brand')

@section('navtitle', env('APP_NAME') . ' Brand')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Detail Penawaran</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Penawaran</a></li>
                    <li class="breadcrumb-item active">Detail Penawaran</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold">Rincian</h5>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="col">Invoice</th>
                                <td scope="col">{{$quotation->reference}}</td>
                            </tr>
                            
                            <tr>
                                <th scope="row">Diajukan Tanggal</th>
                                <td>{{$quotation->created_at}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Outlet Tujuan</th>
                                <td>{{$quotation->outlets->first()->name}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!--end col-->
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="d-flex">
                    <img src="assets/images/companies/wechat.svg" alt="" height="50">
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-semibold">Isi Pengajuan</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">               
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <h5>
                                Pembayaran
                            </h4>

                            <div>
                                <span class="badge bg-secondary">
                                    {{ $quotation->payment_on->name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <h5>
                                Status
                            </h4>

                            <div>
                               @if($quotation->status == 1)
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($quotation->status == 2)
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($quotation->status == 3)
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">Belum Dikirim</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">Rincian Produk:</h5>
                
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->productQuotationItems as $items)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ asset('uploads/'.$items->location.'/'.$items->image_name) }}" 
                                    target="_blank" 
                                    class="text-center btn btn-sm btn-primary">
                                        Cek Gambar
                                    </a>
                                </td>                                
                                <td>{{ $items->name }}</td>
                                <td>Rp {{ number_format($items->price, 0, ',', '.') }}</td>
                                <td>
                                    @if($items->status == 1)
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($items->status == 2)
                                        <span class="badge bg-success">Diterima</span>
                                    @elseif($items->status == 3)
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Dikirim</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h5 class="mb-3">Feedback Penerima Pengajuan:</h5>

                @if(empty($quotation->comments))
                    <p class="text-muted">Kosong</p>
                @else
                    <p class="text-muted">{{$quotation->comments}}</p>
                @endif
            </div>
        </div>
    </div><!--end col-->
</div>
@endsection