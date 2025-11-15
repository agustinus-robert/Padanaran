<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Counseling\Http\Controllers\Controller;

use Modules\Academic\Models\StudentSemester;
use Modules\Academic\Models\StudentSemesterCounseling;
use Modules\Academic\Models\AcademicCounselingCategory;
use Modules\Counseling\Http\Requests\Counseling\StoreRequest;
use Modules\Counseling\Http\Requests\Counseling\UpdateRequest;

class CounselingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('access', StudentSemesterCounseling::class);

        $acsem = $this->acsem;

        $counselings = StudentSemesterCounseling::with('semester')->where(function($query) use ($request) {
            return $query->where('description', 'like', '%'.$request->get('search').'%')
                         ->orWhereHas('semester.student', function ($student) use ($request) {
                             return $student->whereNameLike($request->get('search'));
                         });
        })->whereHas('semester', function ($semester) {
            return $semester->where(['semester_id' => $this->acsem->id, 'student_id' => auth()->user()->student->id])
            ->whereHas('student');
        })->paginate($request->get('limit', 10));

        $counselings_count = StudentSemesterCounseling::whereHas('semester', function ($semester) {
            return $semester->where(['semester_id' => $this->acsem->id, 'student_id' => auth()->user()->student->id]);
        })->count();

        return view('academic::counseling', compact('acsem', 'counselings', 'counselings_count'));
    }
}