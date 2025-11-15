<?php

namespace Modules\Core\Enums;

enum DepartementTypeEnum: int
{
    case BO = 1;
    case OP = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::BO => 'Back office',
            self::OP => 'Operational',
        };
    }
}
