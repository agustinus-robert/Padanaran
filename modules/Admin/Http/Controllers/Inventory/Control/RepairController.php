<?php

namespace Modules\Admin\Http\Controllers\Inventory\Control;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\InventoryLogActionEnum;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyInventoryRepository;
use Modules\Core\Repositories\CompanyInventoryLogRepository;
use Modules\Asset\Http\Requests\Inventory\Control\Repair\UpdateRequest;
use Modules\Asset\Http\Requests\Inventory\Control\Repair\StoreRequest;
use Modules\Core\Models\CompanyInventoryLog;

class RepairController extends Controller
{
    // use CompanyInventoryRepository, CompanyInventoryLogRepository;

    /**
     * List empty stock
     * */
    public function index(Request $request)
    {
        $repairs = CompanyInventoryLog::with('inventory.placeable')->where('action', InventoryLogActionEnum::REPAIR)
            ->search($request->get('search'))
            ->orderByDesc('id')
            ->paginate($request->get('limit', 10));

        return view('asset::inventories.controls.repairs.index', [
            'repairs' => $repairs,
        ]);
    }

    /**
     * Create repairs
     * */
    public function create(Request $request)
    {
        return view('asset::inventories.controls.repairs.create', [
            'items'      => CompanyInventory::with('placeable')->get(),
            'conditions' => collect(InventoryConditionEnum::cases()),
            'placeables' => PlaceableTypeEnum::cases(),
            'actions'    => collect([InventoryLogActionEnum::REPAIR, InventoryLogActionEnum::REFURBISH])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->transformed()->toArray();

        if ($inventory = $this->updateCompanyInventory($data['inventory'], \Arr::only($data, ['name', 'placeable_type', 'placeable_id', 'condition']))) {
            if ($log = $this->storeCompanyInventoryLog($inventory, InventoryLogActionEnum::tryFrom((int) $data['action']), $data['description'], $data['meta'])) {
                return redirect()->next()->with('success', 'Anda telah ' . (InventoryLogActionEnum::tryFrom((int) $data['action']))->message() . '<strong> ' . $inventory->name . '</strong>, terima kasih.');
            }
            return redirect()->fail();
        }
        return redirect()->fail();
    }



    /**
     * Create buildings
     * */
    public function show(CompanyInventoryLog $item)
    {
        return view('asset::inventories.controls.repairs.show', [
            'conditions' => collect(InventoryConditionEnum::cases()),
            'placeables' => PlaceableTypeEnum::cases(),
            'item'       => $item->load('inventory.placeable'),
            'actions'    => collect([InventoryLogActionEnum::REPAIR, InventoryLogActionEnum::REFURBISH])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(UpdateRequest $request, CompanyInventoryLog $item)
    {
        $data = $request->transformed()->toArray();

        if ($inventory = $this->updateCompanyInventory($data['inventory'], \Arr::only($data, ['name', 'placeable_type', 'placeable_id', 'condition']))) {
            if ($log = $this->updateCompanyInventoryActionLog($item, InventoryLogActionEnum::tryFrom((int) $data['action']), $data['description'], $data['meta'])) {
                return redirect()->next()->with('success', 'Anda telah ' . (InventoryLogActionEnum::tryFrom((int) $data['action']))->message() . '<strong> ' . $inventory->name . '</strong>, terima kasih.');
            }
        }
        return redirect()->fail();
    }
}
