<?php

namespace Modules\Portal\Http\Controllers\Schedule;

use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Leave\Manage\UpdateRequest;
use Modules\Portal\Notifications\Leave\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Leave\Manage\ApprovedNotification;
use Modules\Portal\Notifications\Leave\Manage\RejectedNotification;

class ApprovableController extends Controller
{
	/**
	 * Update the specified resource in storage.
	 */
	public function update(CompanyApprovable $approvable, UpdateRequest $request)
	{
		$approvable->update($request->transformed()->toArray());

		// Handle notifications
		if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
			$approvable->modelable->employee->user->notify(new ApprovedNotification($approvable->modelable, $approvable));
			if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn ($a) => $a->level > $approvable->level)->first()) {
				$superior->userable->employee->user->notify(new SubmissionNotification($approvable->modelable, $approvable->userable));
			}
		}

		if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
			$approvable->modelable->employee->user->notify(new RejectedNotification($approvable->modelable, $approvable));
		}

		return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
	}
}
