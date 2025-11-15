<?php

namespace Modules\Admin\Enums;

enum ToolConditionEnum: int
{
    case Baru   = 1;
    case Bekas     = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::Baru   => 'Baru',
            self::Bekas   => 'Bekas',
        };
    }
}
