<?php

namespace Modules\Academic\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Academic\Models\StudentsPackage;
use Modules\Academic\Models\Student;
use Modules\Portal\Http\Controllers\Controller;

class StudentPackageControllerAPI extends Controller
{
    /**
     * Display a listing of the resource as JSON.
     */
    public function index(Request $request)
    {
        $student = $request->user();
        $month    = Carbon::parse($request->get('month', now()));

        $start_at = $month->copy()->startOfMonth()->format("Y-m-d");
        $end_at   = $month->copy()->endOfMonth()->format("Y-m-d");

        $packages = StudentsPackage::with('student')
            ->whereHas('student', fn($query) => $query->where('id', $student->id))
            ->whereNull('deleted_at')
            ->get();

        $students = Student::where('id', $student->id)
            ->whereNull('deleted_at')
            ->get();

        $packagesCount = StudentsPackage::whereHas('student', fn($query) => $query->where('id', $student->id))
            ->whereNull('deleted_at')
            ->count();

        return response()->json([
            'start_at'      => $start_at,
            'end_at'        => $end_at,
            'data'          => [
                'packages'      => $packages,
                'packagesCount' => $packagesCount,
                'students'      => $students
            ]
        ]);
    }
}
