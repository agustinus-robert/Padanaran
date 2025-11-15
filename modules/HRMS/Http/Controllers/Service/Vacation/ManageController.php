<?php

namespace Modules\HRMS\Http\Controllers\Service\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Vacation\Manage\UpdateRequest;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeVacation::class);

        $start_at = $request->get('start_at', date('Y-m-01'));
        $end_at = $request->get('end_at', date('Y-m-t'));

        $departments = CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get();

        $vacations = EmployeeVacation::with('quota.employee.user', 'quota.category', 'approvables.userable.position')
            ->where('grade_id', userGrades())
            ->whenPeriod($start_at, $end_at)
            ->whenWithTrashed($request->get('trashed'))
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('limit', 10));

        return view('hrms::service.vacation.manage.index', compact('start_at', 'end_at', 'departments', 'vacations'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeVacation $vacation)
    {
        $this->authorize('store', EmployeeVacation::class);

        $vacation = $vacation->load('quota.employee.user', 'approvables.userable.position');
        $employee = $vacation->quota->employee;

        $results = ApprovableResultEnum::cases();

        return view('hrms::service.vacation.manage.show', compact('employee', 'vacation', 'results'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeVacation $vacation, UpdateRequest $request)
    {
        $this->authorize('update', $vacation);

        $data = array_merge($request->transformed()->toArray(), [
            'grade_id' => userGrades()
        ]);

        $vacation->update($data);

        return redirect()->back()->with('success', 'Berhasil memperbarui detail pengajuan, terima kasih!');
    }

    /**
     * Change approvable status to cancelable/not.
     */
    public function change(EmployeeVacation $vacation)
    {
        $current = $vacation->approvables->first()->cancelable ?? false;

        $vacation->approvables()->update([
            'cancelable' => !($current),
            'result' => 0,
            'reason' => null,
            'history' => null
        ]);

        return redirect()->back()->with('success', 'Berhasil mengubah status menjadi ' . ($current ? 'pengajuan' : 'pembatalan') . ', terima kasih!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeVacation $vacation)
    {
        $this->authorize('destroy', $vacation);

        $tmp = $vacation;
        $vacation->delete();

        return redirect()->back()->with('success', 'Pengajuan cuti <strong>' . $tmp->quota->employee->user->name . '</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeVacation $vacation)
    {
        $this->authorize('restore', $vacation);

        $vacation->restore();

        return redirect()->back()->with('success', 'Pengajuan cuti <strong>' . $vacation->quota->employee->user->name . '</strong> berhasil dipulihkan');
    }
}
