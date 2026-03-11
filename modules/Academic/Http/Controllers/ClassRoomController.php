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
        $acsem = $this->acsem;
        $user = auth()->user();

        $first = $user->student?->semesters->first();

        if (is_null($first)) {
            return redirect()->back()->with('danger', 'Data semester atau kelas tidak ditemukan!');
        }

        $classroom = $first->classroom_id;

        if (!$classroom) {
            return redirect()->back()->with('danger', 'Anda belum diplot ke kelas manapun!');
        }

        $query = StudentSemester::with('student')->where([
            'semester_id' => $acsem->id, 
            'classroom_id' => $classroom
        ]);

        $studentsCount = $query->count();
        $students = $query->paginate($request->get('limit', 10));

        return view('academic::classroom', compact('acsem', 'students', 'studentsCount'));
    }
}