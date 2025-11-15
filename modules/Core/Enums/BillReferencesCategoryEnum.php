<?php

namespace Modules\Core\Enums;

enum BillReferencesCategoryEnum :int
{
    case BOARDINGPAY = 1;
    case SCHOOLPAY = 2;
    case DEVELOPPAY = 3;
    case CYCLEPAY = 4;
   // case CYCLEPAY = 5;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match($this) 
        {
            self::BOARDINGPAY => 'Biaya Pesantren',
            self::SCHOOLPAY => 'Biaya Sekolah',
            self::DEVELOPPAY => 'Infaq Pembangunan Pesantren',
            self::CYCLEPAY => 'Biaya Bulanan/Tahunan'
            //  self::SIGNPAY => 'Total Biaya Masuk',
        };
    }
}