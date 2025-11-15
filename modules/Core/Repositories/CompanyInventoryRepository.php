<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Models\CompanyInventoryProposal;
use Modules\Core\Models\CompanyInventoryLog;
use Modules\Core\Enums\InventoryLogActionEnum;

trait CompanyInventoryRepository
{
    /**
     * Store newly created resource.
     */
    public function storeCompanyInventory(array $data)
    {
        if ($inventories = array_filter($data, fn ($item) => CompanyInventory::create($item))) {
            $item = CompanyInventory::whereKd($data[0]['kd'])->first();
            Auth::user()->log('membuat ' . count($data) . ' inventaris baru ' . $item->name . ' <strong>[ID: ' . $item->id . ']</strong>', CompanyInventory::class, $item->id);
            return $item;
        };

        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyInventory(CompanyInventory $inventory, array $data)
    {
        if ($inventory->fill($data)->save()) {
            Auth::user()->log('memperbarui inventaris ' . $inventory->name . ' <strong>[ID: ' . $inventory->id . ']</strong>', CompanyInventory::class, $inventory->id);
            return $inventory;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyInventory(CompanyInventory $inventory)
    {
        if (!$inventory->trashed() && $inventory->delete()) {
            Auth::user()->log('menghapus inventaris ' . $inventory->name . ' <strong>[ID: ' . $inventory->id . ']</strong>', CompanyInventory::class, $inventory->id);
            return $inventory;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyInventory(CompanyInventory $inventory)
    {
        if ($inventory->trashed() && $inventory->restore()) {
            Auth::user()->log('memulihkan proposal ' . $inventory->name . ' <strong>[ID: ' . $inventory->id . ']</strong>', CompanyInventory::class, $inventory->id);
            return $inventory;
        }
        return false;
    }
}
