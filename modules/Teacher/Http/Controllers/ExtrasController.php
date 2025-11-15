<?php

namespace Modules\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Teacher\Http\Controllers\Controller;

use Modules\Academic\Models\AcademicClassroom;
use Modules\Academic\Models\AcademicSemester;
use Modules\Academic\Models\AcademicSubjectMeet;
use Modules\Academic\Models\StudentSemesterReport;
use Modules\Academic\Models\AcademicSubject;
use Modules\Academic\Models\StudentExtras;
use Modules\Academic\Models\AcademicSubjectCompetence;

class ExtrasController extends Controller
{
	public function show(AcademicClassroom $classroom, Request $request)
	{
     //   $this->authorize('access', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		$cls = $classroom->with(['stsems' => function ($stsem) {
								return $stsem->with('student', 'reports');
							}])
							->findOrFail($classroom->id);
		
		$teacher = $user->teacher;
		// $subject = AcademicSubject::with('competences')->inTeacherAndSemester($teacher, $acsem)->find($meet->subject_id);
        $classRooms = AcademicClassroom::where('supervisor_id', $teacher->id)->get();
        $classRoomsByLevel = $classRooms->groupBy('level_id');

		return view('teacher::extras.show', compact('acsem', 'cls'));
	}
}
