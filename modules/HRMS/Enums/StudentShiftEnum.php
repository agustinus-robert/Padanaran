<?php

namespace Modules\HRMS\Enums;

enum StudentShiftEnum: int
{
    case MORNING = 1;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::MORNING => 'Pagi'
        };
    }

    /**
     * Get the tolerance accessor with tolerance() object
     */
    public function defaultTimes(): array
    {
        return match ($this) {
            self::MORNING => [
                'in' => ['07:00'],
                'out' => ['16:00']
            ]
        };
    }

    /**
     * Get the tolerance accessor with tolerance() object
     */
    public function tolerance(): array
    {
        return match ($this) {
            self::MORNING => [
                'in' => [null, null],
                'out' => [null, null]
            ]
        };
    }
}
