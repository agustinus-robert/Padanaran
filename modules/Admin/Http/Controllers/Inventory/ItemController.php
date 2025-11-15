<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Auth;
use Carbon\Carbon;
use PDF;
use Str;
use Storage;
use Illuminate\Http\Request;
use Modules\Admin\Models\Inventory;
use Modules\Asset\Http\Requests\Inventory\Item\StoreRequest;
use Modules\Asset\Http\Requests\Inventory\Item\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\InventoryLogActionEnum;
use Modules\Core\Enums\InventoryRegistrationTypeEnum;
use Modules\Core\Enums\InventoryTypeEnum;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Asset\Http\Requests\Inventory\Item\Condition\UpdateRequest as UpdateConditionRequest;
use Modules\Asset\Http\Requests\Inventory\Item\Attachment\UpdateRequest as UpdateAttachmentRequest;
use App\Enums\WorkLocationEnum;

class ItemController extends Controller
{
    // use CompanyInventoryRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin::inventories.items.index', [
            'locations' => collect(WorkLocationEnum::cases()),
            'items' => Inventory::with('placeable')
                // ->isOwned()
                ->isType($request->get('type'))
                ->findByEmployeeLocation($request->get('location'))
                ->whenTrashed($request->get('trash'))
                ->search($request->get('search'))
                ->searchCategory($request->get('category'))
                ->findInvNum($request->get('inv_num'))
                ->orderByDesc('id')
                ->paginate($request->get('limit', 10)),
            'item_count' => Inventory::count()
        ]);
    }

    /**
     * Create buildings
     * */
    public function create(Request $request)
    {
        $placeables = array_map(
            fn ($b) => (object) [
                'value' => $b->value,
                'label' => $b->label(),
                'relation' => ($relation = (new ($b->instance()))->with($b->relations())->where($b->conditions())->orderBy($b->order(), 'ASC')->get()),
                'getter' => $b->getter(),
                'data' =>  $relation->pluck($b->getter(), 'id')
            ],
            PlaceableTypeEnum::cases()
        );

        $employee = collect($placeables)->where('value', 3)->pluck('relation')->first();

        return view('asset::inventories.items.create', [
            'proposal'   => CompanyInventoryProposal::get(),
            'categories' => CompanyInventory::distinct()->get('category'),
            'brands'     => CompanyInventory::distinct()->get('brand'),
            'types'      => collect(InventoryTypeEnum::cases()),
            'conditions' => collect(InventoryConditionEnum::cases()),
            'registers'  => collect(InventoryRegistrationTypeEnum::cases()),
            'placeables' => $placeables,
            'employees'  => $employee,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($item = $this->storeCompanyInventory($request->transformed()->toArray())) {
            return redirect()->next()->with('success', $request->input('count') . ' inventaris baru <strong>' . $item->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Edit the specified resource.
     */
    public function edit(CompanyInventory $item)
    {
        $classifications = config('modules.asset.classifications');

        $result = [];
        foreach ($classifications as $classification) {
            foreach ($classification['groups'] ?? [['code' => '00', 'name' => null]] as $group) {
                foreach ($group['types'] ?? [['code' => '00', 'name' => null]] as $type) {
                    $result[] = [
                        'code' => implode('/', [$classification['code'], $group['code'], $type['code']]),
                        'name' => implode(' - ', array_filter([$classification['name'], $group['name'], $type['name']]))
                    ];
                }
            }
        }

        $placeables = array_map(
            fn ($b) => (object) [
                'value' => $b->value,
                'label' => $b->label(),
                'relation' => ($relation = (new ($b->instance()))->with($b->relations())->where($b->conditions())->get()),
                'getter' => $b->getter(),
                'data' =>  $relation->pluck($b->getter(), 'id')
            ],
            PlaceableTypeEnum::cases()
        );

        $employee = collect($placeables)->where('value', 3)->pluck('relation')->first();

        return view('asset::inventories.items.edit', [
            'item'       => $item,
            'categories' => $result,
            'brands'     => CompanyInventory::distinct()->get('brand'),
            'types'      => collect(InventoryTypeEnum::cases()),
            'conditions' => collect(InventoryConditionEnum::cases()),
            'registers'  => collect(InventoryRegistrationTypeEnum::cases()),
            'placeables' => $placeables,
            'employees'  => $employee,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyInventory $item, Request $request)
    {
        return view('asset::inventories.items.show', [
            'item' => $item,
            'logs' => $item->logs()->orderByDesc('id')->paginate($request->get('limit', 10)),
            'conditions' => collect(InventoryConditionEnum::cases())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyInventory $item, UpdateRequest $request)
    {
        if ($item = $this->updateCompanyInventory($item, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Inventaris dengan nama <strong>' . $item->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyInventory $item)
    {
        $this->authorize('destroy', $item);

        if ($item = $this->destroyCompanyInventory($item)) {

            return redirect()->next()->with('success', 'Inventaris dengan nama <strong>' . $item->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyInventory $item)
    {
        $this->authorize('restore', $item);

        if ($item = $this->restoreCompanyInventory($item)) {

            return redirect()->next()->with('success', 'Inventaris dengan nama <strong>' . $item->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }

    public function condition(UpdateConditionRequest $request, CompanyInventory $item)
    {
        $item->fill(['condition' => $request->transformed()->toArray()['condition']]);
        if ($item->save()) {
            $item->log(
                $item->id,
                InventoryLogActionEnum::OTHER->value,
                $label = Auth::user()->name . ' menandai perangkat ' . $item->name . ' sebagai ' . InventoryConditionEnum::tryFrom($request->transformed()->toArray()['condition'])->label(),
                $label . ', Inventory ID: ' . $item->id . ', Keterangan: ' . $request->transformed()->toArray()['description'] ?: InventoryConditionEnum::tryFrom($request->transformed()->toArray()['condition'])->label()
            );
            return redirect()->back()->with('success', 'Terima kasih, Kondisi peralatan sudah diupdate!');
        }
        return redirect()->fail();
    }

    public function attachment(UpdateAttachmentRequest $request, CompanyInventory $item)
    {
        $item->fill($request->transformed()->toArray());

        if ($item->save()) {
            $item->log(
                $item->id,
                InventoryLogActionEnum::OTHER->value,
                $label = Auth::user()->name . ' memperbarui foto inventaris ' . $item->name,
                $label . ', Inventory ID: ' . $item->id
            );
            return redirect()->back()->with('success', 'Terima kasih, Foto peralatan sudah terupdate!');
        }
        return redirect()->back()->with('danger', 'Gagal, Terjadi kesalahan di server, harap hubungi admin!');
    }

    public function export(Request $request)
    {
        $inventories = CompanyInventory::with('placeable', 'user', 'pic')
            ->isOwned()
            ->isType($request->get('type'))
            ->findByEmployeeLocation($request->get('location'))
            ->whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->orderByDesc('id')
            ->get()
            ->mapToGroups(fn ($item) => [$item->type->label() => $item]);

        foreach ($inventories as $key => $value) {
            $sheet[$key] = [
                'columns' => [
                    'number'    => 'No',
                    'inv_num'   => 'Nomor Inventaris',
                    'name'      => 'Nama Perangkat',
                    'brand'     => 'Merek',
                    'ctg_name'  => 'Kategori',
                    'location'  => 'Lokasi perangkat',
                    'condition' => 'Kondisi perangkat',
                    'bought_at' => 'Tanggal pembelian',
                    'bought_price' => 'Harga pembelian',
                    'user'      => 'Pengguna',
                    'pic'       => 'Penanggung jawab',
                    'usefull'   => 'Masa pakai',
                    'expired'   => 'Masa habis pakai',
                    'attachment' => 'Lampiran',
                ],
                'data' => $value->map(function ($item, $index) {
                    return [
                        'number'    => $index + 1 ?? '',
                        'inv_num'   => $item->meta?->inv_num ?? '',
                        'name'      => $item->name ?? '',
                        'brand'     => $item->brand ?? '',
                        'ctg_name'  => $item->meta?->ctg_name ?? '',
                        'location'  => $item->getPlaceableNameAttribute(),
                        'condition' => $item->condition->label() ?? '',
                        'bought_at' => $item->bought_at ? $item->bought_at->isoFormat('LL') : '',
                        'bought_price' => $item->bought_price ? number_format($item->bought_price, 2) : '',
                        'user'      => $item->user ? $item->user->name : '',
                        'pic'       => is_null($item->user->name) ? ($item->pic->name) : '',
                        'usefull'   => isset($item->meta->usefull) ? $item->meta->usefull . ' Bulan' : '',
                        'expired'   => isset($item->meta->usefull) ? Carbon::parse($item->bought_at)->addMonths($item->meta->usefull)->isoFormat('LL') : '',
                        'attachment' => isset($item->attachments->items) ? Storage::url($item->attachments->items[0]->url) : '',
                    ];
                }),
            ];
        }

        return response()->json([
            'title'     => ($title = 'Rekap Inventaris'),
            'subtitle'  => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file'      => Str::slug($title . '-' . time()),
            'sheets'    => $sheet
        ]);
    }

    public function print(CompanyInventory $item, Request $request)
    {
        $document = $item->load('pic', 'user', 'placeable')->firstOrCreateDocument(
            $title = 'Dokumen pencatatan inventaris - ' . $item->name . ' - ' . $item->created_at->getTimestamp(),
            $path = 'company/inventories/asset/' . $item->meta?->inv_num ?? Str::random(36) . '.pdf'
        );

        $price = round($item->bought_price, 2);
        $diff  = (int)$item->bought_price / (isset($item->meta->usefull) ? (int)$item->meta->usefull : 1);

        if (isset($item->meta->usefull)) {
            for ($i = 1; $i <= $item->meta->usefull; $i++) {
                $data[$i]['date'] = $item->bought_at->addMonth($i)->isoFormat('LL');
                $data[$i]['diff'] = round($diff, 2);
                $data[$i]['nom']  = round($price - ($diff * $i), 2);
            }
        }

        $days = $data ?? [];

        Storage::disk('docs')->put($document->path, PDF::loadView('asset::inventories.items.components.letter', compact('document', 'title', 'item', 'price', 'diff', 'days'))->output());

        return $document->show();
    }
}
