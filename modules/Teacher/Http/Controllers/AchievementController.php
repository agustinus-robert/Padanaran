<?php

namespace Modules\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Teacher\Http\Controllers\Controller;

use Modules\Academic\Models\AcademicClassroom;
use Modules\Academic\Models\AcademicSemester;
use Modules\Academic\Models\AcademicSubjectMeet;
use Modules\Academic\Models\StudentSemesterReport;
use Modules\Academic\Models\AcademicSubject;
use Modules\Academic\Models\AcademicSubjectCompetence;

class AchievementController extends Controller
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
		//$subject = AcademicSubject::with('competences')->inTeacherAndSemester($teacher, $acsem)->find($meet->subject_id);
        $classRooms = AcademicClassroom::where('supervisor_id', $teacher->id)->get();
        $classRoomsByLevel = $classRooms->groupBy('level_id');

		return view('teacher::achievement.show', compact('acsem', 'cls'));
	}

	public function update(AcademicClassroom $classroom, UpdateRequest $request)
	{
       // $this->authorize('update', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		foreach ($request->input('value') as $smt_id => $value) {
			StudentSemesterReport::updateOrCreate([
				'smt_id' => $smt_id,
				'subject_id' => $meet->subject_id,
			], [
				'ki3_comment' => $value['ki3_comment'] ?? null,
				'ki4_evaluation' => $value['ki4_evaluation'] ?? null,
			]);
		}

		return redirect()->back()->with('success', 'Nilai raport '.$meet->subject->name.' berhasil diperbarui.');
	}
}
