<?php

namespace Modules\Admin\Http\Controllers\Inventory\Control;

use Modules\Admin\Http\Controllers\Controller;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Asset\Http\Requests\Inventory\Control\Approval\UpdateRequest;
use Modules\Asset\Notifications\Inventory\Proposal\SubmissionNotification;
use Modules\Asset\Notifications\Inventory\Proposal\ApprovedNotification;
use Modules\Asset\Notifications\Inventory\Proposal\RejectedNotification;

class ApprovalController extends Controller
{
    /**
     * Update a newly created resource in storage.
     */
    public function update(CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable->update($request->transformed()->toArray());
        // return $approvable->modelable;
        // Handle notifications
        if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
            $approvable->modelable->user->notify(new ApprovedNotification($approvable->modelable, $approvable));
            if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn ($a) => $a->level > $approvable->level)->first()) {
                $superior->userable->employee->user->notify(new SubmissionNotification($approvable->modelable));
            }
        }

        if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
            $approvable->modelable->user->notify(new RejectedNotification($approvable->modelable, $approvable));
        }

        return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }
}
