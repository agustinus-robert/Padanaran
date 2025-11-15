<?php

namespace Modules\Admin\Http\Controllers\Inventory\Lease;

use Illuminate\Http\Request;
use Modules\Asset\Http\Requests\Inventory\Lease\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Models\CompanyBorrow;
use Modules\Asset\Notifications\Inventory\Lease\SubmissionNotification;
use Modules\Asset\Notifications\Inventory\Lease\ApprovedNotification;
use Modules\Asset\Notifications\Inventory\Lease\RejectedNotification;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Core\Models\CompanyBorrowItem;
use Modules\Core\Models\CompanyInventory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ItemController extends Controller
{
    /**
     * List empty stock
     * */
    public function index(Request $request)
    {
        $data = CompanyBorrowItem::whereJsonContains('meta->onBorrow', '1')
            ->search($request->get('search'))
            ->whenTrashed($request->get('trashed'))
            ->orderbyDesc('id')
            ->get()
            ->groupBy('borrow_id')->flatten()->unique('modelable_id');

        $page = Paginator::resolveCurrentPage('page');
        $items = new LengthAwarePaginator($data->forPage($page, $request->get('limit', 10)), count($data), $request->get('limit', 10), $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        return view('asset::lease.items.index', [
            'items' => $items,
            'item_count' => CompanyBorrowItem::whereJsonContains('meta->onBorrow', '1')->count(),
        ]);
    }

    /**
     * Show a resource.
     */
    public function show(Request $request, CompanyBorrow $borrow)
    {
        $borrow = $borrow->load('items.modelable');
        $items = $borrow->items->where('modelable_type', CompanyInventory::class)->where('modelable_id', $request->get('item'));
        $returned = $borrow->items()->where('modelable_type', CompanyInventory::class)->where('modelable_id', $request->get('item'))->whereJsonContains('meta->returned', '1')->get();

        return view('asset::lease.items.show', [
            'borrow' => $borrow,
            'items' => $items,
            'returned' => $returned
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable->update($request->transformed()->toArray());

        // Handle notifications
        if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {

            foreach ($approvable->modelable->items as $k => $item) {
                if ($item->modelable_type == CompanyInventory::class) {
                    $data[$k] = $item->modelable->forceFill(['meta->rentable' => "0"])->save();
                }
            }

            $approvable->modelable->receiver->notify(new ApprovedNotification($approvable->modelable, $approvable));

            if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn ($a) => $a->level > $approvable->level)->first()) {
                $superior->userable->empl_id
                    ? $superior->userable->getUser()->notify(new SubmissionNotification($approvable->modelable))
                    : $superior->userable->notify(new SubmissionNotification($approvable->modelable));
            }
        }

        if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
            $approvable->modelable->receiver->notify(new RejectedNotification($approvable->modelable, $approvable));
        }

        return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }

    /**
     * Destroy a resource.
     */
    public function destroy(CompanyBorrow $manage)
    {
        if ($manage->delete()) {
            return redirect()->next()->with('success', 'Peminjaman inventaris oleh <strong>' . $manage->receiver->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Destroy a resource.
     */
    public function restore(CompanyBorrow $manage)
    {
        if ($manage->restore()) {
            return redirect()->next()->with('success', 'Peminjaman inventaris oleh <strong>' . $manage->receiver->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }

    /**
     * Kill a resource.
     */
    public function kill(CompanyBorrow $manage)
    {
        if ($manage->forcedelete()) {
            return redirect()->next()->with('success', 'Peminjaman inventaris oleh <strong>' . $manage->receiver->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * revert a resource.
     */
    public function revert(CompanyBorrow $manage)
    {
        if ($manage->forcedelete()) {
            return redirect()->next()->with('success', 'Peminjaman inventaris oleh <strong>' . $manage->receiver->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    public function revertAll(CompanyBorrow $manage)
    {
        if ($manage->forceFill(['meta->returned' => "1"])->save()) {
            foreach ($manage->items as $k => $item) {
                $item->update(['returned_at' => now()]);
                if ($item->save()) {
                    if ($item->modelable_type == CompanyInventory::class) {
                        $data[$k] = $item->modelable->forceFill(['meta->rentable' => "1"])->save();
                    }
                }
            }
            return redirect()->next()->with('success', 'Inventaris <strong>' . $manage->receiver->name . '</strong> telah dikembalikan.');
        }
        return redirect()->fail();
    }
}
