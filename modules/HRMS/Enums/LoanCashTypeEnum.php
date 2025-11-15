<?php

namespace Modules\HRMS\Enums;

enum LoanCashTypeEnum: int
{
    case CASH = 1;
    case TRANSFER = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Tunai',
            self::TRANSFER => 'Non-tunai'
        };
    }
}
