<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Models\AcademicSubjectSchedule;

class TeacherScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $schedules;
    public string $key;

    public function __construct(array $schedules, string $key)
    {
        $this->schedules = $schedules;
        $this->key = $key;
    }

    public function handle(): void
    {
        $total = count($this->schedules);
        $i = 0;

        foreach ($this->schedules as $schedule) {

            if (!isset(
                $schedule['empl_id'],
                $schedule['subject_id'],
                $schedule['teacher_id'],
                $schedule['classroom_id'],
                $schedule['start_at'],
                $schedule['end_at'],
                $schedule['day'],
                $schedule['date']
            )) {
                continue;
            }

            AcademicSubjectSchedule::where([
                'subject_id'   => $schedule['subject_id'],
                'teacher_id'   => $schedule['teacher_id'],
                'classroom_id' => $schedule['classroom_id'],
                'start_at'     => $schedule['start_at'],
                'end_at'       => $schedule['end_at'],
                'day'          => $schedule['day'],
                'date'         => $schedule['date'],
            ])->delete();

            AcademicSubjectSchedule::create($schedule);

            $i++;
            $percent = intval(($i / $total) * 100);
            cache()->put("progress_{$this->key}", $percent);
        }

        cache()->put("progress_{$this->key}", 100);
    }
}
