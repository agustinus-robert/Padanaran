<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Floor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait FloorRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'building_id','information','name'
    ];

    /**
     * Store newly created resource.
     */
    public function storeFloor(array $data)
    {
        $floor = new Floor(Arr::only($data, $this->keys));
        if ($floor->save()) {
            return true;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateFloor(array $data, $id)
    {
        $floor = Floor::find($id);
        if ($floor->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyFloor($id)
    {
        if (Floor::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreFloor($id)
    {
        if (Floor::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
