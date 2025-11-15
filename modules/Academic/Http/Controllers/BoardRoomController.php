<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Counseling\Http\Controllers\Controller;

use Modules\Boarding\Models\BoardingStudents;
use Modules\Administration\Models\SchoolBuilding;
use Modules\Academic\Models\StudentSemester;
use Modules\Academic\Models\StudentSemesterCounseling;
use Modules\Academic\Models\AcademicCounselingCategory;
use Modules\Counseling\Http\Requests\Counseling\StoreRequest;
use Modules\Counseling\Http\Requests\Counseling\UpdateRequest;
use Modules\Academic\Models;

class BoardRoomController extends Controller
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

        $boardStatus = BoardingStudents::with('employee', 'room', 'room.building', 'student')->where('student_id', $user->student->id)->first(); 
        $boardFriends = BoardingStudents::with('employee', 'room', 'room.building', 'student')->where(['building_id' => $boardStatus->building_id, 'room_id' => $boardStatus->room_id])->get();

        return view('academic::boardroom', compact('boardStatus', 'boardFriends'));
    }
}