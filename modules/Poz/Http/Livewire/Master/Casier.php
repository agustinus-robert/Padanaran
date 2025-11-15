<?php

namespace Modules\Poz\Http\Livewire\Master;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Account\Models\User as UserData;
use Modules\Poz\Models\Outlet;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\CasierStoreRequest;
use Modules\Poz\Repositories\CasierRepository;
use DB;

class Casier extends Component
{
    use WithFileUploads, CasierRepository;

    public $form = [];
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->casier;
        $this->action = $action;

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $casier = UserData::find($id);
            $this->form['id'] = $casier->id;
            $this->form['name'] = $casier->name;
            $this->form['username'] = $casier->username;
            $this->form['email_address'] = $casier->email_address;
            if (!empty($casier->location) && !empty($casier->image_name)) {
                $this->form['document'] = $casier->location . '/' . $casier->image_name;
            } else {
                $this->form['document'] = '';
            }
        } else {
            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }
    }

    protected function rules()
    {
        $rules = (new CasierStoreRequest())->rules();
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new CasierStoreRequest())->attributes();
        return $attrs;
    }

    protected function messages()
    {

        $message = (new CasierStoreRequest())->messages();
        return $message;
    }

    public function save()
    {
        $this->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        if (UserData::where('email_address', $this->form['email_address'])->count() == 1) {
            $this->alert('error', 'Email sudah pernah didaftarkan', [
                'position' => 'center'
            ]);
            return;
        }


        if (isset($this->form['id']) && !empty($this->form['id'])) {
            if ($this->updateCasier($this->form, $this->form['id']) == true) {
                return redirect(route('poz::master.casier.index'))->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::master.casier.index'))->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeCasier($this->form) == true) {
            return redirect(route('poz::master.casier.index'))->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::master.casier.index'))->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        $data['outlet'] = Outlet::whereNull('deleted_at')->get();
        return view('poz::livewire.master.casier', $data);
    }
}
