<?php

namespace Modules\Finance\Http\Controllers\Service\Outwork;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyOutworkCategory;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeePosition;
use Modules\Finance\Http\Requests\Service\Outwork\Manage\StoreRequest;
use Modules\Finance\Http\Requests\Service\Outwork\Manage\UpdateRequest;
use Modules\Finance\Notifications\Service\Outwork\SubmissionNotification;
use Modules\HRMS\Models\EmployeeOutwork;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_at = $request->get('start_at', date('Y-m-01')) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $departments = CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get();

        $outworks = EmployeeOutwork::with('approvables.userable.position', 'employee.user')
            ->where('grade_id', userGrades())
            ->whenPeriod($start_at, $end_at)
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->whenOnlyPending($request->get('pending'))
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('limit', 10));

        $pending_outworks_count = EmployeeOutwork::where('grade_id', userGrades())->whenOnlyPending(true)->count();

        return view('finance::service.outwork.manage.index', compact('start_at', 'end_at', 'departments', 'departments', 'outworks', 'pending_outworks_count'));
    }

    /**
     * Create a newly resource.
     */
    public function create(Request $request)
    {
        $employees  = Employee::with('user', 'position')->get();
        $categories = CompanyOutworkCategory::where('grade_id', userGrades())->get()->groupBy('name');
        $results = config('modules.core.features.services.overtimes.approvable_enum_available');
        $limit_at = Carbon::parse((now()->copy()->startOfYear())->format('Y-m-d') . ' 00:00:00')->subMonth(1)->copy()->startOfMonth();

        return view('finance::service.outwork.manage.create', compact('employees', 'categories', 'results', 'limit_at'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $outwork = new EmployeeOutwork($request->transformed()->toArray());
        if ($outwork->save()) {
            foreach (config('modules.core.features.services.outworks.approvable_steps', []) as $index => $model) {
                if ($model['type'] == 'parent_position_level') {
                    $parents = $outwork->employee->position->position->parents->where('level.value', $model['value']);
                    $parents->each(
                        fn($position) =>
                        $outwork->createApprovable(empty($model['hide_from_input']) ? $position->employeePositions()->active()->find($request->input('approvables.' . $index)) : $position->employeePositions()->active()->first())
                    );
                }
            }
        }
        return redirect()->next()->with('success', 'Berhasil memperbarui detail pengajuan, terima kasih!');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeOutwork $outwork)
    {
        $outwork    = $outwork->load('employee.user', 'approvables.userable.position');
        $employee   = $outwork->employee;
        $categories = CompanyOutworkCategory::where('grade_id', userGrades())->get()->groupBy('name');
        $results = config('modules.core.features.services.overtimes.approvable_enum_available');

        return view('finance::service.outwork.manage.show', compact('employee', 'outwork', 'categories', 'results'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeOutwork $outwork, UpdateRequest $request)
    {
        $outwork->update($request->transformed()->toArray());

        return redirect()->back()->with('success', 'Berhasil memperbarui detail pengajuan, terima kasih!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeOutwork $outwork)
    {
        $tmp = $outwork;
        $outwork->delete();

        return redirect()->back()->with('success', 'Pengajuan lembur pekerjaan tambahan <strong>' . $tmp->employee->user->name . '</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeOutwork $outwork)
    {
        $outwork->restore();

        return redirect()->back()->with('success', 'Pengajuan lembur pekerjaan tambahan <strong>' . $outwork->employee->user->name . '</strong> berhasil dipulihkan');
    }
}
