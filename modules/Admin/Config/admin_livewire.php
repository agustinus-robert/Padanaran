<?php

use App\Enums\ApprovableResultEnum;

return [

	'name' => 'Fasilitas - ' . config('app.name'),

	'features' => [

		'lease' => [

			'approvable_enum_available' => [
				ApprovableResultEnum::PENDING,
				ApprovableResultEnum::APPROVE,
				ApprovableResultEnum::REJECT,
				ApprovableResultEnum::REVISION
			],

			'approvable_steps' => [
				[
					'type' => 'giver_by_user',
					'value' => ''
				],
			],

			'approvers' => [
				1, 3, 53, 27
			],

			'approver_arrays' => [
				'devices' => [
					'type' 	 => 'user_by_id',
					'for' 	 => [1, 3],
					'value'  => [53]
				],
				'room' => [
					'type'   => 'user_by_id',
					'for'    => [2, 4],
					'value'  => [3]
				],
			],
		],
	],

	'setting' => [
		'proposal_approvable',
		'inventory_control_approvable',
		'api_user_endpoint',
		'api_employee_endpoint',
	]

];
