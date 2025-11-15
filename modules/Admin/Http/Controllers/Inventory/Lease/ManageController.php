<?php

namespace Modules\Admin\Http\Controllers\Inventory\Lease;

use Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Models\User;
use Modules\Core\Enums\BorrowableTypeEnum;
use Modules\Asset\Http\Requests\Inventory\Lease\StoreRequest;
use Modules\Asset\Http\Requests\Inventory\Lease\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Models\CompanyBorrow;
use Modules\HRMS\Models\Employee;
use Modules\Asset\Notifications\Inventory\Lease\SubmissionNotification;
use Modules\Asset\Notifications\Inventory\Lease\ApprovedNotification;
use Modules\Asset\Notifications\Inventory\Lease\RejectedNotification;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Core\Models\CompanyBorrowItem;
use Modules\Core\Models\CompanyInventory;

class ManageController extends Controller
{
    /**
     * List empty stock
     * */
    public function index(Request $request)
    {
        return view('asset::lease.manages.index', [
            'borrows'      => CompanyBorrow::with([
                'receiver',
                'items' => fn ($w) => $w->with('modelable', 'giver')
            ])
                ->search($request->get('search'))
                ->whenTrashed($request->get('trashed'))
                ->orderbyDesc('id')
                ->paginate($request->get('limit', 10)),
            'borrow_count' => CompanyBorrow::count(),
        ]);
    }

    /**
     * Create buildings
     * */
    public function create(Request $request)
    {
        return view('asset::lease.manages.create', [
            'borrowables' => array_map(
                fn ($b) => (object) [
                    'value' => $b->value,
                    'label' => $b->label(),
                    'items' => (new ($b->instance()))->with($b->relations())->whereJsonContains('meta->rentable', '1')->get(),
                ],
                BorrowableTypeEnum::cases()
            ),
            'employees'   => Employee::with('user')->get(),
            'approvers'   => Employee::whereIn('id', config('modules.asset.features.lease.approvers'))->get(),
            'self'        => Auth::user()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $borrow = new CompanyBorrow(Arr::only($request->transformed()->toArray(), ['title', 'receiver_id', 'meta']));

        if ($borrow->save()) {

            foreach ($request->transformed()->toArray()['items'] as $value) {
                foreach ($value as $_value) {
                    $borrow->items()->create($_value);
                }
                foreach (config('modules.asset.features.lease.approvable_steps') as $model) {
                    if ($model['type'] == 'parent_position_level') {
                        if ($approver = $borrow->receiver->employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {
                            $borrow->createApprovable($approver);
                        }
                    }
                    if ($model['type'] == 'giver_by_user') {
                        if ($approver = User::find($value[0]['giver_id'])) {
                            $borrow->createApprovable($approver);
                        }
                    }
                }
            }

            // Create system log
            Auth::user()->log('Mengajukan peminjaman sejumlah ' . count($request->transformed()->toArray()['items']) . ' perangkat kepada Perusahaan');

            // Handle notifications
            if ($approvable = $borrow->approvables()->orderBy('level')->first()) {
                $approvable->userable->empl_id
                    ? $approvable->userable->getUser()->notify(new SubmissionNotification($borrow))
                    : $approvable->userable->notify(new SubmissionNotification($borrow));
            }

            return redirect()->next()->with('success', 'Peminjaman inventaris sejumlah <strong>' . $borrow->items->count() . '</strong> telah berhasil ditambahkan.');
        }
        return redirect()->fail();
    }

    /**
     * Show a resource.
     */
    public function show(CompanyBorrow $manage)
    {
        return view('asset::lease.manages.show', [
            'results' => config('modules.asset.features.lease.approvable_enum_available'),
            'borrow'  => $manage->load('items')
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
                    $data[$k] = $item->forceFill(['meta->onBorrow' => "1", 'meta->returned' => "0"])->save();
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
    public function revert(CompanyBorrow $manage, CompanyBorrowItem $item)
    {
        if ($manage) {
            $item->forceFill(['meta->onBorrow' => "0", 'meta->returned' => "1"]);
            if ($item->save()) {
                return redirect()->next()->with('success', 'Inventaris telah dikembalikan oleh <strong>' . $manage->receiver->name . '</strong>, Terima kasih.');
            }
            return redirect()->fail();
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
                        $data[$k] = $item->forceFill(['meta->onBorrow' => "0", 'meta->returned' => "1"])->save();
                    }
                }
            }
            return redirect()->next()->with('success', 'Inventaris <strong>' . $manage->receiver->name . '</strong> telah dikembalikan.');
        }
        return redirect()->fail();
    }
}
