<?php

namespace Modules\Portal\Http\Controllers\Schedule;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeeContractSchedule;
use Modules\HRMS\Models\EmployeeHoliday;
use Modules\HRMS\Repositories\EmployeeContractScheduleRepository;
use Modules\HRMS\Repositories\EmployeeRepository;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Schedule\StoreRequest;
use Modules\Portal\Http\Requests\Schedule\UpdateRequest;
use Modules\Portal\Notifications\Schedule\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Schedule\Manage\ApprovedNotification;
use Modules\Portal\Notifications\Schedule\Manage\RejectedNotification;

class ManageController extends Controller
{
	use EmployeeRepository, EmployeeContractScheduleRepository;

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$user 		= $request->user();
		$employee 	= $user->employee->load('position.position.children');
		$month 		= Carbon::parse($request->get('month', now()));
		$start_at 	= $month->copy()->startOfMonth()->addDays(20)->format('Y-m-d');
		$end_at 	= $month->copy()->endOfMonth()->addDays(20)->format('Y-m-d');

		$employees = Employee::with([
			'user.meta',
			'contract.position.position',
			'contract.schedules' => fn ($schedule) => $schedule->where('start_at', $start_at)->where('end_at', $end_at),
			'holidays' => fn ($holiday) => $holiday->whereDate('start_at', $start_at)->whereDate('end_at', $end_at),
		])
			->whereHas('position', fn ($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
			->search($request->get('search'))->whenTrashed($request->get('trash'))->paginate($request->get('limit', 10));

		$employee_count = $employees->count();

		return view('portal::schedules.manage.index', compact('user', 'employees', 'employee_count'));
	}

	/**
	 * create a resource.
	 */
	public function create(Request $request)
	{
		$this->authorize('store', EmployeeContractSchedule::class);

		$contract 	= EmployeeContract::find($request->get('contract'));
		$month 		= Carbon::parse($request->get('month', now()));
		$start_at 	= $month->copy()->startOfMonth()->addDays(20)->format('Y-m-d 01:00:01');
		$end_at 	= $month->copy()->endOfMonth()->addDays(20)->format('Y-m-d 23:59:59');
		$workshifts = WorkShiftEnum::cases();
		$moments    = CompanyMoment::holiday()->whereBetween('date', [Carbon::parse($start_at)->format('Y-m-d'), Carbon::parse($end_at)->format('Y-m-d')])->get();
		$periods  	= CarbonPeriod::create(Carbon::parse($start_at)->format('Y-m-d'), '1 day', Carbon::parse($end_at)->format('Y-m-d'));
		$holidays	= EmployeeHoliday::whereEmplId($contract->empl_id)
			->whereDate('start_at', Carbon::parse($start_at)->format('Y-m-d'))
			->whereDate('end_at', Carbon::parse($end_at)->format('Y-m-d'))->get()->pluck('dates')->flatten(1)
			->mapToGroups(fn ($g) => [$g['d'] => $g['d']]);

		// Iterate over the period
		$dates = [];
		foreach ($periods as $key => $date) {
			$dates[] = $date->format('Y-m-d');
		}

		return view('portal::schedules.manage.create', compact('contract', 'dates', 'workshifts', 'moments', 'start_at', 'end_at', 'holidays'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreRequest $request)
	{
		if ($schedule = $this->storeEmployeeContractSchedule($request->transformed()->toArray())) {
			return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->contract->employee->user->name . '</strong> berhasil dibuat.');
		}
		return redirect()->fail();
	}

	/**
	 * Display the specified resource.
	 */
	public function show(EmployeeContractSchedule $schedule, Request $request)
	{
		$this->authorize('update', $schedule);

		$workshifts = WorkShiftEnum::cases();
		$month 		= Carbon::parse($request->get('month', now()));
		$start_at 	= $month->copy()->startOfMonth()->addDays(20)->format('Y-m-d 01:00:01');
		$end_at 	= $month->copy()->endOfMonth()->addDays(20)->format('Y-m-d 23:59:59');
		$periods  	= CarbonPeriod::create(Carbon::parse($start_at)->format('Y-m-d'), '1 day', Carbon::parse($end_at)->format('Y-m-d'));
		$moments    = CompanyMoment::holiday()->whereBetween('date', [Carbon::parse($start_at)->format('Y-m-d'), Carbon::parse($end_at)->format('Y-m-d')])->get();
		$holidays	= EmployeeHoliday::whereEmplId($schedule->contract->empl_id)
			->whereDate('start_at', Carbon::parse($start_at)->format('Y-m-d'))
			->whereDate('end_at', Carbon::parse($end_at)->format('Y-m-d'))->get()->pluck('dates')->flatten(1)
			->mapToGroups(fn ($g) => [$g['d'] => $g['d']]);

		// Iterate over the period
		$dates = [];
		foreach ($periods as $key => $date) {
			$dates[] = $date->format('Y-m-d');
		}

		return view('portal::schedules.manage.show', compact('schedule', 'workshifts', 'dates', 'moments', 'holidays'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(EmployeeContractSchedule $schedule, UpdateRequest $request)
	{
		if ($schedule = $this->updateEmployeeContractSchedule($schedule, $request->transformed()->toArray())) {
			return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->contract->employee->user->name . '</strong> berhasil diperbarui.');
		}
		return redirect()->fail();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(EmployeeContractSchedule $schedule)
	{
		$this->authorize('destroy', $schedule);

		if ($schedule = $this->destroyEmployeeContractSchedule($schedule)) {
			return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->contract->employee->user->name . '</strong> berhasil dihapus.');
		}
		return redirect()->fail();
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function approval(CompanyApprovable $approvable, UpdateRequest $request)
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
