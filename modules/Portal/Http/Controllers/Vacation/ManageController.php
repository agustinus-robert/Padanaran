<?php

namespace Modules\Portal\Http\Controllers\Vacation;

use Illuminate\Http\Request;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Vacation\Manage\UpdateRequest;
use Modules\Portal\Notifications\Vacation\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Vacation\Manage\ApprovedNotification;
use Modules\Portal\Notifications\Vacation\Manage\RejectedNotification;
use Modules\Portal\Notifications\Vacation\Manage\AskForRevisionNotification;
use Modules\Portal\Notifications\Vacation\Cancelation\ApprovedNotification as CancelationApprovedNotification;
use Modules\Portal\Notifications\Vacation\Cancelation\RejectedNotification as CancelationRejectedNotification;

class ManageController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$user = $request->user();
		$employee = $user->employee->load('position.position.children');

		$vacations = EmployeeVacation::with('approvables.userable.position', 'quota.employee.user')
			->whereHas('quota.employee.position', fn ($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
			->whenOnlyPending($request->get('pending'))
			->search($request->get('search'))
			->latest()
			->paginate($request->get('limit', 10));

		$pending_vacations_count = EmployeeVacation::whereHas('quota.employee.position', fn ($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
			->whenOnlyPending(true)
			->count();

		return view('portal::vacation.manage.index', compact('user', 'employee', 'vacations', 'pending_vacations_count'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(EmployeeVacation $vacation, Request $request)
	{
		$user = $request->user();
		$employee = $user->employee;

		$results = ApprovableResultEnum::cases();

		if (isset(collect($vacation->dates)->first()['cashable'])) {
			unset($results[array_search(ApprovableResultEnum::REVISION, $results)]);
		}

		$vacation = $vacation->load('approvables.userable.position');

		return view('portal::vacation.manage.show', compact('user', 'employee', 'vacation', 'results'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(CompanyApprovable $approvable, UpdateRequest $request)
	{
		$approvable->update($request->transformed()->toArray());

		// Handle notifications
		if ($approvable->cancelable) {
			$approvable->modelable->approvables()->update($request->transformed()->only('result'));

			if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
				$approvable->modelable->update([
					'dates' => $approvable->modelable->dates->filter(fn ($d) => empty($d['c']))
				]);
				$approvable->modelable->quota->employee->user->notify(new CancelationApprovedNotification($approvable->modelable));
			}

			if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
				$approvable->modelable->update([
					'dates' => $approvable->modelable->dates->map(function ($date) {
						$date['c'] = false;
						return array_filter($date);
					})
				]);
				$approvable->modelable->quota->employee->user->notify(new CancelationRejectedNotification($approvable->modelable));
			}
		} else {
			if ($request->input('result') == ApprovableResultEnum::APPROVE->value) {
				$approvable->modelable->quota->employee->user->notify(new ApprovedNotification($approvable->modelable, $approvable, isset(collect($approvable->modelable->dates)->first()['cashable'])));
				if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn ($a) => $a->level > $approvable->level)->first()) {
					$superior->userable->employee->user->notify(new SubmissionNotification($approvable->modelable, false, $approvable->userable));
				}
			}

			if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
				$approvable->modelable->quota->employee->user->notify(new RejectedNotification($approvable->modelable, $approvable, isset(collect($approvable->modelable->dates)->first()['cashable'])));
			}

			if ($request->input('result') == ApprovableResultEnum::REVISION->value) {
				$approvable->modelable->quota->employee->user->notify(new AskForRevisionNotification($approvable->modelable, $approvable));
			}
		}

		return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
	}
}
