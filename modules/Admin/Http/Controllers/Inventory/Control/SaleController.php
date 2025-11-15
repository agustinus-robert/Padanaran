<?php

namespace Modules\Admin\Http\Controllers\Inventory\Control;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Enums\InventoryLogActionEnum;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Asset\Http\Requests\Inventory\Control\Sale\StoreRequest;
use Modules\Asset\Http\Requests\Inventory\Control\Sale\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Models\CompanyInventoryControl;
use Modules\Core\Models\CompanyInventoryLog;
use Modules\Core\Repositories\CompanyInventoryRepository;
use Modules\Core\Repositories\CompanyInventoryLogRepository;

class SaleController extends Controller
{
    // use CompanyInventoryRepository, CompanyInventoryLogRepository;

    /**
     * Display all resource
     * */
    public function index(Request $request)
    {
        return view('asset::inventories.controls.sales.index', [
            'items' => CompanyInventoryControl::search($request->get('search'))
                ->orderByDesc('id')
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Create buildings
     * */
    public function create(Request $request)
    {
        return view('asset::inventories.controls.sales.create', [
            'items' => CompanyInventory::with('placeable', 'logs')
                ->isOwned()
                ->search($request->get('search'))
                ->orderByDesc('id')
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $inventory = CompanyInventoryControl::create($request->transformed()->toArray());

        foreach ($inventory->meta->items as $item) {
            $inv = CompanyInventory::find($item->inventory_id);
            $inv->fill(['sold_at' => now()]);
            $inv->save();
            $this->storeCompanyInventoryLog($inv, InventoryLogActionEnum::SELL, $request->input('description'));
        }
        return redirect()->next()->with('success', 'Anda telah ' . (InventoryLogActionEnum::SELL)->message() . '<strong> ' . count($inventory->meta->items) . ' inventaris</strong>, terima kasih.');
    }

    public function show(CompanyInventoryControl $sale, Request $request)
    {
        return view('asset::inventories.controls.sales.show', [
            'conditions' => collect(InventoryConditionEnum::cases()),
            'placeables' => PlaceableTypeEnum::cases(),
            'sale'       => $sale,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(CompanyInventoryControl $sale, UpdateRequest $request)
    {
        $sale->fill($request->transformed()->toArray());
        if ($sale->save()) {
            foreach ($sale->meta->items as $item) {
                $inv = CompanyInventory::find($item->inventory_id);
                $inv->fill(['sold_at' => now()]);
                $inv->save();
                $this->updateCompanyInventoryLog($inv, InventoryLogActionEnum::SELL, $request->input('description'));
            }
            return redirect()->next()->with('success', 'Anda telah ' . (InventoryLogActionEnum::SELL)->message() . ' <strong> ' . count($sale->meta->items) . ' inventaris </strong>, terima kasih.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyInventoryControl $sale, Request $request)
    {
        $tmp = $sale;
        foreach ($tmp->meta->items as $key => $item) {
            CompanyInventory::find($item->inventory_id)->update(['sold_at' => null]);
            CompanyInventoryLog::whereInventoryId($item->inventory_id)->whereAction(InventoryLogActionEnum::SELL->value)->first()->delete();
        }

        if ($sale->delete()) {
            return redirect()->next()->with('success', 'Anda telah membatalkan penjualan <strong>' . $sale->name . '</strong>.');
            Auth::user()->log(' membatalkan penjualan <strong>[ID: ' . $sale->name . ']</strong>', CompanyInventoryControl::class, $sale->id);
        }
        return redirect()->fail();
    }
}
