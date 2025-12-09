<?php

namespace Modules\Academic\Http\Controllers\API\Meet;
use Auth;
use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;
use Modules\Administration\Models\SchoolBuilding;
use App\Models\District;
use Modules\Academic\Models\AcademicSubjectSchedule;

class ScheduleControllerAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Gunakan Bearer Token
    }

    /**
     * List buildings with pagination
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicSubjectSchedule::class);

        try {
            $trashed     = $request->get('trash', 0);
            $search      = $request->get('search', '');
            $subjectId   = $request->get('subject_id');
            $teacherId   = $request->get('teacher_id');
            $classroomId = $request->get('classroom_id');
            $dayId       = $request->get('day_id');
            $limit       = $request->get('limit', 10);
            //where('grade_id', userGrades())
            $acdmcSchedule = AcademicSubjectSchedule::when($trashed, fn($query) => $query->onlyTrashed())
                ->when($subjectId, fn($query) => $query->where('subject_id', $subjectId))
                ->when($teacherId, fn($query) => $query->where('teacher_id', $teacherId))
                ->when($dayId, fn($query) => $query->where('day', $dayId))
                ->when($classroomId, fn($query) => $query->where('classroom_id', $classroomId))
                ->paginate($limit);

            // Response kalau data kosong tetap jelas
            if ($acdmcSchedule->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Tidak ada data untuk filter ini'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $acdmcSchedule
            ]);

        } catch (\Exception $e) {
            // Fallback kalau query error
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
}
