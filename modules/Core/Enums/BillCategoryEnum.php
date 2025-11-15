<?php

namespace Modules\Core\Enums;

enum BillCategoryEnum :int
{
    case DEBIT = 1;
    case CREDIT = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match($this) 
        {
            self::DEBIT => 'Debit',
            self::CREDIT => 'Credit',
        };
    }
}