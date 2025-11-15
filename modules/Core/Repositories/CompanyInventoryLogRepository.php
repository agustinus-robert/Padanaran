<?php

namespace Modules\Core\Repositories;

use Auth;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Models\CompanyInventoryLog;

trait CompanyInventoryLogRepository
{
    /**
     * Store inventory logs
     * */
    public function storeCompanyInventoryLog(CompanyInventory $inventory, $action, $data)
    {
        if ($log = $inventory->logs()->create([
            'action'        => $action,
            'label'         => Auth::user()->name . ' ' . $action->message() . ' ' . $inventory->name,
            'description'   => Auth::user()->name . ' ' . $action->message() . ' ' . $inventory->name . ', ID Inventaris: ' . $inventory->id . ', Keterangan: ' . $data
        ])) {

            Auth::user()->log($action->message() . $inventory->name . ' <strong>[ID: ' . $inventory->id . ']</strong>', CompanyInventoryLog::class, $log->id);

            return $log;
        }
        return false;
    }

    /**
     * Store inventory logs
     * */
    public function updateCompanyInventoryLog(CompanyInventory $inventory, $action, $data)
    {
        $log = $inventory->logs()->updateOrCreate(
            ['inventory_id'  => $inventory->id, 'action' => $action],
            [
                'label'         => Auth::user()->name . ' ' . $action->message() . ' ' . $inventory->name,
                'description'   => Auth::user()->name . ' ' . $action->message() . ' ' . $inventory->name . ', ID Inventaris: ' . $inventory->id . ', Keterangan: ' . $data
            ]
        );
        if ($log) {
            Auth::user()->log($action->message() . $inventory->name . ' <strong>[ID: ' . $inventory->id . ']</strong>', CompanyInventoryLog::class, $log->id);
            return $log;
        }
        return false;
    }
}
