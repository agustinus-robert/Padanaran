<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait RoomRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'name','price','building_id','floor_id','information'
    ];

    /**
     * Store newly created resource.
     */
    public function storeRoom(array $data)
    {
        $data['price'] = str_replace('.','', $data['price']);
        $room = new Room(Arr::only($data, $this->keys));
        if ($room->save()) {
            return true;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateRoom(array $data, $id)
    {
        $room = Room::find($id);
        $data['price'] = str_replace('.','', $data['price']);
        if ($room->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyRoom($id)
    {
        if (Room::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreRoom($id)
    {
        if (Room::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
