<?php

namespace Modules\Portal\Http\Controllers\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\HRMS\Models\EmployeeVacationQuota;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Vacation\Submission\StoreRequest;
use Modules\Portal\Http\Requests\Vacation\Submission\UpdateRequest;
use Modules\Portal\Notifications\Vacation\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Vacation\Submission\RevisedNotification;
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

		$quotas = $employee->vacationQuotas()->with('category', 'vacations.approvables')->active()->get()
			->sortBy('category.type.value')->filter(fn ($quota) => $quota->category->type->quotaVisibility());

		return view('portal::vacation.submission.index', compact('employee', 'vacations', 'quotas'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Request $request)
	{
		$employee = $request->user()->employee;

		$quotas = $employee->vacationQuotas()->with('category', 'vacations')->active()->get();

		if (!$quotas->count()) {
			return redirect()->back()->with('danger', 'Maaf, sepertinya belum ada kuota cuti tahun ini nih, silakan hubungi admin untuk keterangan lebih lanjut!');
		}

		return view('portal::vacation.submission.create', compact('employee', 'quotas'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreRequest $request)
	{
		$employee = $request->user()->employee;

		$vacation = $employee->vacations()->create(
			array_merge($request->transformed()->toArray(),
			['grade_id' => userGrades()])
		);

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

		return redirect()->route('portal::vacation.submission.index')->with('success', isset($position) ? 'Pengajuan cuti/libur hari raya sudah terkirim, silakan tunggu notifikasi selanjutnya dari atasan!' : 'Pengajuan sudah tersimpan dan sudah disetujui otomatis oleh sistem, terima kasih!');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(EmployeeVacation $vacation, Request $request)
	{
		$user = $request->user();
		$employee = $user->employee;

		$vacation = $vacation->load('approvables.userable.position');

		return view('portal::vacation.submission.show', compact('user', 'employee', 'vacation'));
	}

	/**
	 * Show the form for editing a specified resource.
	 */
	public function edit(EmployeeVacation $vacation, Request $request)
	{
		$employee = $request->user()->employee;

		$quotas = $employee->vacationQuotas()->with('category', 'vacations')->active()->get();

		return view('portal::vacation.submission.edit', compact('employee', 'quotas', 'vacation'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(EmployeeVacation $vacation, UpdateRequest $request)
	{
		$employee = $request->user()->employee;

		$vacation->fill(array_merge(
			$request->transformed()->toArray(),
			['grade_id' => userGrades()])
		)->save();

		// Reset approvable
		foreach ($vacation->approvables as $approvable) {
			$approvable->fill([
				'result' => ApprovableResultEnum::PENDING,
				'reason' => null,
				'history' => $approvable->result == ApprovableResultEnum::REVISION ? $approvable->only('result', 'reason', 'cancelable') : null
			])->save();
		}

		// Handle notifications
		if ($approvable = $vacation->approvables()->orderBy('level')->first()) {
			$approvable->userable->employee->user->notify(new RevisedNotification($vacation));
		}

		return redirect()->route('portal::vacation.submission.index')->with('success', 'Pengajuan hasil revisi sudah dikirim ulang, silakan tunggu notifikasi selanjutnya dari atasan!');
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

		return redirect()->route('portal::vacation.submission.index')->with('success', 'Pengajuan telah dibatalkan dan kami telah mengirim notifikasi ke atasan!');
	}
}
