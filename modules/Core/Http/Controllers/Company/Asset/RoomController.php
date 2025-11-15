<?php

namespace Modules\Core\Http\Controllers\Company\Asset;

use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyBuilding;
use Modules\Core\Models\CompanyBuildingRoom;
use Modules\Core\Http\Requests\Company\Asset\Room\StoreRequest;
use Modules\Core\Http\Requests\Company\Asset\Room\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\CompanyApprovable;
use Modules\Core\Repositories\CompanyBuildingRoomRepository;
use Modules\HRMS\Models\EmployeeOutwork;

class RoomController extends Controller
{
    use CompanyBuildingRoomRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyBuildingRoom::class);

        return view('core::company.assets.rooms.index', [
            'rooms' => $this->getCompanyBuildingRooms($request),
        ]);
    }

    /**
     * Create buildings
     * */
    public function create(Request $request)
    {
        return view('core::company.assets.rooms.create', [
            'buildings' => CompanyBuilding::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($room = $this->storeCompanyBuildingRoom($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Ruang <strong>' . $room->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyBuildingRoom $room)
    {
        $this->authorize('update', $room);

        return view('core::company.assets.rooms.edit', [
            'room'      => $room,
            'buildings' => CompanyBuilding::get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyBuildingRoom $room, UpdateRequest $request)
    {
        if ($room = $this->updateCompanyBuildingRoom($room, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Gedung dengan nama <strong>' . $room->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyBuildingRoom $room)
    {
        $this->authorize('destroy', $room);

        if ($room = $this->destroyCompanyBuildingRoom($room)) {

            return redirect()->next()->with('success', 'Ruang dengan nama <strong>' . $room->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyBuildingRoom $room)
    {
        $this->authorize('restore', $room);

        if ($room = $this->restoreCompanyBuildingRoom($room)) {

            return redirect()->next()->with('success', 'Ruang dengan nama <strong>' . $room->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}
