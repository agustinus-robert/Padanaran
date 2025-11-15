<?php

namespace Modules\Web\Http\Livewire\ProductsCommerces;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use \App\Mail\MailablePay;
use Modules\Pos\Models\Product as ProductData;
use Modules\Pos\Models\Cart as CartData;
use Modules\Pos\Models\ProductTransaction;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Session;
use Midtrans\Snap;
use Midtrans\Config;
use DB;

class DeliveryManage extends Component
{


    public $cartProduct = [];
    public $taxRate = 0.11; // Pajak 10%
    public $cartz;
    public $sessionId = '';
    public $shipping = 0;
    public $total = 0;

    public function mount()
    {
        $this->sessionId = Session::getId();
        $this->cartz = CartData::where('session_id', $this->sessionId)->whereNull('status')->get();
        $this->total = $this->calculateSubtotal();
    }

    // Method untuk menghitung subtotal
    public function calculateSubtotal()
    {
        $subtotal = 0;
        foreach ($this->cartz as $cartItem) {
            $subtotal += $cartItem->qty * $cartItem->product->price;
        }
        return $subtotal;
    }

    // Method untuk menghitung pajak
    public function calculateTax()
    {
        return $this->calculateSubtotal() * $this->taxRate;
    }

    // Method untuk menghitung total (Subtotal + Pajak)
    public function calculateTotal()
    {
        return $this->calculateSubtotal() + $this->calculateTax();
    }

    #[On('shippingCosted')]
    public function costOfShipping($shippingCost)
    {
        $this->shipping = 'Rp ' . number_format(floatval($shippingCost), 0, ',', '.');
        $this->total = $this->calculateSubtotal() + $shippingCost;
        return $this->total;
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->mount();  // Or call the appropriate method to refresh the data
    }

    #[On('cartItemUpdate')]
    public function cartItemUpdate($id, $action)
    {
        $item = CartData::where(['session_id' => $this->sessionId, 'product_id' => $id])->whereNull('status')->first(['id', 'qty']);

        if ($item) {
            $newQty = $item->qty + $action;
            if ($newQty < 1) {
                $this->alert('error', 'Produk tidak boleh kurang dari 1', [
                    'position' => 'center',
                ]);
                return;
            }

            $item->qty = $newQty;
            $item->save();
        }


        $this->dispatch('cart-count', ['itemId' => $id, 'qty' => $item->qty]);
    }

    #[On('getCities')]
    public function getCities($provinceId)
    {

        // Contoh: Mengambil data dari API RajaOngkir
        $this->cities = $this->curlAPI('city?province=' . $provinceId)['rajaongkir']['results'];
        $this->dispatch('updateCities', ['city' => $this->cities]);
    }

    public function curlAPI($forApi)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/" . $forApi,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: " . env('RAJAONGKIR_API')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }
    }

    #[On('shippingSave')]
    public function curlAPICost($cityOrigin, $courier, $cityDestination)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=" . $cityOrigin . "&destination=" . $cityDestination . "&weight=50&courier=" . $courier,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . env('RAJAONGKIR_API')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $this->dispatch('updateShipping', ['json' => json_decode($response, true)['rajaongkir']['results']]);
        }
    }

    #[On('shippingTransaction')]
    public function transaction($name, $email, $province, $city, $courier, $shipping_type)
    {
        $uuid = Str::uuid()->toString();
        $insert = new ProductTransaction();
        $insert->num = $uuid;
        $insert->session_id = $this->sessionId;
        $insert->address = "Yogyakarta";
        $insert->name = $name;
        $insert->email = $email;
        $insert->province_id = $province;
        $insert->city_id = $city;
        $insert->courier = $courier;
        $insert->shipping = $shipping_type;

        if ($insert->save()) {
            CartData::where(['session_id' => Session::getId(), 'status' => null])->update([
                'num' => $uuid
            ]);

            return redirect()->route('web::payment.page', ['uuid' => $uuid]);
        }
    }

    public function render()
    {
        $data['carts'] = CartData::where(['session_id' => Session::getId(), 'status' => null])->get();
        $data['subtotal'] = $this->calculateSubtotal();
        $data['tax'] = $this->calculateTax();
        $data['total'] = $this->calculateTotal();
        $data['province'] = $this->curlAPI('province')['rajaongkir']['results'];


        return view('web::livewire.products.index-deliver', $data);
    }
}
