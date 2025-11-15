<?php

namespace Modules\Core\Http\Controllers\Company\Asset;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyBuilding;
use Modules\Core\Http\Requests\Company\Asset\Building\StoreRequest;
use Modules\Core\Http\Requests\Company\Asset\Building\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyBuildingRepository;

class BuildingController extends Controller
{
    use CompanyBuildingRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyBuilding::class);

        return view('core::company.assets.buildings.index', [
            'buildings' => $this->getCompanyBuildings($request),
        ]);
    }

    /**
     * Create buildings
     * */
    public function create(Request $request)
    {
        return view('core::company.assets.buildings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if($building = $this->storeCompanyBuilding($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Gedung <strong>'.$building->name.'</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyBuilding $building)
    {
        $this->authorize('update', $building);

        return view('core::company.assets.buildings.edit', [
            'building'  => $building,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyBuilding $building, UpdateRequest $request)
    {
        if($building = $this->updateCompanyBuilding($building, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Gedung dengan nama <strong>'.$building->name.'</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyBuilding $building)
    {
        $this->authorize('destroy', $building);

        if($building = $this->destroyCompanyBuilding($building)) {

            return redirect()->next()->with('success', 'Gedung dengan nama <strong>'.$building->name.'</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyBuilding $building)
    {
        $this->authorize('restore', $building);

        if($building = $this->restoreCompanyBuilding($building)) {

            return redirect()->next()->with('success', 'Gedung dengan nama <strong>'.$building->name.'</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}