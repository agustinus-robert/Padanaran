@extends('web::layouts.default2', [
    'useTransparentNavbar' => true,
])
@section('title', request()->bahasa == 'id' ? 'Beranda' : 'Payment' . ' | ')

@section('main')
    <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Pembayaran<span>Shop</span></h1>
        </div><!-- End .container -->
    </div>

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    @if ($dataUser->status == 1)
                        <div class="summary">
                            <h3 class="summary-title">Status Pembayaran</h3>
                            <div class="d-flex flex-column align-items-center rounded bg-success p-4 text-white shadow-sm" style="max-width: 300px; margin: auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-check-circle-fill mb-2" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 10.03a.75.75 0 0 0 1.06 0l4-4a.75.75 0 1 0-1.06-1.06L7.5 8.94 5.53 6.97a.75.75 0 1 0-1.06 1.06l2.5 2.5z" />
                                </svg>
                                <h5 class="font-weight-bold mb-1">Pembayaran Berhasil!</h5>
                                <p class="small mb-3 text-center text-white">Terima kasih telah melakukan pembayaran. Transaksi Anda berhasil.</p>
                            </div>

                            <div class="col-12">
                                <a href="{{ route('web::home.page') }}" style="max-width: 300px; margin: auto;" class="btn-light w-100 font-weight-bold btn btn-sm"><i class="fa fa-home" aria-hidden="true"></i> Kembali ke Beranda</a>
                            </div>
                        </div>
                    @else
                        <div class="summary">
                            <h3 class="summary-title">Pilih Cara Pembayaran</h3>
                            <div class="justify-content-center">
                                <div class="justify-content-center">
                                    <div class="justify-content-center" id="snap-container"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-4">
                    <div class="summary">
                        <h3 class="summary-title">Informasi Pelanggan</h3>
                        <table class="table-summary table">
                            <thead>

                            <tbody>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>{{ $productTransactions->name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $productTransactions->email }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ $productTransactions->address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <aside class="col-4">
                    <div class="summary">
                        <h3 class="summary-title">Your Order</h3><!-- End .summary-title -->

                        <table class="table-summary table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php($subtotal = 0)
                                @php($total = 0)
                                @foreach ($cart as $key => $value)
                                    <tr>
                                        <td><a href="#">{{ $value->product->name }}</a></td>
                                        <td>{{ $value->qty }} x {{ number_format($value->product->price, 0, ',', '.') }}</td>
                                        @php($subtotal += $value->qty * $value->product->price)
                                    </tr>
                                @endforeach
                                <tr class="summary-subtotal">
                                    <td>Subtotal:</td>
                                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr><!-- End .summary-subtotal -->
                                <tr>
                                    <td>Shipping:</td>
                                    <td>{{ number_format($productTransactions->shipping, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="summary-total">
                                    <td>Total:</td>
                                    @php($total = $subtotal + $productTransactions->shipping)
                                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr><!-- End .summary-total -->
                            </tbody>
                        </table><!-- End .table table-summary -->
                    </div><!-- End .summary -->
                </aside>
            </div>
        </div>
    </div>
@endsection
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@push('scripts')
    <script>
        function updateTransactionStatus(status) {
            // Buat request AJAX
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('web::payment.transaction') }}", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}"); // Tambahkan CSRF token Laravel

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        // setTimeout(function() {
                        location.reload()

                    } else {
                        toastr.error(response.message);
                    }
                }
            };

            // Kirim data dalam format JSON
            xhr.send(JSON.stringify({
                status: status
            }));
        }

        document.addEventListener("DOMContentLoaded", function() {
            let snapContainer = document.getElementById("snap-container");
            if (snapContainer) {
                snap.embed('{{ $snapToken }}', {
                    embedId: 'snap-container',
                    onSuccess: function(result) {
                        updateTransactionStatus(1)
                    },
                    onPending: function(result) {
                        updateTransactionStatus(2)
                    },
                    onError: function(result) {
                        updateTransactionStatus(3)
                    }
                });
            }
        });
    </script>
@endpush
