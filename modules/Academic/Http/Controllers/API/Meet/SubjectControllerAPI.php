<?php

namespace Modules\Academic\Http\Controllers\API\Meet;
use Auth;
use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;
use Modules\Academic\Models\AcademicSubjectSchedule;
use Modules\Administration\Models\SchoolBillStudent;
use Modules\Academic\Models\AcademicSubjectMeet;

class SubjectControllerAPI extends Controller
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
        $this->authorize('access', SchoolBillStudent::class);

        try {
            $trashed     = $request->get('trash', 0);
            $search      = $request->get('search', '');
            $smtId      = $request->get('semester_id');
            $subjectId    = $request->get('subject_id');
            $teacherId    = $request->get('teacher_id');
            $classroomId = $request->get('classroom_id');

            $limit       = $request->get('limit', 10);

            $acdmcInvoice = SchoolBillStudent::when($trashed, fn($query) => $query->onlyTrashed())
                ->when($smtId, fn($query) => $query->where('semester_id', $smtId))
                ->when($subjectId, fn($query) => $query->where('subject_id', $subjectId))
                ->when($teacherId, fn($query) => $query->where('subject_id', $teacherId))
                ->when($classroomId, fn($query) => $query->where('classroom_id', $classroomId))
                ->paginate($limit);

            if ($acdmcInvoice->isEmpty()) {
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
