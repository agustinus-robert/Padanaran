<?php

namespace Modules\Core\Enums;

enum PaymentCycleEnum :int
{
    case NONE = 0;
    case YEARS3X1 = 1;
    case YEARSX1 = 2;
    case MONTHX1 = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match($this) 
        {
            self::NONE => 'Menyesuaikan Yayasan',
            self::YEARS3X1 => '1x dalam 3 tahun',
            self::YEARSX1 => '1x setiap tahun',
            self::MONTHX1 => '1x setiap bulan'
        };
    }
}