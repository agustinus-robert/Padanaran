<?php

namespace Modules\Poz\Http\Livewire\Master;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Poz\Models\Outlet as OutletData;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\OutletStoreRequest;
use Modules\Poz\Repositories\OutletRepository;
use Illuminate\Support\Facades\Auth;
use DB;

class Outlet extends Component
{
    use WithFileUploads, OutletRepository;

    public $form = [];
    public $isSubCategory = '';
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->manage_outlet;
        $this->action = $action;
        $this->form['admin_id'] = Auth::user()->id;

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $outlet = OutletData::find($id);
            $this->form['id'] = $outlet->id;
            $this->form['admin_id'] = $outlet->admin_id;
            $this->form['code'] = $outlet->code;
            $this->form['name'] = $outlet->name;
            $this->form['description'] = $outlet->description;
            $this->form['location'] = $outlet->location;
            $this->form['image_name'] = $outlet->image_name;
        } else {
            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }
    }

    protected function rules()
    {
        $rules = (new OutletStoreRequest())->rules();
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new OutletStoreRequest())->attributes();
        return $attrs;
    }

    protected function messages()
    {

        $message = (new OutletStoreRequest())->messages();
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
            if ($this->updateOutlet($this->form, $this->form['id']) == true) {
                return redirect(route('portal::dashboard.index'))->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('portal::dashboard.index'))->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeOutlet($this->form) == true) {
            return redirect(route('portal::dashboard.index'))->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('portal::dashboard.index'))->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        return view('poz::livewire.master.outlet');
    }
}
