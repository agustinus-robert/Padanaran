<?php

namespace Modules\Poz\Http\Livewire\Master;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Poz\Models\Supplier as SupplierData;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\SupplierStoreRequest;
use Modules\Poz\Repositories\SupplierRepository;
use DB;

class Supplier extends Component
{
    use WithFileUploads, SupplierRepository;

    public $form = [];
    public $document;
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->supplier;
        $this->action = $action;

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $supplier = SupplierData::find($id);
            $this->form['id'] = $supplier->id;
            $this->form['code'] = $supplier->code;
            $this->form['name'] = $supplier->name;
            $this->form['email'] = $supplier->email;
            $this->form['phone'] = $supplier->phone;
            $this->form['address'] = $supplier->address;
            if (!empty($supplier->location) && !empty($supplier->image_name)) {
                $this->form['document'] = $supplier->location . '/' . $supplier->image_name;
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
        $rules = (new SupplierStoreRequest())->rules();
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new SupplierStoreRequest())->attributes();
        return $attrs;
    }

    protected function messages()
    {

        $message = (new SupplierStoreRequest())->messages();
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
        // $this->form['document'] = $this->document;

        if (isset($this->form['id']) && !empty($this->form['id'])) {
            if ($this->updateSupplier($this->form, $this->form['id'], $this->document) == true) {

                return redirect(route('poz::master.supplier.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::master.supplier.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeSupplier($this->form, $this->document) == true) {
            return redirect(route('poz::master.supplier.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::master.supplier.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        return view('poz::livewire.master.supplier');
    }
}
