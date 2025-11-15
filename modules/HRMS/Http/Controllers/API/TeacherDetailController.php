<?php

namespace Modules\HRMS\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\Core\Models\CompanyPosition;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\Academic\Models\AcademicClassroom;
use Modules\Academic\Models\AcademicSubject;

class TeacherDetailController extends Controller
{

    /**
     * Fetch all empls.
     */
    public function getTeacherClassroom(Request $request)
    {
        $emplId = $request->employee_id;
        $date = $request->date;
        $rombel = [];

        $schedule = EmployeeScheduleTeacher::where('empl_id', $emplId)->first();

        $data = $schedule->dates->toArray();
        foreach($data[$date] as $date){
            if(isset($date['rombel'][0])){
                $rombel[] = $date['rombel'][0];
            }
        }

        if (!empty($rombel)) {
            $unique = array_unique($rombel);
            $classrooms = AcademicClassroom::whereIn('id', $unique)
            ->get(['id', 'name']); // ambil id dan name langsung
        } else {
            $classrooms = collect(); // kosong jika tidak ada
        }

        return response()->json([
            'success' => true,
            'data' => $classrooms->map(function($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name
                ];
            })->values() // pastikan index rapi 0,1,2...
        ]);

    }

    public function getLessonByClassroom(Request $request)
    {
        $classroomId = $request->classroom_id;
        $emplId      = $request->employee_id;

        if (!$classroomId) {
            return response()->json([
                'success' => false,
                'message' => 'Classroom ID required',
                'data' => []
            ]);
        }

        $schedule = EmployeeScheduleTeacher::where('empl_id', $emplId)->first();
        $lessons = [];

        if ($schedule) {
            $data = $schedule->dates->toArray();

            foreach ($data as $day => $slots) {
                foreach ($slots as $slot) {
                    if (!empty($slot['rombel'][0]) && $slot['rombel'][0] == $classroomId) {
                        if (!empty($slot['lesson'][0])) {
                            $lessons[] = $slot['lesson'][0];
                        }
                    }
                }
            }
        }

        $uniqueLessons = array_unique($lessons);

        if (empty($uniqueLessons)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada mata pelajaran pada hari ini',
                'data' => []
            ]);
        }

        $lessonData = AcademicSubject::whereIn('id', $uniqueLessons)
            ->get(['id', 'name']) // ambil id dan name
            ->map(function($lesson) {
                return [
                    'id' => $lesson->id,
                    'name' => $lesson->name
                ];
            })
            ->values(); // reset index array

        return response()->json([
            'success' => true,
            'data' => $lessonData
        ]);
    }

    public function getTodayLessonsWithClassroom(Request $request)
    {
        $emplId = $request->employee_id;
        $date = $request->date;

        $schedule = EmployeeScheduleTeacher::where('empl_id', $emplId)->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal untuk guru ini',
                'data' => []
            ]);
        }

        $data = $schedule->dates->toArray();

        if (!isset($data[$date])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal pada tanggal ini',
                'data' => []
            ]);
        }

        $result = [];

        foreach ($data[$date] as $slot) {
            if (!empty($slot['rombel'][0]) && !empty($slot['lesson'][0])) {
                $classroom = AcademicClassroom::find($slot['rombel'][0]);
                $lesson = AcademicSubject::find($slot['lesson'][0]);

                if ($classroom && $lesson) {
                    $result[] = [
                        'classroom_id' => $classroom->id,
                        'classroom_name' => $classroom->name,
                        'lesson_id' => $lesson->id,
                        'lesson_name' => $lesson->name,
                    ];
                }
            }
        }

        // hilangkan duplikat jika ada
        $result = collect($result)->unique(function ($item) {
            return $item['classroom_id'] . '-' . $item['lesson_id'];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
