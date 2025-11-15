<?php

namespace Modules\Core\Enums;

enum PositionStudentLevelEnum: int
{
    case PENGURUS = 1;
    case HUMAS = 3;
    case BK = 8;
    case GURU = 5;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::PENGURUS => 'Pengurus',
            self::BK => 'Guru BK',
            self::HUMAS, self::GURU => 'Wali Kelas',
        };
    }
}
