<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Supplier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait SupplierRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'name','email','address','phone','description','status'
    ];

    /**
     * Store newly created resource.
     */
    public function storeSupplier(array $data)
    {
        $supplier = new Supplier(Arr::only($data, $this->keys));
        if ($supplier->save()) {
            return true;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateSupplier(array $data, $id)
    {
        $supplier = Supplier::find($id);
        if ($supplier->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroySupplier($id)
    {
        if (Supplier::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreSupplier($id)
    {
        if (Supplier::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
