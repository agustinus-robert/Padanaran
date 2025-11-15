<?php

namespace Modules\Admin\Http\Controllers\Inventory\Control;

use Illuminate\Http\Request;
use Modules\Asset\Http\Requests\Inventory\Control\Restock\UpdateRequest;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\InventoryLogActionEnum;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyInventoryRepository;
use Modules\Core\Repositories\CompanyInventoryLogRepository;

class RestockController extends Controller
{
    // use CompanyInventoryRepository, CompanyInventoryLogRepository;

    /**
     * List empty stock
     * */
    public function index(Request $request)
    {
        return view('asset::inventories.controls.restocks.index', [
            'items'   => CompanyInventory::whereJsonContains('meta->fillable', '1')->with('placeable')
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
            'item'       => $item->load('placeable'),
            'actions'    => collect([InventoryLogActionEnum::RESTOCK, InventoryLogActionEnum::REFILL])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(CompanyInventory $item, UpdateRequest $request)
    {
        if ($inventory = $this->updateCompanyInventory($item, $request->transformed()->toArray())) {
            if ($log = $this->storeCompanyInventoryLog($inventory, InventoryLogActionEnum::tryFrom((int) $request->transformed()->toArray()['action']), \Arr::only($request->transformed()->toArray(), ['description']))) {
                return redirect()->next()->with('success', 'Anda telah ' . (InventoryLogActionEnum::tryFrom((int) $request->transformed()->toArray()['action']))->message() . '<strong> ' . $inventory->name . '</strong>, terima kasih.');
            }
        }
        return redirect()->fail();
    }
}
