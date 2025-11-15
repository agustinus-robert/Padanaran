<?php
namespace Modules\Counseling\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Counseling\Http\Controllers\Controller;

use App\Models\References\GradeLevel;
use Modules\Academic\Models\AcademicClassroom;
use Modules\Academic\Models\AcademicClassroomPresence;
use Modules\Academic\Models\AcademicSubjectMeetPlan;
use Modules\HRMS\Models\Employee;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Academic\Models\StudentSemesterAssessment;
use Modules\Counseling\Http\Requests\Presences\PresenceRequest;
use Modules\Academic\Models\AcademicSubject;


class PresenceClassRoomAPIController extends Controller
{
    // Force JSON
    public function index(Request $request)
    {
        $teacherId = $request->teacher_id;
        $lessonId = $request->lesson_id;
        $date = $request->get('date') ?? now()->toDateString();

        if (empty($teacherId)) {
            return response()->json([
                'success' => false,
                'message' => 'Guru harus dimasukkan',
            ]);
        }

        // Ambil guru beserta jadwal
        $teacher = Employee::with('schedulesTeachers')
            ->where('id', $teacherId)
            ->whereHas('contract.position.position', function ($q) {
                $q->where('type', PositionTypeEnum::GURU);
            })
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        $result = [];
        $classroomsInfo = [];

        foreach ($teacher->schedulesTeachers as $schedule) {
            $dates = json_decode($schedule->dates, true);
            if (!is_array($dates) || empty($dates)) continue;

            foreach ($dates as $d => $items) {
                if ($d !== $date) continue; // filter tanggal

                foreach ($items as $item) {
                    if (empty($item['rombel'])) continue;

                    foreach ($item['rombel'] as $classroomId) {
                        // Ambil classroom beserta stsems & students
                        $currentClassroom = AcademicClassroom::with('stsems.student.user')->find($classroomId);
                        if (!$currentClassroom) continue;

                        $classroomsInfo[$classroomId] = [
                            'id' => $currentClassroom->id,
                            'name' => $currentClassroom->name ?? null,
                        ];

                        $lessonId = $item['lesson'][0] ?? null;
                        $presences = AcademicClassroomPresence::where('classroom_id', $classroomId)->get();

                        $result[$classroomId] = [
                            'lessons' => [],
                            'students' => $currentClassroom->stsems->map(function ($st) use ($presences, $teacherId, $lessonId) {
                                $studentPresence = null;

                                foreach ($presences as $pres) {
                                    $items = is_array($pres->presence) ? $pres->presence : json_decode($pres->presence, true);

                                    foreach ($items as $item) {
                                        if (($item['student_id'] ?? null) == $st->student->id
                                            && ($item['teacher_id'] ?? null) == $teacherId
                                            && ($item['lesson_id'] ?? null) == $lessonId // pastikan sesuai lesson
                                        ) {
                                            $studentPresence = $item;
                                            break 2;
                                        }
                                    }
                                }

                                return [
                                    'semester_id' => $st->id,
                                    'student_id' => $st->student->id,
                                    'presence' => $studentPresence['presence'] ?? 0,
                                    'was_presence' => $studentPresence ? true : false,
                                    'name' => $st->student->user->name,
                                    'at' => $studentPresence['at'] ?? null,
                                    'lesson_id' => $lessonId,
                                    'teacher_id' => $teacherId,
                                ];
                            })->toArray(),
                            'time_start' => $item['0'],
                            'time_end' => $item['1'],
                        ];

                        // Lessons
                        if ($lessonId && !in_array($lessonId, array_column($result[$classroomId]['lessons'], 'id'))) {
                            $lesson = AcademicSubject::find($lessonId);
                            if ($lesson) {
                                $result[$classroomId]['lessons'][] = [
                                    'id' => $lesson->id,
                                    'name' => $lesson->name,
                                ];
                            }
                        }
                    }

                }
            }
        }

        // Satukan dalam format array rombel per classroom
        $finalResult = [];
        foreach ($result as $classroomId => $data) {
            // Gabungkan time_start dan time_end menjadi satu string
            $data['time'] = $data['time_start'].'-'.$data['time_end'];
            unset($data['time_start'], $data['time_end']);
            $finalResult[$classroomId][] = $data;
        }

        return response()->json([
            'success' => true,
            'message' => 'Data classroom hari ini berhasil diambil',
            'data' => [
                'classroom' => $classroomsInfo,
                'result' => $finalResult,
            ],
        ]);
    }

    public function presencesSummary(Request $request)
    {
        $teacherId = $request->teacher_id;
        $lessonId = $request->lesson_id;
        $classroomIdFilter = $request->classroom_id; // <--- filter classroom
        $date = $request->get('date') ?? now()->toDateString();

        if (empty($teacherId)) {
            return response()->json([
                'success' => false,
                'message' => 'Guru harus dimasukkan',
            ]);
        }

        // Ambil guru dan jadwalnya
        $teacher = Employee::with('schedulesTeachers')
            ->where('id', $teacherId)
            ->whereHas('contract.position.position', function ($q) {
                $q->where('type', PositionTypeEnum::GURU);
            })
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        $summary = [];

        foreach ($teacher->schedulesTeachers as $schedule) {
            $dates = json_decode($schedule->dates, true);
            if (!is_array($dates) || empty($dates)) continue;

            foreach ($dates as $d => $items) {
                if ($d !== $date) continue;

                foreach ($items as $item) {
                    if (empty($item['rombel']) || empty($item['lesson'])) continue;

                    $lessonIdItem = $item['lesson'][0] ?? null;
                    if ($lessonId && $lessonId != $lessonIdItem) continue;

                    foreach ($item['rombel'] as $classroomId) {
                        if ($classroomIdFilter && $classroomIdFilter != $classroomId) continue; // <--- filter classroom

                        $currentClassroom = AcademicClassroom::find($classroomId);
                        if (!$currentClassroom) continue;

                        $presences = AcademicClassroomPresence::where('classroom_id', $classroomId)->get();

                        // inisialisasi counter per status
                        $counter = [0 => 0, 1 => 0, 2 => 0, 3 => 0];

                        foreach ($presences as $pres) {
                            $itemsPresence = is_array($pres->presence) ? $pres->presence : json_decode($pres->presence, true);
                            foreach ($itemsPresence as $p) {
                                if (($p['teacher_id'] ?? null) == $teacherId
                                    && ($p['lesson_id'] ?? null) == $lessonIdItem
                                ) {
                                    $status = $p['presence'] ?? 0;
                                    if (!isset($counter[$status])) $counter[$status] = 0;
                                    $counter[$status]++;
                                }
                            }
                        }

                        $summary[$classroomId] = [
                            'classroom_id' => $classroomId,
                            'classroom_name' => $currentClassroom->name ?? null,
                            'lesson_id' => $lessonIdItem,
                            'lesson_name' => $lessonIdItem ? AcademicSubject::find($lessonIdItem)->name : null,
                            'presence_summary' => $counter,
                        ];
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Ringkasan presensi berhasil diambil',
            'data' => array_values($summary),
        ]);
    }






    public function store(Request $request)
    {
        $this->authorize('store', AcademicClassroomPresence::class);
        $data = $request->all();

        try {
            $user = auth()->user();

            $presence = new AcademicClassroomPresence([
                'classroom_id' => $data['classroom_id'],
                'presenced_at' => $data['presenced_at'],
                'presence' => $data['presence'],
                'presenced_by' => $data['presenced_by'],
            ]);

            $presence->save();

            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil dibuat untuk ' . $presence->classroom->full_name,
                'data' => $presence,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}