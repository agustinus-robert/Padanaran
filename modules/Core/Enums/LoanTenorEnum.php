<?php

namespace Modules\Core\Enums;

enum LoanTenorEnum: int
{
    case DAILY = 1;
    case WEEKLY = 2;
    case MONTHLY = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::DAILY => 'Hari',
            self::WEEKLY => 'Minggu',
            self::MONTHLY => 'Bulan'
        };
    }

    /**
     * Get the operator accessor with operator() object, based Carbon functions
     */
    public function operator($type = false): string
    {
        return match ($this) {
            self::DAILY => $type == 'Carbon' ? 'addDays' : 'days',
            self::WEEKLY => $type == 'Carbon' ? 'addWeeks' : 'weeks',
            self::MONTHLY => $type == 'Carbon' ? 'addMonths' : 'months'
        };
    }
}
