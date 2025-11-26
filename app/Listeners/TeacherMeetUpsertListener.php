<?php

namespace App\Listeners;

use App\Events\TeacherMeetUpsert;
use App\Jobs\TeacherScheduleJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeacherMeetUpsertListener implements ShouldQueue
{
    public function handle(TeacherMeetUpsert $event)
    {
        TeacherScheduleJob::dispatch(
            $event->schedules,
            $event->key
        );
    }
}
