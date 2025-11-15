<?php

use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Enums\AdditionalTypeEnum;

return [

    'name' => 'Finance - ' . config('app.name'),

    'features' => [
        'loans' => [
            'approvable_steps' => [
                [
                    'type' => 'parent_position_level',
                    'value' => 4
                ],
                [
                    'type' => 'employee_position_by_kd',
                    'value' => ['finances-mgr']
                ],
                [
                    'type' => 'parent_position_level',
                    'value' => 3
                ]
            ],
            'state' => 'disabled',
        ],
        'pom' => [
            'groups' => [
                'coordinator' => [
                    [
                        'type'   => 'position_level',
                        'query'  => 'where',
                        'clause' => ['level', 5],
                    ],
                ],
                'projectmanager' => [
                    [
                        'type'   => 'position_level',
                        'query'  => 'where',
                        'clause' => ['level', 6]
                    ],
                ],
                'editor' => [
                    [
                        'type'   => 'position_id',
                        'query'  => 'whereIn',
                        'clause' => ['id', [13, 14]]
                    ],
                ],
                'translator' => [
                    [
                        'type'   => 'position_id',
                        'query'  => 'whereIn',
                        'clause' => ['id', [15, 16]]
                    ],
                ],
                'support' => [
                    [
                        'type'   => 'position_level',
                        'query'  => 'whereIn',
                        'clause' => ['level', [5, 6, 7]]
                    ],
                ]
            ]
        ],
        'additional_overtime' => [
            'options' => [
                [
                    'id' => AdditionalTypeEnum::ADDITIONAL,
                    'label' => AdditionalTypeEnum::ADDITIONAL->label(),
                    'option' => [
                        'meal_input' => true,
                        'time_input' => 'start_to_end',
                    ],
                    'rate' => [
                        'type' => 'fixed_rate',
                        'value' => ['getOvertimeSalaryViaActiveTemplate', '18125', '23563'],
                        'except' => ['21', '22', '38'],
                    ],
                    'meal' => [
                        'type' => 'salary_rate',
                        'value' => 'getMealSalaryViaActiveTemplate'
                    ],
                    'method' => [1, 2],
                    'approvable_steps' => [
                        [
                            'type' => 'parent_position_level',
                            'required' => true,
                            'value' => 5
                        ],
                        [
                            'type' => 'parent_position_level',
                            'required' => true,
                            'value' => 4
                        ],
                        [
                            'type' => 'parent_position_level',
                            'required' => true,
                            'value' => 3
                        ]
                    ],
                    'approvable_enum_available' => [
                        ApprovableResultEnum::PENDING,
                        ApprovableResultEnum::APPROVE,
                        ApprovableResultEnum::REJECT
                    ],
                ],
                [
                    'id' => AdditionalTypeEnum::FEASTDAY,
                    'label' => AdditionalTypeEnum::FEASTDAY->label(),
                    'option' => [
                        'time_input' => 'start_to_end',
                    ],
                    'rate' => [
                        'type' => 'salary_rate',
                        'value' => 'getOvertimeSalaryViaActiveTemplate'
                    ],
                    'meal' => [
                        'type' => 'salary_rate',
                        'value' => 'getMealSalaryViaActiveTemplate'
                    ],
                    'transport' => [
                        'type' => 'salary_rate',
                        'value' => 'getTransportSalaryViaActiveTemplate'
                    ],
                    'method' => [1],
                    'approvable_steps' => [
                        [
                            'type' => 'parent_position_level',
                            'required' => true,
                            'value' => 5
                        ],
                        [
                            'type' => 'parent_position_level',
                            'required' => true,
                            'value' => 4
                        ],
                        [
                            'type' => 'parent_position_level',
                            'required' => true,
                            'value' => 3
                        ]
                    ],
                    'approvable_enum_available' => [
                        ApprovableResultEnum::PENDING,
                        ApprovableResultEnum::APPROVE,
                        ApprovableResultEnum::REJECT
                    ],
                ],
                [
                    'id' => AdditionalTypeEnum::VENUE,
                    'label' => AdditionalTypeEnum::VENUE->label(),
                    'option' => [
                        'time_input' => 'start_to_end',
                    ],
                    'rate' => [
                        'type' => 'fixed_rate',
                        'value' => ['18125', '23563']
                    ],
                    'meal' => [
                        'type' => 'fixed_rate',
                        'value' => ['15000', '20000']
                    ],
                    'load' => [
                        'type' => 'fixed_rate',
                        'value' => '23563'
                    ],
                    'method' => [1, 2],
                    'approvable_steps' => [
                        [
                            'type' => 'employee_position_by_kd',
                            'required' => true,
                            'value' => ['ga-crd']
                        ],
                        [
                            'type' => 'employee_position_by_kd',
                            'required' => true,
                            'value' => ['marketings-mgr']
                        ],
                        [
                            'type' => 'employee_position_by_kd',
                            'required' => true,
                            'value' => ['directors']
                        ]
                    ],
                    'approvable_enum_available' => [
                        ApprovableResultEnum::PENDING,
                        ApprovableResultEnum::APPROVE,
                        ApprovableResultEnum::REJECT
                    ],
                ]
            ],
            'access' => [
                [
                    'type' => 'enabled',
                    'value' => [3, 5, 6, 7]
                ]
            ],
            'approver' => [
                3,
                4,
                5
            ]
        ]
    ]

];
