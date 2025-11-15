<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Counseling\Http\Controllers\Controller;

use Modules\Academic\Models\StudentSemester;
use Modules\Academic\Models\StudentSemesterCounseling;
use Modules\Academic\Models\AcademicCounselingCategory;
use Modules\Counseling\Http\Requests\Counseling\StoreRequest;
use Modules\Counseling\Http\Requests\Counseling\UpdateRequest;
use Modules\Academic\Models;

class ClassRoomController extends Controller
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

        $students = StudentSemester::with('student')->where(['semester_id' => $acsem->id, 'classroom_id' => $classroom])->paginate($request->get('limit', 10));
        $studentsCount = StudentSemester::with('student')->where(['semester_id' => $acsem->id, 'classroom_id' => $classroom])->count();

        return view('academic::classroom', compact('acsem', 'students', 'studentsCount'));
    }
}