<?php

namespace Modules\HRMS\Enums;

use Carbon\Carbon;

enum TeacherShiftEnum: int
{
    case SHIFT1 = 1;
    case SHIFT2 = 2;
    case SHIFT3 = 3;
    case SHIFT4 = 4;
    case SHIFT5  = 5;
    case SHIFT6  = 6;
    case SHIFT7  = 7;
    case SHIFT8  = 8;
    case SHIFT9  = 9;
    case SHIFT10 = 10;

    public function label(): string
    {
        return match ($this) {
            self::SHIFT1 => 'Jam Ke-1',
            self::SHIFT2 => 'Jam Ke-2',
            self::SHIFT3 => 'Jam Ke-3',
            self::SHIFT4 => 'Jam Ke-4',
            self::SHIFT5  => 'Jam Ke-5',
            self::SHIFT6  => 'Jam Ke-6',
            self::SHIFT7  => 'Jam Ke-7',
            self::SHIFT8  => 'Jam Ke-8',
            self::SHIFT9  => 'Jam Ke-9',
            self::SHIFT10 => 'Jam ke-10'
        };
    }

    public function defaultTime(): array
    {
        return match ($this) {
            self::SHIFT1 => [
                'in' => ['07:10'],
                'out' => ['07:50']
            ],
            self::SHIFT2 => [
                'in' => ['07:50'],
                'out' => ['08:30']
            ],
            //4 jam
            self::SHIFT3 => [
                'in' => ['08:30'],
                'out' => ['09:10']
            ],
            self::SHIFT4 => [
                'in' => ['09:10'],
                'out' => ['10:50']
            ],
            //4 jam
            self::SHIFT5 => [
                'in' => ['10:05'],
                'out' => ['10:45']
            ],
            self::SHIFT6 => [
                'in' => ['10:45'],
                'out' => ['11:25']
            ],
            self::SHIFT7 => [
                'in' => ['11:25'],
                'out' => ['12:05']
            ],
            self::SHIFT8 => [
                'in' => ['12:05'],
                'out' => ['12:45']
            ],
            self::SHIFT9 => [
                'in' => ['13:25'],
                'out' => ['14:05']
            ],
            self::SHIFT10 => [
                'in' => ['14:05'],
                'out' => ['14:45']
            ],
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
