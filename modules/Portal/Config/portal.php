<?php

return [

	'name' => 'Portal - ' . config('app.name'),

	'required_profile' => [
		'name', // Nama lengkap
		'meta.profile_nik', // NIK
		'meta.profile_marriage', // Status kawin
		'meta.profile_couple', // Data suami/istri
		'meta.profile_couple_as', // Data sebagai suami/istri
		'meta.children', // Data anak
		'meta.insurances', // BPJS
		'meta.parents', // Data orangtua
		'meta.educations' // Data pendidikan
	]

];
