<?php

namespace Modules\Poz\Http\Livewire\Master;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Poz\Models\Brand as BrandData;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\BrandStoreRequest;
use Modules\Poz\Repositories\BrandRepository;
use DB;

class Brand extends Component
{
    use WithFileUploads, BrandRepository;

    public $form = [];
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->brand;
        $this->action = $action;

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $brand = BrandData::find($id);
            $this->form['id'] = $brand->id;
            $this->form['code'] = $brand->code;
            $this->form['name'] = $brand->name;
            $this->form['description'] = $brand->description;
            if (!empty($brand->location) && !empty($brand->image_name)) {
                $this->form['document'] = $brand->location . '/' . $brand->image_name;
            } else {
                $this->form['document'] = '';
            }
        } else {
            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }

        $this->form['outlet'] = request()->query('outlet', auth()->user()->current_outlet_id);
    }

    protected function rules()
    {
        $rules = (new BrandStoreRequest())->rules();
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new BrandStoreRequest())->attributes();
        return $attrs;
    }

    protected function messages()
    {

        $message = (new BrandStoreRequest())->messages();
        return $message;
    }

    public function save()
    {
        $this->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        $outletId = $this->form['outlet'];

        if (isset($this->form['id']) && !empty($this->form['id'])) {
            if ($this->updateBrand($this->form, $this->form['id']) == true) {

                return redirect(route('poz::master.brand.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::master.brand.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeBrand($this->form) == true) {
            return redirect(route('poz::master.brand.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::master.brand.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        return view('poz::livewire.master.brand');
    }
}
