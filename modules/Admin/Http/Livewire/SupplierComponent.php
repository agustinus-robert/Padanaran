<?php

namespace Modules\Admin\Http\Livewire;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Modules\Admin\Models\Supplier;
use Modules\Admin\Http\Requests\Inventory\Supplier\StoreRequest;
use Modules\Admin\Repositories\SupplierRepository;
use Livewire\Attributes\On;

class SupplierComponent extends Component
{
    use SupplierRepository;

    #[Url()]

    public $supplier_id;
    public $form = [];
    public $isCreating = false;
    public $isEditing = false;

    public function mount(){
        if(isset($_GET['supplier_id'])){
            $this->edit($_GET['supplier_id']);
            $this->isCreating = true;
            $this->isEditing = true;
        }
    }

    protected function rules() 
    {
        return (new StoreRequest())->rules();
    } 

    protected function messages(){
        return (new StoreRequest())->messages();
    }

    public function showCreateForm()
    {
        $this->isCreating = true;
        $this->isEditing = false;
    }

    public function hideCreateForm()
    {
        return redirect('admin/inventories/suppliers');
    }

    public function save()
    {

        $this->validate();

        if($this->storeSupplier($this->form) == true){
            return redirect('admin/inventories/suppliers')->with('msg-sukses', "Data berhasil disimpan");
         } else {
            return redirect('admin/inventories/suppliers')->with('msg-gagal', "Data gagal disimpan");
         }
    }

    
    #[\Livewire\Attributes\On('supplier-edited')]
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        $this->supplier_id = $supplier->id;
        $this->form['name'] = $supplier->name;
        $this->form['email'] = $supplier->email;
        $this->form['phone'] = $supplier->phone;
        $this->form['address'] = $supplier->address;

        $this->isCreating  = true;
        $this->isEditing   = true;
    }

    public function update()
    {
        $this->validate();

        if($this->updateSupplier($this->form, $this->supplier_id) == true){
            return redirect('admin/inventories/suppliers')->with('msg-sukses', "Data berhasil diperbarui");
         } else {
            return redirect('admin/inventories/suppliers')->with('msg-gagal', "Data gagal diperbarui");
         }
    }

    #[\Livewire\Attributes\On('supplier-delete')]
    public function delete($id)
    {
        if($this->destroySupplier($id)){
            return redirect('admin/inventories/suppliers')->with('msg-sukses', "Data berhasil dihapus");
        } else {
            return redirect('admin/inventories/suppliers')->with('msg-gagal', "Data gagal diperbarui");
        }
    }

    #[\Livewire\Attributes\On('supplier-restore')]
    public function restore($id){
        if($this->restoreSupplier($id)){
            return redirect('admin/inventories/suppliers')->with('msg-sukses', "Data berhasil dikembalikan");
        } else {
            return redirect('admin/inventories/suppliers')->with('msg-sukses', "Data gagal dikembalikan");
        }
    }

    public function render()
    {
        return view('admin::livewire.supplier');
    }
}
