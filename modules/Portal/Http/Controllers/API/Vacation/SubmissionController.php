<?php

namespace Modules\Portal\Http\Controllers\API\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Vacation\Submission\StoreRequest;
use Modules\Portal\Notifications\Vacation\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Vacation\Submission\CanceledNotification;

class SubmissionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$employee = $request->user()->employee;
		$vacations = $employee->vacations()
			->withTrashed()
			->with('approvables.userable.position', 'quota.category')
			->search($request->get('search'))
			->whenPeriod($request->get('start_at'), $request->get('end_at'))
			->latest()
			->paginate($request->get('limit', 10));

		return response()->success([
			'message' => 'Berikut adalah daftar cuti berdasarkan kueri Anda.',
			'vacations' => $vacations
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreRequest $request)
	{
		$employee = $request->user()->employee;

		$vacation = $employee->vacations()->create($request->transformed()->toArray());

		// Assign approvable though employee positions
		foreach (config('modules.core.features.services.vacations.approvable_steps', []) as $model) {
			if ($model['type'] == 'parent_position_level') {
				if ($position = $employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {
					$vacation->createApprovable($position);
				}
			}
		}

		// Handle notifications
		if ($approvable = $vacation->approvables()->orderBy('level')->first()) {
			$approvable->userable->getUser()->notify(new SubmissionNotification($vacation, false, null));
		}

		return response()->success([
			'message' => 'Pengajuan kamu telah terekam di sistem.',
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(EmployeeVacation $vacation, Request $request)
	{
		$vacation = $vacation->load('approvables.userable.position');

		return response()->success([
			'message' => 'Berikut adalah daftar cuti berdasarkan kueri Anda.',
			'vacations' => $vacation
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(EmployeeVacation $vacation)
	{
		$vacation->delete();

		// Handle notifications
		if ($approvable = $vacation->approvables()->whereNotIn('result', [ApprovableResultEnum::PENDING])->orderBy('level')->first()) {
			$approvable->userable->employee->user->notify(new CanceledNotification($vacation));
		}

		return response()->success([
			'message' => 'Pengajuan telah dibatalkan dan kami telah mengirim notifikasi ke atasan!.',
		]);
	}
}
