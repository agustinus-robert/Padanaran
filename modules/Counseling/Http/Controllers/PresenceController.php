<?php

namespace Modules\Counseling\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Counseling\Http\Controllers\Controller;

use App\Models\References\GradeLevel;
use Modules\Academic\Models\AcademicClassroom;
use Modules\Academic\Models\AcademicClassroomPresence;
use Modules\Academic\Models\AcademicSubjectMeetPlan;
use Modules\Academic\Models\StudentSemesterAssessment;
use Modules\HRMS\Models\Employee;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Counseling\Http\Requests\Presences\PresenceBatchRequest;
use Modules\Counseling\Http\Requests\Presences\PresenceRequest;
use Modules\Boarding\Models\BoardingStudentsLeave;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicClassroomPresence::class);

        $grades = GradeLevel::where('grade_id', userGrades())->pluck('id');
        $acsem = $this->acsem->load([
            'classrooms' => function ($query) use ($grades) {
                $query->whereIn('level_id', $grades);
            }
        ]);

        $user = auth()->user();

        $presenceList = AcademicSubjectMeetPlan::$presenceList;

        $presences = AcademicClassroomPresence::with('presencer.user')
            ->where('classroom_id', $request->get('classroom'))
            ->whereHas('classroom', function ($classroom) use ($grades) {
                $classroom->whereIn('level_id', $grades);
            });

        if (!empty($request->dateSsearch)) {
            $date = \Carbon\Carbon::parse($request->dateSsearch)->toDateString(); // ambil Y-m-d
            $presences->whereDate('created_at', $date);
        }

        $presences = $presences->get();
        $currentClassroom = AcademicClassroom::with('stsems.student')->find($request->get('classroom'));
        $teachers = Employee::with([
            'user.meta',
            'contract.position.position',
            'schedulesTeachers',
        ])
        ->where('grade_id', userGrades())
        ->whenTrashed($request->get('trash'))
        ->whereHas('contract.position', function ($position) {
            $position->whereHas('position', function ($type) {
                $type->where('type', PositionTypeEnum::GURU);
            });
        })
        ->get()
        ->filter(function ($teacher) use ($request) {
            $classroomId = $request->get('classroom');
            $filterDate = $request->get('dateSsearch');

            foreach ($teacher->schedulesTeachers as $schedule) {
                $data = json_decode($schedule->dates, true);
                if (!is_array($data)) continue;

                // cek apakah key tanggal ada
                if (!isset($data[$filterDate])) continue;

                // ambil hanya data untuk tanggal itu
                $items = $data[$filterDate];
                foreach ($items as $item) {
                    if (!empty($item['rombel']) && in_array($classroomId, $item['rombel'])) {
                        return true;
                    }
                }
            }

            return false;
        });

        $teacherPresences = $teachers->map(function ($teacher) use ($presences) {
            $teacherPresence = $presences->map(function ($p) {
                $decoded = json_decode($p->presence, true); 
                return array_filter($decoded, fn($item) => isset($item['teacher']));
            })->flatten(1)
            ->filter(fn($item) => (string) ($item['teacher'] ?? '') === (string) $teacher->id);

            $teacherPresenceModel = $presences->first(function ($p) use ($teacher) {
                $items = json_decode($p->presence, true);
                foreach ($items as $item) {
                    if (isset($item['teacher']) && (string)$item['teacher'] === (string)$teacher->id) {
                        return true;
                    }
                }
                return false;
            });

            return [
                'teacher' => $teacher,
                'presence' => $teacherPresence->isEmpty() ? null : $teacherPresence,
                'originalPresence' => $teacherPresenceModel,
            ];
        });


        $date = $request->query('dateSsearch');
        $studentLeave = BoardingStudentsLeave::whereRaw("EXISTS (
            SELECT 1
            FROM jsonb_array_elements(dates) AS elem
            WHERE elem->>'d' = ?
        )", [$date])->get();

        $firstPresenceBefore7 = AcademicClassroomPresence::with('presencer.user')
            ->where('classroom_id', $request->get('classroom'))
            ->whereHas('classroom', function ($query) use ($grades) {
                $query->whereIn('level_id', $grades);
            });

        if (!empty($request->dateSsearch)) {
            $firstPresenceBefore7->whereDate('created_at', $date);
        }

        $firstPresenceBefore7 = $firstPresenceBefore7
            ->whereRaw("presenced_at::time < ?", ['07:00:00'])
            ->orderBy('presenced_at', 'asc')
            ->first();


        return view('counseling::presences.index', compact('acsem', 'user', 'presenceList', 'presences', 'currentClassroom', 'teachers', 'teacherPresences', 'studentLeave', 'firstPresenceBefore7'));
    }

    public function presencesSummary(Request $request){
        $teacherId = $request->teacher_id;
        $lessonId = $request->lesson_id;
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

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', AcademicClassroomPresence::class);

        $grades = GradeLevel::where('grade_id', userGrades())->pluck('id');

        $acsem = $this->acsem->load([
            'classrooms' => function ($query) use ($grades) {
                $query->whereIn('level_id', $grades);
            }
        ]);

        $grades = GradeLevel::where('grade_id', userGrades())->pluck('id');
        $user = auth()->user();

        $presenceList = AcademicSubjectMeetPlan::$presenceList;
        $types = StudentSemesterAssessment::$type;

        $currentClassroom = AcademicClassroom::with('stsems')->whereIn('level_id', $grades)->find($request->get('classroom'));

        return view('counseling::presences.create', compact('acsem', 'user', 'presenceList', 'types', 'currentClassroom'));
    }

    public function storeBatch(Request $request)
    {
        //$this->authorize('store', AcademicClassroomPresence::class);

        $data = $request->all();
        $presenceData = [];
        $now = now();

        $date = $data['date'] ?? $now->toDateString();

        // Ambil mapel pertama yang dikirim
        $firstMapelId = array_key_first($data['mapel']);
        $firstSession = $data['mapel'][$firstMapelId][0] ?? null;
        if (!$firstSession) {
            return redirect()->back()->withErrors('Tidak ada jam mapel yang valid.');
        }

        $startHour = explode(' - ', $firstSession)[0] ?? $firstSession;
        $teacher = $request->teacher;

        // Gabungkan tanggal + jam
        $dateTime = "$date $startHour";

        // Carbon tanpa ubah timezone, langsung format ISO 8601 Z
        $at = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $dateTime, config('app.timezone'))
                            ->format('Y-m-d\TH:i:s.u\Z');

        foreach ($data['presence'] as $semesterId => $p) {
            $presenceData[] = [
                'semester_id' => $semesterId,
                'student_id' => $p['student_id'],
                'presence' => (int) $p['type'],
                'name' => AcademicSubjectMeetPlan::$presenceList[(int)$p['type']] ?? 'Hadir',
                'at' => $at, // jam pertama
                'mapel_id' => $firstMapelId,
                'session' => $firstSession, // jam pertama
                'teacher' => $teacher
            ];
        }

        $user = auth()->user();

        $presence = new AcademicClassroomPresence([
            'classroom_id' => $data['classroom_id'],
            'presenced_at' => $now->format('Y-m-d H:i:s'),
            'presence' => $presenceData,
            'presenced_by' => $user->employee->id
        ]);

        $presence->save();

        return redirect($request->get('next', url()->previous()))
            ->with('success', 'Sukses, presensi '.$presence->classroom->full_name.' berhasil dibuat');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PresenceRequest $request)
    {
        $this->authorize('store', AcademicClassroomPresence::class);

        $data = $request->validated();

        $user = auth()->user();

        $presence = new AcademicClassroomPresence([
            'classroom_id' => $data['classroom_id'],
            'presenced_at' => date('Y-m-d H:i:s', strtotime($data['presenced_at'])),
            'presence' => AcademicSubjectMeetPlan::transformPresenceFormat($request->input('presence')),
            'presenced_by' => $user->employee->id
        ]);

        $presence->save();

        return redirect($request->get('next', url()->previous()))->with('success', 'Sukses, presensi '.$presence->classroom->full_name.' berhasil dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicClassroomPresence $presence, Request $request)
    {
        $this->authorize('update', AcademicClassroomPresence::class);
        return abort(404);
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicClassroomPresence $presence, Request $request)
    {
        $this->authorize('store', AcademicClassroomPresence::class);
        return abort(404);
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicClassroomPresence $presence)
    {
        $this->authorize('show', AcademicClassroomPresence::class);
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicClassroomPresence $presence)
    {
        $this->authorize('destroy', AcademicClassroomPresence::class);
        // $this->authorize('remove', $case);

        $tmp = $presence;
        $presence->delete();

        return redirect()->back()->with('success', 'Presensi rombel <strong>'.$tmp->classroom->full_name.'</strong> berhasil dihapus');
    }
}
