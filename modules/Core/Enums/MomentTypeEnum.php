<?php

namespace Modules\Core\Enums;

enum MomentTypeEnum :int
{
    case NATIONAL = 1;
    case COMPANY = 2;
    case SPECIAL = 3;
    case MASSLEAVE = 4;
    case CEREMONY = 5;
    case OTHER = 6;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match($this) 
        {
            self::NATIONAL => 'Libur nasional',
            self::COMPANY => 'Libur kantor/perusahaan',
            self::SPECIAL => 'Libur khusus',
            self::MASSLEAVE => 'Cuti bersama',
            self::CEREMONY => 'Hari peringatan',
            self::OTHER => 'Lainnya',
        };
    }
}