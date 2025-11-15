<?php

namespace Modules\Portal\Http\Controllers\Holiday;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Models\EmployeeHoliday;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Holiday\Submission\StoreRequest;
use Modules\Portal\Http\Requests\Holiday\Submission\UpdateRequest;
use Modules\Portal\Notifications\Holiday\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Holiday\Submission\RevisedNotification;
use Modules\Portal\Notifications\Holiday\Submission\CanceledNotification;

class SubmissionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$employee 	= $request->user()->employee;

		$holidays = $employee->holidays()
			->withTrashed()
			->with('approvables.userable.position')
			->search($request->get('search'))
			->whenPeriod($request->input('start_at'), $request->input('end_at'))
			->latest()
			->paginate($request->get('limit', 10));

		return view('portal::holidays.submission.index', compact('employee', 'holidays'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Request $request)
	{
		$employee 	= $request->user()->employee;
		$month 		= Carbon::parse($request->get('month', now()));
		$start_at 	= $month->copy()->startOfMonth()->addDays(20)->format('Y-m-d 01:00:01');
		$end_at 	= $month->copy()->endOfMonth()->addDays(20)->format('Y-m-d 23:59:59');

		return view('portal::holidays.submission.create', compact('employee', 'start_at', 'end_at'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreRequest $request)
	{
		$employee = $request->user()->employee;

		$holiday = $employee->holidays()->create($request->transformed()->toArray());

		// Assign approvable though employee positions
		foreach (config('modules.core.features.services.holidays.approvable_steps', []) as $model) {
			if ($model['type'] == 'parent_position_level') {
				if ($position = $employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {
					if (in_array($employee->position?->position?->level?->value, $model['only'])) {
						$holiday->createApprovable($position);
					}
				}
			}
			if ($model['type'] == 'current_position_level') {
				if ($position = $employee->position->position->firstWhere('level', $model['value'])?->employeePositions()->active()->first()) {
					if (in_array($employee->position?->position?->level?->value, $model['only'])) {
						$data = ['result' => $employee->position?->position?->level?->value == 1 ? 1 : 0];
						$holiday->createApprovable($position, $data);
					}
				}
			}
		}

		// return $position;

		// Handle notifications
		if ($approvable = $holiday->approvables()->orderBy('level')->first()) {
			$approvable->userable->getUser()->notify(new SubmissionNotification($holiday, null));
		}

		return redirect()->route('portal::holiday.submission.index')->with('success', isset($position) ? 'Pengajuan hari libur sudah terkirim, silakan tunggu notifikasi selanjutnya dari atasan!' : 'Pengajuan sudah tersimpan dan sudah disetujui otomatis oleh sistem, terima kasih!');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(EmployeeHoliday $holiday, Request $request)
	{
		$user = $request->user();
		$employee = $user->employee;

		$holiday = $holiday->load('approvables.userable.position');

		return view('portal::holidays.submission.show', compact('user', 'employee', 'holiday'));
	}

	/**
	 * Show the form for editing a specified resource.
	 */
	public function edit(EmployeeHoliday $vacation, Request $request)
	{
		$employee = $request->user()->employee;

		$quotas = $employee->vacationQuotas()->with('category', 'vacations')->active()->get();

		return view('portal::vacation.submission.edit', compact('employee', 'quotas', 'vacation'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(EmployeeHoliday $holiday, UpdateRequest $request)
	{
		$employee = $request->user()->employee;

		$holiday->fill($request->transformed()->toArray())->save();

		// Reset approvable
		foreach ($holiday->approvables as $approvable) {
			$approvable->fill([
				'result' => ApprovableResultEnum::PENDING,
				'reason' => null,
				'history' => $approvable->result == ApprovableResultEnum::REVISION ? $approvable->only('result', 'reason', 'cancelable') : null
			])->save();
		}

		// Handle notifications
		if ($approvable = $holiday->approvables()->orderBy('level')->first()) {
			$approvable->userable->employee->user->notify(new RevisedNotification($holiday, $position = null));
		}

		return redirect()->route('portal::holiday.submission.index')->with('success', 'Pengajuan hasil revisi sudah dikirim ulang, silakan tunggu notifikasi selanjutnya dari atasan!');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(EmployeeHoliday $holiday)
	{
		$holiday->delete();

		// Handle notifications
		if ($approvable = $holiday->approvables()->whereNotIn('result', [ApprovableResultEnum::PENDING])->orderBy('level')->first()) {
			$approvable->userable->employee->user->notify(new CanceledNotification($holiday));
		}

		return redirect()->route('portal::holiday.submission.index')->with('success', 'Pengajuan hari libur telah dibatalkan dan kami telah mengirim notifikasi ke atasan!');
	}
}
