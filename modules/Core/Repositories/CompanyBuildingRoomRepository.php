<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyBuilding;
use Modules\Core\Models\CompanyBuildingRoom;

trait CompanyBuildingRoomRepository
{
    public function getCompanyBuildingRooms($request)
    {
        return $categories = CompanyBuildingRoom::whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->orderBy('name')
            ->paginate($request->get('limit', 10));
    }
    /**
     * Store newly created resource.
     */
    public function storeCompanyBuildingRoom(array $data)
    {
        $room = new CompanyBuildingRoom($data);
        if ($room->save()) {
            Auth::user()->log('membuat gedung baru ' . $room->name . ' <strong>[ID: ' . $room->id . ']</strong>', CompanyBuildingRoom::class, $room->id);
            return $room;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyBuildingRoom(CompanyBuildingRoom $room, array $data)
    {
        $room = $room->fill($data);
        if ($room->save()) {
            Auth::user()->log('memperbarui gedung ' . $room->name . ' <strong>[ID: ' . $room->id . ']</strong>', CompanyBuildingRoom::class, $room->id);
            return $room;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyBuildingRoom(CompanyBuildingRoom $room)
    {
        if (!$room->trashed() && $room->delete()) {
            Auth::user()->log('menghapus gedung ' . $room->name . ' <strong>[ID: ' . $room->id . ']</strong>', CompanyBuildingRoom::class, $room->id);
            return $room;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyBuildingRoom(CompanyBuildingRoom $room)
    {
        if ($room->trashed() && $room->restore()) {
            Auth::user()->log('memulihkan gedung ' . $room->name . ' <strong>[ID: ' . $room->id . ']</strong>', CompanyBuildingRoom::class, $room->id);
            return $room;
        }
        return false;
    }
}
