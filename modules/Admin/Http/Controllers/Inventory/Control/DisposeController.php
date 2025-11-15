<?php

namespace Modules\Admin\Http\Controllers\Inventory\Control;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Asset\Http\Requests\Inventory\purcase\StoreRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyInventoryRepository;

class DisposeController extends Controller
{
    // use CompanyInventoryRepository;

    /**
     * List empty stock
     * */
    public function index(Request $request)
    {
        return view('asset::inventories.controls.restocks.index', [
            'items'   => CompanyInventory::with('placeable')
                ->isOwned()
                ->search($request->get('search'))
                ->orderBy('name')
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Create buildings
     * */
    public function show(CompanyInventory $item)
    {
        return view('asset::inventories.controls.restocks.show', [
            'placeables' => PlaceableTypeEnum::cases(),
            'item' => $item->load('placeable'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($purcase = $this->storeCompanyInventoryPurcase($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Penambahan stock inventaris <strong>' . $purcase->name . '</strong> telah berhasil ditambahkan.');
        }
        return redirect()->fail();
    }
}
