<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyBuilding;

trait CompanyBuildingRepository
{
    public function getCompanyBuildings($request)
    {
        return $categories = CompanyBuilding::whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->orderBy('name')
            ->paginate($request->get('limit', 10));
    }
    /**
     * Store newly created resource.
     */
    public function storeCompanyBuilding(array $data)
    {
        $building = new CompanyBuilding(Arr::only($data, ['kd', 'name', 'address_primary', 'address_secondary', 'address_city', 'state_id']));
        if ($building->save()) {
            Auth::user()->log('membuat gedung baru ' . $building->name . ' <strong>[ID: ' . $building->id . ']</strong>', CompanyBuilding::class, $building->id);
            return $building;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyBuilding(CompanyBuilding $building, array $data)
    {
        $building = $building->fill(Arr::only($data, ['kd', 'name', 'address_primary', 'address_secondary', 'address_city', 'state_id']));
        if ($building->save()) {
            Auth::user()->log('memperbarui gedung ' . $building->name . ' <strong>[ID: ' . $building->id . ']</strong>', CompanyBuilding::class, $building->id);
            return $building;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyBuilding(CompanyBuilding $building)
    {
        if (!$building->trashed() && $building->delete()) {
            Auth::user()->log('menghapus gedung ' . $building->name . ' <strong>[ID: ' . $building->id . ']</strong>', CompanyBuilding::class, $building->id);
            return $building;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyBuilding(CompanyBuilding $building)
    {
        if ($building->trashed() && $building->restore()) {
            Auth::user()->log('memulihkan gedung ' . $building->name . ' <strong>[ID: ' . $building->id . ']</strong>', CompanyBuilding::class, $building->id);
            return $building;
        }
        return false;
    }
}
