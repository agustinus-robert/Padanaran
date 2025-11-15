<?php

namespace Modules\Administration\Http\Controllers\API\Scholar;

use Auth;
use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;
use Modules\Academic\Models\AcademicSemester;
use Modules\Academic\Models\AcademicClassroom;
use Modules\Academic\Models\StudentSemester;
use Modules\HRMS\Models\Employee;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Administration\Models\SchoolBuildingRoom;
use App\Models\References\GradeLevel;
use Modules\Administration\Http\Requests\Scholar\Classroom\StoreRequest;
use Modules\Administration\Http\Requests\Scholar\Classroom\UpdateRequest;
use Modules\Administration\Http\Requests\Scholar\Classroom\SyncStudentsRequest;

class ClassroomAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Bearer token
    }

    /**
     * List classrooms
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicClassroom::class);

        $trashed = $request->get('trash', 0);
        $search  = $request->get('search', '');
        $limit   = $request->get('limit', 10);
        $academicId = $request->get('academic');

        $acsems = AcademicSemester::openedByDesc()->get();
        //userGrades()
        $gradesLevels = GradeLevel::pluck('id');
        $academicId = $academicId ?? $acsems->first()->id;

        $classrooms = AcademicClassroom::withCount('stsems')
            ->where('name', 'like', "%$search%")
            ->where('semester_id', $academicId)
            ->whereIn('level_id', $gradesLevels)
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->orderBy('level_id')
            ->orderBy('name')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $classrooms            
        ]);
    }
}
