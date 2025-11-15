<?php

namespace Modules\Core\Enums;

enum PaymentStatusEnum: int
{
    case WAITING = 1;
    case SUCCESS = 2;
    case EXPIRE  = 3;
    case CANCEL  = 4;
    case PENDING = 5;
    case FAILED  = 6;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::WAITING => 'Menunggu pembayaran',
            self::SUCCESS => 'Pembayaran berhasil',
            self::EXPIRE  => 'Kadaluarsa',
            self::CANCEL  => 'Dibatalkan',
            self::PENDING => 'Pembayaran dalam antrian',
            self::FAILED  => 'Pembayaran gagal',
        };
    }

    /**
     * Get the color accessor with color() object
     */
    public function color(): string
    {
        return match ($this) {
            self::WAITING => 'info text-info',
            self::SUCCESS => 'success text-success',
            self::EXPIRE  => 'secondary text-dark',
            self::CANCEL  => 'warning text-warning',
            self::PENDING => 'primary text-primary',
            self::FAILED  => 'danger text-danger',
        };
    }
}
