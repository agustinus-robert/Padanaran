<?php

namespace Modules\HRMS\Enums;

enum LoanMethodEnum: int
{
    case PAYROLL = 1;
    case DIRECT = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::PAYROLL => 'Pemotongan gaji',
            self::DIRECT => 'Pembayaran langsung'
        };
    }

    /**
     * Get the description accessor with description() object
     */
    public function description(): string
    {
        return match ($this) {
            self::PAYROLL => 'Pelunasan ditandai ketika pembuatan slip gaji',
            self::DIRECT => 'Akan langsung ditandai sebagai lunas jika nominal pembayaran telah mencapai nominal tagihan'
        };
    }

    /**
     * Get the defaultPaidAt accessor with defaultPaidAt() object
     */
    public function defaultPaidAt(): string
    {
        return match ($this) {
            self::PAYROLL => cmp_cutoff(1)->format('Y-m-d 09:00:00'),
            self::DIRECT => date('Y-m-d H:i:s'),
        };
    }
}
