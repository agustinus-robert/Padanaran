<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Academic\Models\Student;

class StudentWebController extends Controller
{
    /**
     * Return JSON list of students (id + name) for API.
     */
    public function index(Request $request)
    {
        $trashed = $request->get('trash');
        $search  = $request->get('search');
        $gradeId = userGrades();

        $cacheKey = "students_api:"
            . "grade={$gradeId}:"
            . "search=" . ($search ?: 'null') . ":"
            . "trashed=" . ($trashed ?: 'false');

        $students = Cache::tags(['students'])->remember($cacheKey, 60, function () use ($trashed, $search, $gradeId) {
            return Student::with('user')
                ->where('grade_id', $gradeId)
                ->when($search, fn($query) => $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")))
                ->when($trashed, fn($query) => $query->onlyTrashed())
                ->orderByDesc('id')
                ->get(['id', 'user_id']); // Ambil id dan user_id
        });

        // Map user name dari relasi
        $students = $students->map(fn($student) => [
            'id' => $student->id,
            'name' => $student->user->name ?? null,
        ]);

        return response()->json([
            'data' => $students,
            'count' => $students->count(),
        ]);
    }
}
