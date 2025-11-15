<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Poz\Models\Product as ProductData;
use Modules\Poz\Models\Brand;
use Modules\Poz\Models\Category;
use Modules\Poz\Models\Tax;
use Modules\Poz\Models\Unit;
use Modules\Poz\Models\Supplier;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\ProductStoreRequest;
use Modules\Poz\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Models\User;
use DB;

class Product extends Component
{
    use WithFileUploads, ProductRepository;

    public $form = [];
    public $action;
    public $categoryHasSub = '';
    public $subCategory = [];
    public $loading = false;
    public $supplier = [];
    public $sch = [];

    public function mount($action, Request $req)
    {
        $id = $req->product;
        $this->action = $action;
        $this->loading = false;
        $this->supplier = Supplier::whereNull('deleted_at')->get();

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $product = ProductData::find($id);
            $this->form['id'] = $product->id;
            $this->form['type'] = $product->type;
            $this->form['code'] = $product->code;
            $this->form['name'] = $product->name;
            $this->form['barcode'] = $product->barcode;
            $this->form['brand_id'] = $product->brand_id;
            $this->form['category_id'] = $product->category_id;
            $this->form['alert_qty'] = $product->alert_qty;
            $this->form['wholesale'] = $product->wholesale;
            if (!empty($product->sub_category_id)) {
                $this->categoryHasSub = 1;
                $this->subCategory = Category::find($product->sub_category_id)->get();
                $this->form['sub_category_id'] = $product->sub_category_id;
            }

            $this->form['unit_id'] = $product->unit_id;
            $this->form['tax_rate_id'] = $product->tax_rate_id;
            $this->form['price'] = $product->price;

            if (!empty($product->location) && !empty($product->image_name)) {
                $this->form['document'] = $product->location . '/' . $product->image_name;
            } else {
                $this->form['document'] = '';
            }
        } else {
            if ($action !== 'direction') {
                $this->action = 'Tambah';
            }
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }

        $this->form['outlet'] = request()->query('outlet', auth()->user()->current_outlet_id);
    }

    protected function rules()
    {
        $rules = (new ProductStoreRequest())->rules();

        if ($this->categoryHasSub == 1 && count($this->subCategory)) {
            $rules['form.sub_category_id'] = 'required';
        } else {
            $rules['form.sub_category_id'] = 'nullable';
        }
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new ProductStoreRequest())->attributes();
        if ($this->categoryHasSub == 1 && count($this->subCategory)) {
            $attrs['form.sub_category_id'] = 'Sub Kategori';
        }

        return $attrs;
    }

    protected function messages()
    {

        $message = (new ProductStoreRequest())->messages();
        if ($this->categoryHasSub == 1 && count($this->subCategory)) {
            $rules['form.sub_category_id'] = ':attribute tidak boleh kosong';
        }

        return $message;
    }

    public function sub_category_changed($cat_id)
    {
        $this->categoryHasSub = 1;

        $this->subCategory = Category::where(['parent_id' => $cat_id, 'deleted_at' => null])->get();
    }

    public function save()
    {
        $this->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );
        $outletId = $this->form['outlet'];
        $this->loading = true;

        if (isset($this->form['id']) && !empty($this->form['id'])) {
            if ($this->updateProduct($this->form, $this->form['id']) == true) {
                $this->loading = false;
                return redirect(route('poz::transaction.product.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::transaction.product.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeProduct($this->form, $this->sch) == true) {
            if ($this->action == 'direction') {
                $this->loading = false;
                $this->dispatch('productSaved');
            } else {
                $this->loading = false;
                return redirect(route('poz::transaction.product.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            }
        } else {
            return redirect(route('poz::transaction.product.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        $outletId = $this->form['outlet'];
        $data['brand'] = Brand::whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })->get();

        $data['category'] = Category::whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })->get();

        $data['tax'] = Tax::whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })->get();

        $data['unit'] = Unit::whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })->get();

        $data['loading'] = $this->loading;
        return view('poz::livewire.transaction.product', $data);
    }
}
