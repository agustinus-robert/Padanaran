<?php

namespace Modules\Academic\Http\Controllers;

use PDF;

use Illuminate\Http\Request;
use Modules\Academic\Http\Controllers\Controller;
use Modules\Academic\Models\AcademicClassroom;
use Modules\Academic\Models\AcademicClassroomPresence;
use Modules\Academic\Models\Academic;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    //    $this->authorize('access', Academic::class);

        $acsem = $this->acsem;

        $user = auth()->user();
        $student = $user->student;

        $stsem = null;
        if (!empty($student)) {
            $stsem = $student->semesters()->with('reports')->where('semester_id', $acsem->id)->first();
        }

        return view('academic::report', compact('acsem', 'user', 'student', 'stsem'));
    }

    /**
     * Print the report.
     */
    public function print()
    {
      //  $this->authorize('access', Academic::class);

        $acsem = $this->acsem;

        $user = auth()->user();
        $student = $user->student;

        $stsem = $student->semesters()->with('reports', 'reportEval', 'extras')->where('semester_id', $acsem->id)->first();

        $filename = 'RAPORT-'.$student->nis.'-'.$stsem->semester->full_name.'.pdf';
        $achivment = $user->achievements()->get();


        $achievementsSmt = $student->achievementsInSemester($acsem->id)->get();

        $classroom = AcademicClassroom::whereHas('students', function ($query) use ($student) {
            $query->where('stdnts.id', $student->id);
        })
        ->with([
            'students' => fn ($query) => $query->where('stdnts.id', $student->id)
        ])
        ->where('semester_id', $acsem->id)
        ->first();


        $presences = AcademicClassroomPresence::where('classroom_id', $classroom->id)
        ->pluck('presence')
        ->flatten(1)
        ->toArray();


        $summary = $student->presenceSummary($presences);


       // $presenceData = $stsem->presence ?? []; 
       // $presenceSummary = $student->presenceSummary($presenceData);

        $pdf = PDF::loadView('academic::report-print', compact('acsem', 'user', 'student', 'stsem', 'filename', 'achievementsSmt', 'summary'));
        return $pdf->stream($filename);
    }
}
