<?php

namespace Modules\Core\Enums;

enum SalaryOperateEnum: int
{
    case NULL = 0;
    case ADDITION = 1;
    case REDUCTION = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::NULL => 'Tidak dihitung',
            self::ADDITION => 'Pendapatan',
            self::REDUCTION => 'Potongan',
        };
    }

    /**
     * Get the badge accessor with badge() object
     */
    public function badge(): string
    {
        return match ($this) {
            self::NULL => '<span class="badge bg-soft-secondary text-dark fw-normal">' . $this->label() . '</span>',
            self::ADDITION => '<span class="badge bg-soft-success text-success fw-normal">' . $this->label() . '</span>',
            self::REDUCTION => '<span class="badge bg-soft-danger text-white fw-normal">' . $this->label() . '</span>'
        };
    }

    /**
     * Get the badge accessor with icon() object
     */
    public function icon(): string
    {
        return match ($this) {
            self::NULL => 'mdi mdi-alert-circle',
            self::ADDITION => 'mdi mdi-plus-circle',
            self::REDUCTION => 'mdi mdi-minus-circle'
        };
    }

    /**
     * Get the color accessor with color() object
     */
    public function color(): string
    {
        return match ($this) {
            self::NULL => 'dark',
            self::ADDITION => 'success',
            self::REDUCTION => 'danger'
        };
    }

    /**
     * Get the symbol accessor with symbol() object
     */
    public function symbol(): null|string
    {
        return match ($this) {
            self::NULL => null,
            self::ADDITION => '+',
            self::REDUCTION => '-',
        };
    }
}
