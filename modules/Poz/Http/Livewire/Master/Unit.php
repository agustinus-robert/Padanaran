<?php

namespace Modules\Poz\Http\Livewire\Master;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Poz\Models\Unit as UnitData;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\UnitStoreRequest;
use Modules\Poz\Repositories\UnitRepository;
use DB;

class Unit extends Component
{
    use WithFileUploads, UnitRepository;

    public $form = [];
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->unit;
        $this->action = $action;

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $unit = UnitData::find($id);
            $this->form['id'] = $unit->id;
            $this->form['code'] = $unit->code;
            $this->form['name'] = $unit->name;
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
        $rules = (new UnitStoreRequest())->rules();
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new UnitStoreRequest())->attributes();
        return $attrs;
    }

    protected function messages()
    {

        $message = (new UnitStoreRequest())->messages();
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
            if ($this->updateUnit($this->form, $this->form['id']) == true) {
                return redirect(route('poz::master.unit.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::master.unit.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeUnit($this->form) == true) {
            return redirect(route('poz::master.unit.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::master.unit.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        return view('poz::livewire.master.unit');
    }
}
