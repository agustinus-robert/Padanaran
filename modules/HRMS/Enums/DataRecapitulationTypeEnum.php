<?php

namespace Modules\HRMS\Enums;

enum DataRecapitulationTypeEnum: int
{
    case ATTENDANCE = 1;
    case OVERTIME = 2; // Tambahan
    case OUTWORK = 3; // insentif
    case REIMBURSEMENT = 4;
    case G13 = 5;
    case THR = 6;
    case BONUS = 7;
    case HONOR = 8;
    case DEDUCTION = 9;
    case PPH = 10;
    case COORD = 11;
    case ABOARD = 12;
    case TEACHERLOYALTY = 13;
    Case TEACHERCLASS = 14;
    case BOARDLOYALTY = 15;
    case PAT = 16;  // per jam
    case TEACHERDUTY = 17; // per jam
    case UKM = 18;
    case TEACHERINVIGILATOR =  19; //per lembar

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::ATTENDANCE => 'Kehadiran, izin, cuti, dan lembur',
            self::OVERTIME => 'Lembur',
            self::OUTWORK => 'Kegiatan lainnya',
            self::REIMBURSEMENT => 'Reimbursement',
            self::G13 => 'Gaji ke-13',
            self::THR => 'Tunjangan Hari Raya',
            self::BONUS => 'Bonus tahunan',
            self::HONOR => 'Rapelan',
            self::DEDUCTION => 'Potongan',
            self::PPH => 'Potongan PPh 21',
            self::COORD => 'Koordinator siswa',
            self::ABOARD => 'Kehadiran penugasan luar',
            self::TEACHERLOYALTY => 'Tunjangan Pengabdian Guru',
            self::TEACHERCLASS => 'Tunjangan Wali Kelas',
            self::BOARDLOYALTY => 'Tunjangan Pengabdian Pondok',
            self::PAT => 'Panitia Ujian',
            self::TEACHERDUTY => 'Guru Piket',
            self::UKM => 'UKM',
            self::TEACHERINVIGILATOR => 'Guru Pengawas'
        };
    }
}
