<?php

use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Account\Models\User;
use Modules\Core\Enums\VacationTypeEnum;

return [

    'name' => 'Core - ' . config('app.name'),

    'features' => [

        'services' => [

            'vacations' => [
                'view_quotas' => [
                    1,
                    2
                ],
                'approvable_steps' => [
                    [
                        'type' => 'parent_position_level',
                        'value' => 2
                    ],
                    [
                        'type' => 'parent_position_level',
                        'value' => 1
                    ]
                ]
            ],

            'leaves' => [
                'approvable_enum_available' => [
                    ApprovableResultEnum::PENDING,
                    ApprovableResultEnum::APPROVE,
                    ApprovableResultEnum::REJECT
                ],
                'approvable_steps' => [
                    [
                        'type' => 'parent_position_level',
                        'required' => false,
                        'value' => 2,
                        'only' => [
                            2
                        ],
                    ],
                    [
                        'type' => 'parent_position_level',
                        'required' => true,
                        'value' => 1,
                        'only' => [
                            1
                        ],
                    ],
                ]
            ],
            'leaves_student' => [
                'approvable_enum_available' => [
                    ApprovableResultEnum::PENDING,
                    ApprovableResultEnum::APPROVE,
                    ApprovableResultEnum::REJECT
                ],
                'approvable_steps' => [
                    [
                        'type' => 'parent_position_level',
                        'required' => true,
                        'value' => 1
                    ],
                    [
                        'type' => 'parent_position_level',
                        'required' => true,
                        'value' => 8,
                    ],
                    [
                        'type' => 'parent_position_level',
                        'required' => false,
                        'value' => [4, 7],
                    ]
                ]
            ],

            'reimbursements' => [
                'approvable_enum_available' => [
                    ApprovableResultEnum::PENDING,
                    ApprovableResultEnum::APPROVE,
                    ApprovableResultEnum::REJECT
                ],
                'approvable_steps' => [
                    [
                        'type' => 'employee_position_by_kd',
                        'value' => ['finance_manager']
                    ]
                ]
            ],

            'overtimes' => [
                'approvable_enum_available' => [
                    ApprovableResultEnum::PENDING,
                    ApprovableResultEnum::APPROVE,
                    ApprovableResultEnum::REJECT
                ],
                'approvable_steps' => [
                    [
                        'type' => 'parent_position_level',
                        'required' => false,
                        'value' => 2
                    ],
                    [
                        'type' => 'parent_position_level',
                        'required' => true,
                        'value' => 1
                    ],
                ]
            ],

            'outworks' => [
                'approvable_steps' => [
                    [
                        'type' => 'parent_position_level',
                        'required' => false,
                        'value' => 2
                    ],
                    [
                        'type' => 'parent_position_level',
                        'required' => true,
                        'value' => 1
                    ],
                ]
            ],

            'schedules' => [
                'approvable_enum_available' => [
                    ApprovableResultEnum::PENDING,
                    ApprovableResultEnum::APPROVE,
                    ApprovableResultEnum::REJECT
                ],
                'approvable_steps' => [
                    [
                        'type' => 'parent_position_level',
                        'value' => 2
                    ]
                ]
            ],

            'recapteacher' => [
                'approvable_enum_available' => [
                    ApprovableResultEnum::PENDING,
                    ApprovableResultEnum::APPROVE,
                    ApprovableResultEnum::REJECT
                ],
                'approvable_steps' => [
                    [
                        'type' => 'employee_position_by_kd',
                        'value' => ['humas']
                    ]
                ]
            ],

            'recapcoordinator' => [
                'approvable_enum_available' => [
                    ApprovableResultEnum::PENDING,
                    ApprovableResultEnum::APPROVE,
                    ApprovableResultEnum::REJECT
                ],
                'approvable_steps' => [
                    [
                        'type' => 'employee_position_by_kd',
                        'value' => ['finance_manager']
                    ]
                ]
            ],
            'salaries' => [
                'validation_notify' => 10000002,
            ]
        ],
        'recapitulations' => [
            'attendances' => [
                'Rekapitulasi keseluruhan' => [
                    'Jumlah hari' => 'days',
                    'Hari efektif' => 'workdays',
                    'Hari libur nasional' => 'holidays',
                    'Jumlah presensi' => 'attendance_total',
                    'Jumlah tepat waktu' => 'ontime_total',
                    'Jumlah terlambat' => 'late_total'
                ],
                'Rekapitulasi berdasar lokasi' => [
                    'Presensi WFO' => 'presence.wfo',
                    'Presensi WFA' => 'presence.wfa',
                    'Jumlah WFO ke WFA' => 'presence.move'
                ],
                'Rekapitulasi tunjangan kehadiran' => [
                    'Presensi WFO Ontime' => 'ontime.wfo',
                    'Presensi WFA Ontime' => 'ontime.wfa',
                    'Jumlah WFA ke WFO Ontime' => 'ontime.move',
                ],
                'Rekapitulasi perizinan' => array_merge([
                    'Jumlah izin' => 'unpresence.leave',
                    'Kompensasi cuti' => 'unpresence.cashable_vacation'
                ], ...array_map(fn($vacation) => [
                    'Jumlah ' . $vacation->label() => 'unpresence.vacation.' . strtolower($vacation->name)
                ], VacationTypeEnum::cases())),
                'Rekapitulasi lembur' => [
                    'Jumlah lembur di luar jam kerja' => 'overtime.works',
                    'Jumlah lembur kelebihan hari' => 'overtime.overdays',
                    'Jumlah lembur tanggal merah' => 'overtime.holidays',
                    'Jumlah lembur keseluruhan' => 'overtime.total'
                ]
            ]
        ],

        'loans' => [
            [
                'label' => 'az',
                'name'  => 'Urutan',
                'icon'  => 'mdi-sort',
                'type'  => 'number',
            ],
            [
                'label' => 'tenor',
                'name'  => 'Jangka Waktu',
                'icon'  => 'mdi-calendar',
                'type'  => 'number',
            ],
            [
                'label' => 'interest',
                'name'  => 'Suku Bunga',
                'icon'  => 'mdi-percent',
                'type'  => 'number',
            ],
            [
                'label' => 'divider',
                'name'  => 'Pembagi gaji pokok',
                'icon'  => 'mdi-ab-testing',
                'type'  => 'number',
            ],
            [
                'label' => 'visible',
                'name'  => 'Tampilkan ke pengguna',
                'icon'  => 'mdi-eye',
                'type'  => 'radio',
            ],
            [
                'label' => 'file',
                'name'  => 'Unggah Berkas',
                'icon'  => 'mdi-file',
                'type'  => 'checkbox',
            ],
            [
                'label' => 'only_permanent_empl',
                'name'  => 'Hanya Karyawan Tetap',
                'icon'  => 'mdi-account',
                'type'  => 'checkbox',
            ],
            [
                'label' => 'multiplied_by_tenor',
                'name'  => 'Dikali Jangka Waktu',
                'icon'  => 'mdi-multiply',
                'type'  => 'checkbox',
            ],
        ]
    ]
];
