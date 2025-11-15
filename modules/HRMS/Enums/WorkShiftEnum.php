<?php

namespace Modules\HRMS\Enums;

enum WorkShiftEnum: int
{
    case NORMAL = 1;
    case NOON = 2;
    case NIGHT = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::NORMAL => 'Shift pagi',
            self::NOON => 'Shift siang',
            self::NIGHT => 'Shift malam'
        };
    }

    /**
     * Get the tolerance accessor with tolerance() object
     */
    public function tolerance(): array
    {
        return match ($this) {
            self::NORMAL => [
                'in' => [null, null],
                'out' => [null, null]
            ],
            self::NOON => [
                'in' => ['2 hours', (60 * 60 + 59) . ' seconds'],
                'out' => [null, null]
            ],
            self::NIGHT => [
                'in' => ['2 hours', (60 * 60 + 59) . ' seconds'],
                'out' => [null, null]
            ]
        };
    }

    /**
     * Get the tolerance accessor with tolerance() object
     */
    public function defaultTime(): array
    {
        return match ($this) {
            self::NORMAL => [
                'in' => ['07:00'],
                'out' => ['15:00']
            ],
            self::NOON => [
                'in' => ['11:00'],
                'out' => ['19:00']
            ],
            self::NIGHT => [
                'in' => ['23:00'],
                'out' => ['07:00']
            ]
        };
    }
}
