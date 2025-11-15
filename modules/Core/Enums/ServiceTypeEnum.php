<?php

namespace Modules\Core\Enums;

enum ServiceTypeEnum: int
{
    case DEFAULT = 1;
    case MEAL = 2;
    case SERVICE = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'Tidak ada',
            self::MEAL => 'Konsumsi',
            self::SERVICE => 'Akomodasi'
        };
    }
}
