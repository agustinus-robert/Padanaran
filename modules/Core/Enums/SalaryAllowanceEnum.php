<?php

namespace Modules\Core\Enums;

enum SalaryAllowanceEnum: int
{
    case BASIC = 1;
    case ABSOLUTE = 2;
    case RELATIVE = 3;
    case INSENTIVE = 4;
    case THR = 5;
    case G13 = 6;
    case PREMI = 7;
    case ADDITIONAL = 8;
    case OTHER = 99;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::BASIC => 'Gaji pokok',
            self::ABSOLUTE => 'Tunjangan tetap',
            self::RELATIVE => 'Tunjangan tidak tetap',
            self::INSENTIVE => 'Insentif',
            self::THR => 'THR',
            self::G13 => 'Gaji ke-13',
            self::PREMI => 'Premi',
            self::ADDITIONAL => 'Tunjangan pekerjaan lainnya',
            self::OTHER => 'Lain-lain'
        };
    }
}
