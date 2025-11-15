<?php

namespace Modules\Poz\Http\Livewire\Supplierz;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\Poz\Models\ProductQuotation;
use Modules\Poz\Repositories\QuotationRepository;
use Modules\Poz\Http\Requests\QuotationStoreRequest;
use Livewire\Attributes\On;
use Modules\Poz\Models\Outlet;
use DB;

class QuotationSupplier extends Component
{
    use WithFileUploads, QuotationRepository;

    public $rows = [];
    public $form = [];
    public $outlets = [];
    public $idd = '';
    public $action;

    public function mount($action, Request $req)
    {
        $id = $req->quotation;
        $this->action = $action;
        $this->outlets = Outlet::whereNull('deleted_at')->get();

        if (!empty($id) && is_string($id)) {
            $this->action = 'Perbarui';
            $quotation = ProductQuotation::with('productQuotationItems')->find($id);
            $this->form['outletId'] = $quotation->outlets->first()->id;
            $this->form['payment_on'] = $quotation->payment_on;
            $this->idd = $id;
            foreach($quotation->productQuotationItems as $item){
                $this->rows[] = [
                    'name' => $item->name, 
                    'price' => $item->price, 
                    'location' => $item->location,
                    'image_name' => $item->image_name
                ];
            }
        } else {
            $this->action = 'Tambah';

            $this->rows = [
              ['name' => '', 'price' => '']
            ];
        }
    }

    protected function rules()
    {
        $rules = (new QuotationStoreRequest())->rules();

        if (!empty($this->idd)) {
            $rules['rows.*.file'] = '';
        } else {
            $rules['rows.*.file'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        return $rules;
    }

    protected function attributes()
    {
        $attrs = (new QuotationStoreRequest())->attributes();
        if (!empty($this->idd)) {
            $rules['rows.*.file'] = '';
        } else {
            $rules['rows.*.file'] = 'Lampiran file';
        }

        return $attrs;
    }

    protected function messages()
    {

        $message = (new QuotationStoreRequest())->messages();
        if (!empty($this->idd)) {
            $rules['rows.*.name.required'] = '';
        } else {
            $rules['rows.*.name.required'] = 'Gambar harus diisi';
        }

        return $message;
    }

    public function addRow()
    {
        $this->rows[] = ['name' => '', 'price' => ''];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // reset index biar rapi
    }

    public function save()
    {
        $this->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        $outletId = $this->form['outletId'];

        if (isset($this->idd) && !empty($this->idd)) {
    
            if ($this->updateQuotation($this->form, $this->rows, $this->idd, $outletId) == true) {

                return redirect(route('poz::supplierz.quotation.index'))->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::supplierz.quotation.index'))->with('msg-gagal', "Data gagal disimpan");
            }
        } else if ($this->storeQuotation($this->form, $this->rows, $outletId) == true) {
            return redirect(route('poz::supplierz.quotation.index'))->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::supplierz.quotation.index'))->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function modalClosed()
    {
        $this->reset(); // Reset semua data jika perlu
    }

    public function render()
    {
        return view('poz::livewire.supplierz.quotation');
    }
}
