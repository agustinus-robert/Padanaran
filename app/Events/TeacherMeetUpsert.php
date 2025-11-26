<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\EventMeetTrait;

class TeacherMeetUpsert implements ShouldQueue
{
    use Dispatchable, SerializesModels, EventMeetTrait;

    public array $schedules;
    public string $key;

    public function __construct(array $payload, string $key)
    {
        $this->key = $key;
        $this->schedules = $this->processTeacherSchedules($payload);
    }
}
