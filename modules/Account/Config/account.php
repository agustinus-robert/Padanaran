<?php

return [

	'name' => 'Account - ' . config('app.name'),

	'freelancers' => [
		'supplier_categories' => [
			1 => 'Individual Freelancer',
			2 => 'Group of freelancers',
			3 => 'Agency/LSP'
		],
		'tools' => [
			[
				'id' => 1,
				'kd' => 'os',
				'summary' => 'Operating System',
			],
			[
				'id' => 2,
				'kd' => 'software',
				'summary' => 'Software',
			],
			[
				'id' => 3,
				'kd' => 'platform',
				'summary' => 'Platform',
			]
		],
		'interpretation' => [
			[
				'id' => 1,
				'summary' => 'Simultaneous interpretation',
			],
			[
				'id' => 2,
				'summary' => 'Consecutive interpretation',
			],
			[
				'id' => 3,
				'summary' => 'Remote interpretation',
			],
			[
				'id' => 4,
				'summary' => 'On-site interpretation',
			],
			[
				'id' => 5,
				'summary' => 'Whispered interpretation',
			],
			[
				'id' => 6,
				'summary' => 'Conference interpretation',
			]
		]
	]

];
