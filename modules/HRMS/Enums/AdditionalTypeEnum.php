<?php

namespace Modules\HRMS\Enums;

enum AdditionalTypeEnum: int
{
    case ADDITIONAL = 1;
    case FEASTDAY = 2;
    case VENUE = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::ADDITIONAL => 'Lembur pekerjaan tambahan',
            self::FEASTDAY => 'Lembur hari raya keagamaan',
            self::VENUE => 'Venue Organizer'
        };
    }
}
