<?php

namespace Modules\Poz\Http\Livewire\Supplierz;

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
use Modules\Poz\Models\Outlet;
use DB;

class AdjustmentSupplier extends Component
{
    use WithFileUploads, AdjustmentRepository;

    public $form = [];
    public $action;
    public $products = [];
    public $supplier = [];
    public $categories = '';
    public $brand = '';
    public $outletNow = '';
    public $outlets = [];
    public $shift = [];

    public function mount($action, Request $req)
    {
        $this->form['outlet'] = request()->query('outlet', auth()->user()->current_outlet_id);

        $this->action = $action;
        $this->outlets = Outlet::whereNull('deleted_at')->get();
   //     $this->products = Product::whereNull('deleted_at')->get();
        $this->supplier = Supplier::whereNull('deleted_at')->get();
       // $this->products = Product::find($id);
    }

    public function showProduct($outletId){
    
       if (empty($outletId)) {
         $this->outletNow = null;
        $this->products = collect();
        $this->shift = collect(); // jika kamu juga punya shift
        return;
      }

      $this->outletNow = $outletId;
      $this->products = Product::whereHas('schedule', function ($query) use ($outletId) {
            $query->whereHas('supplier', function ($q) use ($outletId) {
                $q->where('user_id', auth()->id()) 
                ->whereHas('outlets', fn($oq) => $oq->where('outlet.id', $outletId));
            });
        })->get();
    }

    public function showShift($productId){
        if (empty($productId)) {
            $this->shift = collect();
            return;
        }

        $outletId = $this->outletNow;
        $this->shift = SupplierSchedule::where('product_id', $productId)
        ->whereHas('supplier', function ($query) use ($outletId) {
            $query->where('user_id', auth()->id())
                ->whereHas('outlets', fn($q) => $q->where('outlet.id', $outletId));
        })
        ->get();
    }


    public function save(){
        $this->form['supplier_id'] = Supplier::where('user_id', auth()->id())->first()->id;
        $this->form['is_supplier'] = 1;
        if ($this->storeAdjustment($this->form, $this->outletNow) == true) {
            return redirect(route('poz::supplierz.adjustment.index'))->with('msg-sukses', "Data berhasil disimpan");
        } else {
            return redirect(route('poz::supplierz.adjustment.index'))->with('msg-gagal', "Data gagal disimpan");
        }
    }

    public function modalClosed()
    {
        $this->reset(); // Reset semua data jika perlu
    }

    public function render()
    {
        $data['productInfo'] = $this->products;
        return view('poz::livewire.supplierz.adjustment', $data);
    }
}
