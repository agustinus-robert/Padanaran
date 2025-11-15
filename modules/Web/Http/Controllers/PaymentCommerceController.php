<?php

namespace Modules\Web\Http\Controllers;

use Modules\Reference\Http\Controllers\Controller;
use Modules\Pos\Models\ProductTransaction;
use Modules\Pos\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;
use DB;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentCommerceController extends Controller
{
    public function index($uuid)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');

        $snapToken = '';
        $productTransactions = ProductTransaction::where('num', $uuid)->first();

        if (empty($productTransactions->status)) {
            $payment = 0;

            foreach (Cart::where('num', $uuid)->get() as $key => $val) {
                $payment += $val->product->price * $val->qty;
            }

            $transactionDetails = [
                'transaction_details' => [
                    'order_id'    => uniqid(),
                    'gross_amount' => ($payment + $productTransactions->shipping),
                ]
            ];

            $snapToken = Snap::getSnapToken($transactionDetails);
        }


        $dataUser = ProductTransaction::where('num', $uuid)->first();
        $cart = Cart::where('num', $uuid)->get();

        return view('web::payment.index', compact('snapToken', 'dataUser', 'cart', 'productTransactions'));
    }

    public function status(Request $request)
    {
        $dataUser = ProductTransaction::where('session_id', Session::getId())->whereNull('status')->first();
        $dataCart = Cart::where('session_id', Session::getId())->whereNull('status')->update(['status' => 1]);

        if ($dataUser) {
            $dataUser->status = $request->status;
            $dataUser->save();
        }


        return response()->json(['success' => true, 'message' => 'Status transaksi diperbarui']);
    }
}
