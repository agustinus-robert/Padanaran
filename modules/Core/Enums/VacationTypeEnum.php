<?php

namespace Modules\Core\Enums;

enum VacationTypeEnum: int
{
    case YEARLY = 1;
    case SPECIAL = 2;
    case HOLIDAY = 3;
    // case FIVEYEARLY = 4;
    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::YEARLY => 'Cuti Tahunan',
            self::SPECIAL => 'Cuti Khusus',
            self::HOLIDAY => 'Libur Hari Raya',
            // self::FIVEYEARLY => 'Cuti 5 Tahunan'
        };
    }

    /**
     * Set the visibility
     */
    public function quotaVisibility(): bool
    {
        return match ($this) {
            //self::FIVEYEARLY => true,
            self::YEARLY, self::HOLIDAY, 
            self::SPECIAL => true
        };
    }

    /**
     * getVisibleOnly
     */
    public static function getVisibleOnly(): array
    {
        return array_values(array_filter(self::cases(), fn($type) => $type->quotaVisibility()));
    }
}
