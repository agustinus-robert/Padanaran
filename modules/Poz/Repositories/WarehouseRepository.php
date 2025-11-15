<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Warehouse;

trait WarehouseRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'code',
        'name',
        'location',
        'phone',
        'email'
    ];

    /**
     * Store newly created resource.
     */
    public function storeWarehouse(array $data)
    {
        $warehouse = new Warehouse(Arr::only($data, $this->keys));
        if ($warehouse->save()) {
            return true;
        }

        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateWarehouse(array $data, $id)
    {
        $warehouse = Warehouse::find($id);
        if ($warehouse->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyWarehouse($id)
    {
        if (Warehouse::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreWarehouse($id)
    {
        if (Warehouse::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
