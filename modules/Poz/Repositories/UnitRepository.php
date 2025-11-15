<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Unit;

trait UnitRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'code',
        'name'
    ];

    /**
     * Store newly created resource.
     */
    public function storeUnit(array $data)
    {
        $unit = new Unit(Arr::only($data, $this->keys));
        if ($unit->save()) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $unit->outlets()->attach($outletId);
            }

            return true;
        }

        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateUnit(array $data, $id)
    {
        $unit = Unit::find($id);
        if ($unit->update(Arr::only($data, $this->keys))) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $unit->outlets()->syncWithoutDetaching($outletId);
            }
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyUnit($id)
    {
        if (Unit::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreUnit($id)
    {
        if (Unit::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
