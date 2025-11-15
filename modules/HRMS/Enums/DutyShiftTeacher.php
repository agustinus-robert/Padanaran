<?php

namespace Modules\HRMS\Enums;

use Carbon\Carbon;

enum DutyShiftTeacher: int
{
    case DAY = 1;

    public function label(): string
    {
        return match ($this) {
            self::DAY => 'Petugas Piket',
        };
    }

    public function defaultTime(): array
    {
        return match ($this) {
            self::DAY => [
                'in' => ['07:00'],
                'out' => ['16:00']
            ]
        };
    }

    public function startTime(): string
    {
        return $this->defaultTime()['in'];
    }

    public function endTime(): string
    {
        return $this->defaultTime()['out'];
    }

    public function timeRange(): array
    {
        return [$this->startTime(), $this->endTime()];
    }

    public function teachingHours(): int
    {
        return 2;
    }
}
