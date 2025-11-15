<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Supplier;
use Modules\Poz\Models\SupplierSchedule;
use Modules\Poz\Models\Adjustment as Adj;
use Modules\Poz\Models\PurchaseItems;
use Modules\Poz\Repositories\AdjustmentRepository;
use Livewire\Attributes\On;
use DB;

class Adjustment extends Component
{
    use WithFileUploads, AdjustmentRepository;

    public $form = [];
    public $action;
    public $products = [];
    public $supplier = [];
    public $categories = '';
    public $brand = '';
    public $supplierNow = '';
    public $shift = [];


    public function mount($action, Request $req)
    {
        $this->form['outlet'] = request()->query('outlet', auth()->user()->current_outlet_id);

        $this->action = $action;
        $this->products = Product::whereNull('deleted_at')->get();
        $this->supplier = Supplier::whereNull('deleted_at')->get();
       // $this->products = Product::find($id);
    }

    public function showProduct($supplierId){
    
       if (empty($supplierId)) {
        $this->supplierNow = null;
        $this->products = collect();
        $this->shift = collect(); // jika kamu juga punya shift
        return;
      }

      $this->supplierNow = $supplierId;
      $outNow = $this->form['outlet'];
      $this->products = Product::whereHas('schedule', function ($query) use ($supplierId, $outNow) {
            $query->whereHas('supplier', function ($q) use ($supplierId, $outNow) {
                $q->where('supplier.id', $supplierId) 
                ->whereHas('outlets', fn($oq) => $oq->where('outlet.id', $outNow));
            });
        })->get();

    }

    public function showShift($productId){
        if (empty($productId)) {
            $this->shift = collect();
            return;
        }

        $suppNow = $this->supplierNow;
        $outNow = $this->form['outlet'];

        $this->shift = SupplierSchedule::where('product_id', $productId)
        ->whereHas('supplier', function ($query) use ($suppNow, $outNow) {
            $query->where('supplier.id', $suppNow)
                ->whereHas('outlets', fn($q) => $q->where('outlet.id', $outNow));
        })
        ->get();
    }


    public function save(){
        if ($this->storeAdjustment($this->form, $this->form['outlet']) == true) {
            return redirect(route('poz::transaction.adjustment.index') . '?outlet=' . $this->form['outlet'])->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::transaction.adjustment.index') . '?outlet=' . $this->form['outlet'])->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function modalClosed()
    {
        $this->reset(); // Reset semua data jika perlu
    }

    public function render()
    {
        $data['productInfo'] = $this->products;
        return view('poz::livewire.transaction.adjustment', $data);
    }
}
