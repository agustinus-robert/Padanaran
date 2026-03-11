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
        $acsem = $this->acsem;
        $user = auth()->user();

        $firstSemester = $user->student?->semesters->first();
        
        if (is_null($firstSemester) || empty($firstSemester->classroom_id)) {
            return redirect()->back()->with('danger', 'Data kelas akademik Anda tidak ditemukan.');
        }

        $classroom = $firstSemester->classroom_id;

        $boardStatus = BoardingStudents::with('employee', 'room', 'room.building', 'student')
            ->where('student_id', $user->student->id)
            ->first();

        if (is_null($boardStatus)) {
            return redirect()->back()->with('danger', 'Anda belum terdaftar di fasilitas asrama mana pun.');
        }

        $boardFriends = [];
        if (!empty($boardStatus->building_id) && !empty($boardStatus->room_id)) {
            $boardFriends = BoardingStudents::with('employee', 'room', 'room.building', 'student')
                ->where([
                    'building_id' => $boardStatus->building_id, 
                    'room_id' => $boardStatus->room_id
                ])
                ->get();
        } else {
            return redirect()->back()->with('danger', 'Detail gedung atau kamar asrama Anda belum diatur.');
        }

        return view('academic::boardroom', compact('boardStatus', 'boardFriends'));
    }
}