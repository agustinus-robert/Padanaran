<?php

namespace Modules\Web\Http\Livewire\ProductsCommerces;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use \App\Mail\MailablePay;
use Modules\Pos\Models\Product as ProductData;
use Modules\Pos\Models\Category as CategoryData;
use Modules\Pos\Models\Brand as BrandData;
use Modules\Pos\Models\Cart as CartData;
use Illuminate\Support\Facades\Session;
use RalphJSmit\Livewire\Urls\Facades\Url;
//use Livewire\Attributes\On;
use DB;
// use Livewire\Livewire;

// // Menetapkan route dengan parameter tambahan
// Livewire::setUpdateRoute(function ($handle) {
//     return Route::post('/custom/livewire/update/{id}', function ($id) use ($handle) {
//         // Kirim ID ke metode komponen
//         $handle->update($id);

//         return response()->json(['status' => 'updated']);
//     });
// });
class ProductFront extends Component
{
    protected $listeners = ['itemAdded'];

    public $first_name;
    public $last_name;
    public $org;
    public $message;
    public $subject;
    public $email;
    public $tab = 'individual';
    public $cmpId = '';
    public function mount()
    {
        $this->cmpId = $this->__id;
    }

    public function resets()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->org = '';
        $this->message = '';
        $this->subject = '';
        $this->email = '';
    }

    public function tabAction($action)
    {
        $this->tab = $action;
        $this->resets();
    }

    public function update()
    {
        dd('ok');
    }

    public function itemAdded($id)
    {
        // dd($id);
        $sessionId = Session::getId();
        $cartItem = CartData::where(['product_id' => $id, 'session_id' => $sessionId])->whereNull('status')->first();

        if ($cartItem) {
            $cartItem->increment('qty');
        } else {
            CartData::create([
                'session_id' => $sessionId,
                'product_id' => $id,
                'qty' => 1,
            ]);
        }

        return [
            'success' => true,
            'productId' => $id
        ];
    }

    public function addItemDirect($id) {}


    public function save(Request $request)
    {

        DB::beginTransaction();
        try {

            if ($this->tab == 'individual') {
                $saving = new Contact();
                $saving->first_name = $this->first_name;
                $saving->last_name = $this->last_name;
            } else {
                $saving = new ContactOrganization();
                $saving->name = $this->org;
            }

            $saving->email = $this->email;
            $saving->subject = $this->subject;
            $saving->message = $this->message;
            $saving->save();

            $this->alert('success', 'Kontak Berhasil Disimpan',  [
                'position' => 'center'
            ]);

            DB::commit();
            $this->resets();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        if (env('SALES') == 1) {
            $product = ProductData::whereNull('deleted_at')->where('outlet_id', session()->get('selected_outlet_on'))->limit(4)->get();
            $category = CategoryData::whereNull('deleted_at')->where('outlet_id', session()->get('selected_outlet_on'))->get();
            $brand = BrandData::whereNull('deleted_at')->where('outlet_id', session()->get('selected_outlet_on'))->get();
        } else {
            $product = ProductData::whereNull('deleted_at')->get();
            $category = CategoryData::whereNull('deleted_at')->get();
            $brand = BrandData::whereNull('deleted_at')->get();
        }
        $data['product'] = $product;
        $data['category'] = $category;
        $data['brand'] = $brand;

        return view('web::livewire.products.index-front', $data);
    }
}
