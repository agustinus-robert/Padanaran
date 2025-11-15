<?php

namespace Modules\Portal\Http\Controllers\Leave;

use Illuminate\Http\Request;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Leave\Manage\UpdateRequest;
use Modules\Portal\Notifications\Leave\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Leave\Manage\ApprovedNotification;
use Modules\Portal\Notifications\Leave\Manage\RejectedNotification;

class ManageController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$user = $request->user();
		$employee = $user->employee->load('position.position.children');

		$leaves = EmployeeLeave::with('approvables.userable.position', 'employee.user')
			->whereHas('employee.position', fn ($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
			->whenOnlyPending($request->get('pending'))
			->search($request->get('search'))
			->latest()
			->paginate($request->get('limit', 10));

		$pending_leaves_count = EmployeeLeave::whereHas('employee.position', fn ($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
			->whenOnlyPending(true)
			->count();

		return view('portal::leave.manage.index', compact('user', 'employee', 'leaves', 'pending_leaves_count'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(EmployeeLeave $leave, Request $request)
	{
		$user = $request->user();
		$employee = $user->employee;
		$results = config('modules.core.features.services.leaves.approvable_enum_available');

		$leave = $leave->load('approvables.userable.position');


		return view('portal::leave.manage.show', compact('user', 'employee', 'leave', 'results'));
	}

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
