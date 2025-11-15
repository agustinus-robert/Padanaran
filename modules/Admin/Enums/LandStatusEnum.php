<?php

namespace Modules\Admin\Enums;

enum LandStatusEnum: int
{
    case HGU   = 1;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::HGU   => 'Tanah HGU',
        };
    }
}
