<?php

namespace Modules\Poz\Http\Livewire\Master;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Modules\Poz\Models\Category as CategoryData;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Http\Requests\CategoryStoreRequest;
use Modules\Poz\Repositories\CategoryRepository;
use DB;

class Category extends Component
{
    use WithFileUploads, CategoryRepository;

    public $form = [];
    public $isSubCategory = '';
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->category;
        $this->action = $action;

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $category = CategoryData::find($id);
            $this->form['id'] = $category->id;
            $this->form['code'] = $category->code;
            $this->form['name'] = $category->name;
            $this->form['description'] = $category->description;
            $this->form['parent_id'] = $category->parent_id;
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
        $rules = (new CategoryStoreRequest())->rules();
        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new CategoryStoreRequest())->attributes();
        return $attrs;
    }

    protected function messages()
    {

        $message = (new CategoryStoreRequest())->messages();
        return $message;
    }

    public function categoryChanged($changeValue)
    {
        $this->isSubCategory = 1;
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
            if ($this->updateCategory($this->form, $this->form['id']) == true) {
                return redirect(route('poz::master.category.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::master.category.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeCategory($this->form) == true) {
            return redirect(route('poz::master.category.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::master.category.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function render()
    {
        $data['category'] = CategoryData::get();
        return view('poz::livewire.master.category', $data);
    }
}
