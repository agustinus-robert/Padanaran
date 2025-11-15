<?php

namespace Modules\Poz\Http\Livewire\Master;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Poz\Models\Tax as TaxData;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\TaxStoreRequest;
use Modules\Poz\Repositories\TaxRepository;
use DB;

class Tax extends Component
{
    use WithFileUploads, TaxRepository;

    public $form = [];
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->tax;
        $this->action = $action;

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $tax = TaxData::find($id);
            $this->form['id'] = $tax->id;
            $this->form['code'] = $tax->code;
            $this->form['name'] = $tax->name;
            $this->form['rate'] = $tax->rate;
            $this->form['actived_on'] = $tax->actived_on;
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
        $rules = (new TaxStoreRequest())->rules();
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new TaxStoreRequest())->attributes();
        return $attrs;
    }

    protected function messages()
    {

        $message = (new TaxStoreRequest())->messages();
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
            if ($this->updateTax($this->form, $this->form['id']) == true) {
                return redirect(route('poz::master.tax.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::master.tax.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeTax($this->form) == true) {
            return redirect(route('poz::master.tax.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::master.tax.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        return view('poz::livewire.master.tax');
    }
}
