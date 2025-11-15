<?php

namespace Modules\Administration\Http\Controllers\Service\Template;

use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use Modules\Administration\Http\Requests\Service\Teacher\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Administration\Excel\Exports\ExportTeacher;
use Modules\Core\Enums\PositionTypeEnum;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Employee::class);

        $employees = Employee::with('position')
            ->whereHas('position', function ($query) {
                $query->where('position_id', 14);
            })
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->get();

        return Excel::download(new ExportTeacher($employees), 'Template data guru.xlsx');
    }
}
