<?php

namespace Modules\Core\Enums;

enum PositionTypeEnum: int
{
    // Sekolah
    case PENGASUH = 1;
    case PENGURUS = 2;
    case KEPALASEKOLAH = 3;
    case HUMAS = 4;
    case TATAUSAHA = 5;
    case KEUANGAN = 6;
    case GURU = 7;
    case GURUBK = 8;
    case MURID = 9;

    // Unit Usaha
    case KASIRTOKO = 10;
    case KASIRSWALAYAN = 11;
    case SUPPLIER = 12;

    // Umum
    case ADMIN = 13;

    // Pesantren    
    case USTADZ = 14;
    case USTADZAH = 15;
    case ADMINPONDOK = 16;
    case KEAMANAN = 17;
    case KOKI = 18;

    /**
     * Get the label accessor
     */
    public function label(): string
    {
        return match ($this) {
            self::KEPALASEKOLAH => 'Kepala Sekolah',
            self::HUMAS => 'Humas',
            self::TATAUSAHA => 'Tata Usaha',
            self::GURU => 'Guru',
            self::GURUBK => 'Guru BK',
            self::MURID => 'Murid',
            self::KASIRTOKO => 'Kasir Toko',
            self::PENGURUS => 'Pengurus',
            self::KASIRSWALAYAN => 'Kasir Swalayan',
            self::SUPPLIER => 'Supplier',
            self::ADMIN => 'Admin',
            self::PENGASUH => 'Pengasuh',
            self::KASIRSWALAYAN => 'Kasir Swalayan',
            self::USTADZ => 'Ustadz',
            self::USTADZAH => 'Ustadzah',
            self::ADMINPONDOK => 'Admin Pondok',
            self::KEAMANAN => 'Keamanan',
            self::KOKI => 'Koki',
            self::FINANCE => 'Keuangan'
        };
    }

    /**
     * Get the key accessor
     */
    public function key(): string
    {
        return match ($this) {
            self::KEPALASEKOLAH => 'kepala_sekolah',
            self::HUMAS => 'humas',
            self::TATAUSAHA => 'tata_usaha',
            self::GURU => 'guru',
            self::GURUBK => 'guru_bk',
            self::MURID => 'murid',
            self::KASIRTOKO => 'kasir_toko',
            self::KASIRSWALAYAN => 'kasir_swalayan',
            self::SUPPLIER => 'supplier',
            self::ADMIN => 'admin',
            self::PENGASUH => 'pengasuh',
            self::WAKILPENGASUH => 'wakil_pengasuh',
            self::USTADZ => 'ustadz',
            self::MUSYRIF => 'musyrif',
            self::ADMINPONDOK => 'admin_pondok',
            self::KEAMANAN => 'keamanan',
            self::KOKI => 'koki',
        };
    }
}
