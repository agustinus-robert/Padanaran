<?php

namespace Modules\HRMS\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\Core\Enums\PositionTypeEnum;

class TeacherReportController extends Controller
{
    /**
     * Search empls.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeScheduleTeacher::class);

        $start_at = $request->get('start_at', now()->startOfMonth()->toDateString());
        $end_at   = $request->get('end_at', now()->endOfMonth()->toDateString());

        $employees = Employee::with([
            'user.meta',
            'contract.position.position',
            'schedulesTeachers' => function ($query) use ($start_at, $end_at) {
                $query->where(function ($q) use ($start_at, $end_at) {
                    $q->where('start_at', '<=', $end_at)
                    ->where('end_at', '>=', $start_at); // overlap check
                });
            },
        ])
        ->search($request->get('search'))
        ->where('grade_id', userGrades())
        ->whenTrashed($request->get('trash'))
        ->whereHas('contract.position', function ($position) {
            $position->whereHas('position', function ($type) {
                $type->where('type', PositionTypeEnum::GURU);
            });
        })
        ->paginate($request->get('limit', 10));

        return response()->json([
            'success' => true,
            'data' => $employees->items(),
            'pagination' => [
                'total' => $employees->total(),
                'per_page' => $employees->perPage(),
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
            ],
            'filters' => [
                'start_at' => $start_at,
                'end_at' => $end_at,
            ],
        ]);
    }
}