<?php

namespace Modules\Portal\Http\Controllers\API\Leave;

use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Leave\Submission\StoreRequest;
use Modules\Portal\Notifications\Leave\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Leave\Submission\CanceledNotification;

class SubmissionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$employee = $request->user()->employee;

		$leaves = $employee->leaves()->withTrashed()->with('approvables.userable.position')
			->search($request->get('search'))->whenPeriod($request->get('start_at'), $request->get('end_at'))
			->latest()->paginate($request->get('limit', 10));

		$leaves_count = $employee->leaves()->where('dates->[*]->d', 'like', '%' . date('Y') . '%')->whereApproved()->count();

		return response()->success([
			'message' => 'Berikut adalah daftar izin berdasarkan kueri Anda.',
			'leaves' => $leaves,
			'leaves_count' => $leaves_count
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreRequest $request)
	{
		$employee = $request->user()->employee;

		$leave = $employee->leaves()->create($request->transform());

		// Assign approvable based approvable_steps configuration
		foreach (config('modules.core.features.services.leaves.approvable_steps', []) as $model) {
			if ($model['type'] == 'parent_position_level') {
				if ($position = $employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {
					if (in_array($employee->position?->position?->level?->value, $model['only'])) {
						$leave->createApprovable($position);
					}
				}
			}
		}

		// Handle notifications
		if ($approvable = $leave->approvables()->orderBy('level')->first()) {
			$approvable->userable->getUser()->notify(new SubmissionNotification($leave, null));
		}

		return redirect()->route('portal::leave.submission.index')->with('success', isset($position) ? 'Pengajuan izin sudah terkirim, silakan tunggu notifikasi selanjutnya dari atasan!' : 'Pengajuan sudah tersimpan dan sudah disetujui otomatis oleh sistem, terima kasih!');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(EmployeeLeave $leave, Request $request)
	{
		$user = $request->user();
		$employee = $user->employee;

		$leave = $leave->load('approvables.userable.position');

		return view('portal::leave.submission.show', compact('user', 'employee', 'leave'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(EmployeeLeave $leave)
	{
		$leave->delete();

		// Handle notifications
		if ($approvable = $leave->approvables()->whereNotIn('result', [ApprovableResultEnum::PENDING])->orderBy('level')->first()) {
			$approvable->userable->employee->user->notify(new CanceledNotification($leave));
		}

		return redirect()->route('portal::leave.submission.index')->with('success', 'Pengajuan telah dibatalkan dan kami telah mengirim notifikasi ke atasan!');
	}
}
