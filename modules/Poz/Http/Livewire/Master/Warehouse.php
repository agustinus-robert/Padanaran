<?php

namespace Modules\Poz\Http\Livewire\Master;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Poz\Models\Warehouse as WarehouseData;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\WarehouseStoreRequest;
use Modules\Poz\Repositories\WarehouseRepository;
use DB;

class Warehouse extends Component
{
    use WithFileUploads, WarehouseRepository;

    public $form = [];
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->warehouse;
        $this->action = $action;

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $warehouse = WarehouseData::find($id);
            $this->form['code'] = $warehouse->code;
            $this->form['id'] = $warehouse->id;
            $this->form['name'] = $warehouse->name;
            $this->form['location'] = $warehouse->location;
            $this->form['phone'] = $warehouse->phone;
            $this->form['email'] = $warehouse->email;
        } else {
            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }
    }

    protected function rules()
    {
        $rules = (new WarehouseStoreRequest())->rules();
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new WarehouseStoreRequest())->attributes();
        return $attrs;
    }

    protected function messages()
    {

        $message = (new WarehouseStoreRequest())->messages();
        return $message;
    }

    public function save()
    {
        $this->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        if (isset($this->form['id']) && !empty($this->form['id'])) {
            if ($this->updateWarehouse($this->form, $this->form['id']) == true) {
                return redirect(route('poz::master.warehouse.index'))->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::master.warehouse.index'))->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeWarehouse($this->form) == true) {
            return redirect(route('poz::master.warehouse.index'))->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::master.warehouse.index'))->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        return view('poz::livewire.master.warehouse');
    }
}
