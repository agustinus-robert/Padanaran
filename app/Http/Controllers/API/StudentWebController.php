<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Academic\Models\Student;

class StudentWebController extends Controller
{
    public function index(Request $request)
    {
        $trashed = $request->get('trash');
        $search  = $request->get('search');
        $gradeId = userGrades();

        try {
            $cacheKey = "students_api:"
            . "grade={$gradeId}:"
            . "search=" . ($search ?: 'null') . ":"
            . "trashed=" . ($trashed ?: 'false');

            $students = Cache::tags(['students'])->remember($cacheKey, now()->addMinutes(60), function () use ($trashed, $search, $gradeId) {
                Log::info('sedang membuat cache');
                $std = Student::with('user')
                    ->where('grade_id', $gradeId)
                    ->when($search, fn($query) => $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")))
                    ->when($trashed, fn($query) => $query->onlyTrashed())
                    ->orderByDesc('id')
                    ->get(['id', 'user_id']);

                Log::info('cache berhasil dibuat');
                return $std;
            });

            $students = $students->map(fn($student) => [
                'id' => $student->id,
                'name' => $student->user->name ?? null,
            ]);

            Log::info('cache di load');
            return response()->json([
                'data' => $students,
                'count' => $students->count(),
            ]);
        } catch (\Throwable $th) {
            Log::error([
                'error' => $th->getMessage()
            ]);

            throw $th;
        }


    }
}
