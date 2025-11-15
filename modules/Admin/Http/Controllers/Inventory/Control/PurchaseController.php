<?php

namespace Modules\Admin\Http\Controllers\Inventory\Control;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Models\CompanyInventoryProposal;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Enums\InventoryLogActionEnum;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Asset\Http\Requests\Inventory\Control\Purchase\StoreRequest;
use Modules\Asset\Http\Requests\Inventory\Control\Purchase\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyInventoryRepository;
use Modules\Core\Repositories\CompanyInventoryLogRepository;

class PurchaseController extends Controller
{
    // use CompanyInventoryRepository, CompanyInventoryLogRepository;

    /**
     * Display all resource
     * */
    public function index(Request $request)
    {
        return view('asset::inventories.controls.purchases.index', [
            'items' => CompanyInventory::with('placeable')
                ->whereNull('bought_at')
                ->search($request->get('search'))
                ->orderBy('name')
                ->paginate($request->get('limit', 10)),
        ]);
    }
    /**
     * Create item
     * */
    public function create(Request $request)
    {
        return view('asset::inventories.controls.purchases.create', [
            'conditions' => collect(InventoryConditionEnum::cases()),
            'proposal'   => CompanyInventoryProposal::with('items.placeable')->find($request->get('purcase')),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $logs = [];
        foreach ($request->transformed()->toArray() as $id => $inputs) {
            if ($inventory = $this->updateCompanyInventory(CompanyInventory::find($id), $inputs)) {
                $logs[$inventory->id] = ($log = $this->storeCompanyInventoryLog($inventory, InventoryLogActionEnum::BUY, \Arr::only($inputs, ['description'])));
            }
        }
        return redirect()->next()->with([
            'success' => 'Pembelian ' . count(array_filter($logs)) . ' dari total ' . count($request->transformed()->toArray()) . ' inventaris telah berhasil dibuat.',
            'danger' => count($errors = array_filter($logs, fn ($log) => $log == false)) ? 'Terjadi kesalahan ketika memproses data, silakan hubungi administrator, terima kasih. [ID Gagal: ' . implode(',', array_keys($errors)) . ']' : null
        ]);
    }

    /**
     * show item
     * */
    public function show(CompanyInventory $item)
    {
        return view('asset::inventories.controls.purchases.show', [
            'placeables' => PlaceableTypeEnum::cases(),
            'item' => $item->load('placeable'),
        ]);
    }

    /**
     * Update item.
     */
    public function update(CompanyInventory $item, UpdateRequest $request)
    {
        if ($inventory = $this->updateCompanyInventory($item, $request->transformed()->toArray())) {
            if ($log = $this->storeCompanyInventoryLog($inventory, InventoryLogActionEnum::BUY, \Arr::only($request->transformed()->toArray(), ['description']))) {
                return redirect()->next()->with('success', 'Pembelian inventaris <strong>' . $inventory->name . '</strong> telah berhasil dibuat.');
            }
        }
        return redirect()->fail();
    }
}
