<?php

namespace Modules\HRMS\Http\Controllers\Service\Teacher\Template;

use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use Modules\Administration\Http\Requests\Service\Teacher\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Administration\Excel\Exports\ExportScheduleTeacher;
use Modules\Core\Enums\PositionTypeEnum;

class TeacherScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Employee::class);

        return Excel::download(new ExportScheduleTeacher(), 'Template jadwal guru.xlsx');
    }
}
