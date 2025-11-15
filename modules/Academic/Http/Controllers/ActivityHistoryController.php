<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Counseling\Http\Controllers\Controller;

use Modules\Boarding\Models\BoardingStudentsLeave;
use Modules\Boarding\Models\BoardingStudents;
use Modules\Administration\Models\SchoolBuilding;
use Modules\Academic\Models\StudentSemester;
use Modules\Academic\Models\StudentSemesterCounseling;
use Modules\Academic\Models\AcademicClassroomPresence;
use Modules\Academic\Models\Student;
use Modules\Account\Models\UserLog;
use Modules\Academic\Models\AcademicCounselingCategory;
use Modules\Counseling\Http\Requests\Counseling\StoreRequest;
use Modules\Counseling\Http\Requests\Counseling\UpdateRequest;

class ActivityHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('access', StudentSemesterCounseling::class);
        
        $acsem = $this->acsem;

        $user = auth()->user();
    	$student = $user->student->semesters;
        $classroom = $student->first()->classroom_id;

        $leaveTable = (new BoardingStudentsLeave)->getTable();
        $boardingTable = (new BoardingStudents)->getTable();

        $actived = UserLog::with('modelable')
            ->where('user_id', $user->id)
            ->where(function ($q) use ($leaveTable, $boardingTable, $user) {
                $q->where(function ($sub) use ($leaveTable, $user) {
                    $sub->where('modelable_type', BoardingStudentsLeave::class)
                        ->whereIn('modelable_id', function ($query) use ($leaveTable, $user) {
                            $query->select('id')
                                ->from($leaveTable)
                                ->where('student_id', $user->student->id);
                        });
                })
                ->orWhere(function ($sub) use ($boardingTable, $user) {
                    $sub->where('modelable_type', BoardingStudents::class)
                        ->whereIn('modelable_id', function ($query) use ($boardingTable, $user) {
                            $query->select('id')
                                ->from($boardingTable)
                                ->where('student_id', $user->student->id);
                        });
                });
            })
            ->orderByDesc('created_at');

                    // paginate
        $activityStudent = $actived->paginate();
        $activityStudentNum = (clone $actived)->count();

        return view('academic::activity-history', compact('acsem', 'activityStudent', 'activityStudentNum'));
    }
}