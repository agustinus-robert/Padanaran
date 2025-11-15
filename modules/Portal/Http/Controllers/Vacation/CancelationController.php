<?php

namespace Modules\Portal\Http\Controllers\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\HRMS\Models\EmployeeVacationQuota;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Vacation\Cancelation\UpdateRequest;
use Modules\Portal\Notifications\Vacation\Cancelation\CancelationNotification;

class CancelationController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(EmployeeVacation $vacation, Request $request)
    {
    	$user = $request->user();
		$employee = $user->employee;

		$vacation = $vacation->load('approvables.userable.position');

        return view('portal::vacation.cancelation.show', compact('user', 'employee', 'vacation'));
    }

	/**
     * Update the specified resource in storage.
	 */
	public function update(EmployeeVacation $vacation, UpdateRequest $request)
	{
		$employee = $request->user()->employee;

		$vacation->fill($request->transformed()->toArray())->save();

		$vacation->approvables()->update([
			'cancelable' => 1,
			'result' => ApprovableResultEnum::PENDING,
			'reason' => null
		]);

		// Handle notifications
		if($approvable = $vacation->approvables()->orderBy('level')->first()) {
			$approvable->userable->employee->user->notify(new CancelationNotification($vacation));
		}

		return redirect()->route('portal::vacation.submission.index')->with('success', 'Pengajuan pembatalan telah dikirim, silakan tunggu notifikasi selanjutnya dari atasan!');
	}
}