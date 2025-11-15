<?php

namespace Modules\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Teacher\Http\Controllers\Controller;

use Auth;
use Modules\Academic\Models\AcademicClassroom;
use Modules\Academic\Models\AcademicSemester;
use Modules\Academic\Models\AcademicSubjectMeet;
use Modules\Academic\Models\StudentSemesterReport;
use Modules\Academic\Models\AcademicSubject;
use Modules\Academic\Models\AcademicSubjectCompetence;
use Modules\Teacher\Http\Requests\Supervisor\UpdateRequest;
use Modules\Academic\Models\StudentAcademicEvaluation;
use Modules\Academic\Models\StudentSemester;

class ReccomendationController extends Controller
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
	//	$subject = AcademicSubject::with('competences')->inTeacherAndSemester($teacher, $acsem)->find($meet->subject_id);
        $classRooms = AcademicClassroom::where('supervisor_id', $teacher->id)->get();
        $classRoomsByLevel = $classRooms->groupBy('level_id');

		return view('teacher::reccomendation.show', compact('cls', 'acsem'));
	}

	public function update(AcademicClassroom $classroom, Request $request)
	{
       // $this->authorize('update', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();

		foreach ($request->input('value') as $smt_id => $value) {
			$studentSmt = StudentSemester::find($smt_id);

			$dataId = StudentAcademicEvaluation::updateOrCreate([
				'smt_id' => $smt_id,
			], [
				'recommendation_note' => $value['recommendation_note'] ?? null,
				'grade' => $value['grade'] ?? null
			]);

			Auth::user()->log(
				' Kenaikan untuk siswa '.$studentSmt->student->user->name.' telah dibuat oleh '.$user->teacher->user->name.' '.
				' <strong>[ID: ' . $dataId->id . ']</strong>',
				StudentAcademicEvaluation::class,
				$dataId->id
			);
		}

		return redirect()->back()->with('success', 'Nilai raport '.$classroom->name.' berhasil diperbarui.');
	}
}
