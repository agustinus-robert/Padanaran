<?php

namespace Modules\Core\Enums;

enum PayrollSettingEnum: int
{
    case APPROVABLE = 1;
    case FORMULA = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::APPROVABLE => 'Persetujuan',
            self::FORMULA => 'Rumus hitung'
        };
    }

    /**
     * Get the label accessor with label() object
     */
    public function template(): string
    {
        return match ($this) {
            self::APPROVABLE => 'core::company.salaries.configs.includes.approval',
            self::FORMULA => 'core::company.salaries.configs.includes.calculation'
        };
    }
}
