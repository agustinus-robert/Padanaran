<?php

namespace Modules\Core\Enums;

enum PositionLevelEnum: int
{
    case PENGASUH = 1;
    case KEPALASEKOLAH = 2;
    case HUMAS = 3;
    case BK = 4;
    case GURU = 5;
    case STAFF = 6;
    case NONSTAFF = 7;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::PENGASUH => 'Pengasuh',
            self::KEPALASEKOLAH => 'Kepala Sekolah',
            self::HUMAS => 'Humas',
            self::BK => 'BK',
            self::GURU => 'Guru',
            self::STAFF => 'Staf Fungsional',
            self::NONSTAFF => 'Non-staff',
        };
    }
}
