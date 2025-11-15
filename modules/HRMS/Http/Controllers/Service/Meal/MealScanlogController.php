<?php

namespace Modules\HRMS\Http\Controllers\Service\Meal;

use Illuminate\Http\Request;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Http\Requests\Service\Meal\Scanlog\StoreRequest;
use Modules\HRMS\Models\EmployeeMealScanLog;

class MealScanlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeScanLog::class);

        $start_at = $request->get('start_at', date('Y-m-01')) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $departments = CompanyDepartment::visible()->with('positions')->get();

        foreach (WorkLocationEnum::cases() as $location) {
            $locations[$location->value] = $location->name;
        }

        $scanlogs = EmployeeMealScanLog::with('employee.user', 'employee.contract.position.position')
            ->whereBetween('created_at', [$start_at, $end_at])
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('limit', 10));

        return view('hrms::service.meal.scanlogs.index', compact('start_at', 'end_at', 'departments', 'scanlogs', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = Employee::find($request->input('employee'));

        $employee->mealscanlogs()->create($request->transformed()->toArray());

        return redirect()->back()->with('success', 'Presensi makan <strong>' . $employee->user->name . '</strong> pada tanggal ' . $request->input('datetime') . ' berhasil dibuat.');
    }
}
