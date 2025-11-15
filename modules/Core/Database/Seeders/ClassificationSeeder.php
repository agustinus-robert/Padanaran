<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Modules\Core\Models\Category;
use Modules\Core\Models\CategoryGroup;
use Modules\Core\Models\CategoryGroupType;
use Modules\Core\Models\CompanyBuilding;

class ClassificationSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$classifications = [
			[
				'code' => '01',
				'title' => 'Peralatan Kantor',
				'groups' => [
					[
						'code' => '001',
						'title' => 'Komputer',
						'types' => [
							[
								'code' => '001',
								'title' => 'Monitor',
							],
							[
								'code' => '002',
								'title' => 'CPU Set',
							],
							[
								'code' => '003',
								'title' => 'UPS',
							],
							[
								'code' => '004',
								'title' => 'Headphone',
							],
							[
								'code' => '005',
								'title' => 'Webcam',
							],
							[
								'code' => '006',
								'title' => 'Modem USB',
							],
							[
								'code' => '007',
								'title' => 'Speaker USB',
							]
						]
					],
					[
						'code' => '002',
						'title' => 'Telekomunikasi',
						'types' => [
							[
								'code' => '001',
								'title' => 'PABX',
							],
							[
								'code' => '002',
								'title' => 'Pesawat telepon',
							]
						]
					],
					[
						'code' => '003',
						'title' => 'Storage',
						'types' => [
							[
								'code' => '001',
								'title' => 'NAS',
							],
							[
								'code' => '002',
								'title' => 'Hardisk External',
							]
						]
					],
					[
						'code' => '004',
						'title' => 'Laptop',
						'types' => [
							[
								'code' => '001',
								'title' => 'Laptop',
							]
						]
					],
					[
						'code' => '005',
						'title' => 'AIO',
						'types' => [
							[
								'code' => '001',
								'title' => 'All in one PC',
							]
						]
					],
					[
						'code' => '006',
						'title' => 'Projector',
						'types' => [
							[
								'code' => '001',
								'title' => 'Projector',
							]
						]
					],
					[
						'code' => '007',
						'title' => 'Mobile device',
						'types' => [
							[
								'code' => '001',
								'title' => 'Handphone',
							],
							[
								'code' => '002',
								'title' => 'Tablet',
							],
							[
								'code' => '003',
								'title' => 'HT (Handy Talky)'
							]
						]
					],
					[
						'code' => '008',
						'title' => 'Printer',
						'types' => [
							[
								'code' => '001',
								'title' => 'Printer',
							],
							[
								'code' => '002',
								'title' => 'All in one printer',
							]
						]
					],
					[
						'code' => '009',
						'title' => 'Scanner',
						'types' => [
							[
								'code' => '001',
								'title' => 'Scanner',
							]
						]
					],
					[
						'code' => '010',
						'title' => 'Router',
						'types' => [
							[
								'code' => '001',
								'title' => 'Mikrotik',
							],
							[
								'code' => '002',
								'title' => 'Access Point',
							]
						]
					],
					[
						'code' => '011',
						'title' => 'Server',
						'types' => [
							[
								'code' => '001',
								'title' => 'CPU Server',
							],
							[
								'code' => '002',
								'title' => 'Monitor Server',
							],
							[
								'code' => '003',
								'title' => 'UPS Server'
							]
						]
					],
					[
						'code' => '012',
						'title' => 'CCTV',
						'types' => [
							[
								'code' => '001',
								'title' => 'DVR',
							],
							[
								'code' => '002',
								'title' => 'Kamera CCTV',
							]
						]
					],
					[
						'code' => '013',
						'title' => 'Speaker',
						'types' => [
							[
								'code' => '001',
								'title' => 'Speaker Aktif',
							]
						]
					],
					[
						'code' => '014',
						'title' => 'Peralatan Interpreting',
						'types' => [
							[
								'code' => '001',
								'title' => 'Audio control center',
							],
							[
								'code' => '002',
								'title' => 'Transmitter',
							],
							[
								'code' => '003',
								'title' => 'Headset'
							],
							[
								'code' => '004',
								'title' => 'Receiver'
							]
						]
					],
					[
						'code' => '015',
						'title' => 'Apar',
						'types' => [
							[
								'code' => '001',
								'title' => 'Apar',
							],
						]
					],
					[
						'code' => '016',
						'title' => 'Genset',
						'types' => [
							[
								'code' => '001',
								'title' => 'Genset',
							],
						]
					],
					[
						'code' => '017',
						'title' => 'Elektronik',
						'types' => [
							[
								'code' => '001',
								'title' => 'AC',
							],
							[
								'code' => '002',
								'title' => 'Dispenser',
							],
							[
								'code' => '003',
								'title' => 'Kulkas',
							],
							[
								'code' => '004',
								'title' => 'Air purifier',
							],
							[
								'code' => '005',
								'title' => 'Vaccum cleaner',
							],
							[
								'code' => '006',
								'title' => 'Thermogun',
							],
							[
								'code' => '007',
								'title' => 'TV',
							],
							[
								'code' => '008',
								'title' => 'Papper shredder',
							],
							[
								'code' => '009',
								'title' => 'Exhaust/blower',
							],
							[
								'code' => '010',
								'title' => 'Lain-lain',
							],
						]
					],
					[
						'code' => '018',
						'title' => 'Sparepart',
						'types' => [
							[
								'code' => '001',
								'title' => 'Hardisk',
							],
							[
								'code' => '002',
								'title' => 'LCD Monitor',
							],
							[
								'code' => '003',
								'title' => 'Casing',
							],
							[
								'code' => '004',
								'title' => 'PSU',
							],
							[
								'code' => '005',
								'title' => 'Processor',
							],
							[
								'code' => '006',
								'title' => 'Motherboard',
							],
						]
					],
					[
						'code' => '019',
						'title' => 'Meja',
						'types' => [
							[
								'code' => '001',
								'title' => 'Meja kerja',
							],
							[
								'code' => '002',
								'title' => 'Meja tamu',
							],
							[
								'code' => '003',
								'title' => 'Meja kelas',
							],
						]
					],
					[
						'code' => '020',
						'title' => 'Almari',
						'types' => [
							[
								'code' => '001',
								'title' => 'Almari kayu',
							],
							[
								'code' => '002',
								'title' => 'Almari plat besi',
							],
							[
								'code' => '003',
								'title' => 'Cabinet',
							],
							[
								'code' => '004',
								'title' => 'Etalase kaca',
							],
							[
								'code' => '005',
								'title' => 'Brankas',
							],
						]
					],
					[
						'code' => '021',
						'title' => 'Kursi',
						'types' => [
							[
								'code' => '001',
								'title' => 'Kursi kerja kantor',
							],
							[
								'code' => '002',
								'title' => 'Bench',
							],
							[
								'code' => '003',
								'title' => 'Kursi kelas',
							],
							[
								'code' => '004',
								'title' => 'Kursi meeting'
							]
						]
					],
					[
						'code' => '022',
						'title' => 'Whiteboard',
						'types' => [
							[
								'code' => '001',
								'title' => 'Whiteboard',
							],
							[
								'code' => '002',
								'title' => 'Flipchart',
							],
							[
								'code' => '003',
								'title' => 'Papan pengumuman',
							],
						]
					],
					[
						'code' => '023',
						'title' => 'Perlengkapan bangunan',
						'types' => [
							[
								'code' => '001',
								'title' => 'Teralis',
							],
							[
								'code' => '002',
								'title' => 'Pintu kaca',
							],
							[
								'code' => '003',
								'title' => 'Pintu kayu',
							],
							[
								'code' => '004',
								'title' => 'Closet duduk',
							],
							[
								'code' => '005',
								'title' => 'Neon box',
							],
							[
								'code' => '006',
								'title' => 'Rak pantry',
							],
							[
								'code' => '007',
								'title' => 'Kabinet bawah tangga',
							],
							[
								'code' => '008',
								'title' => 'Sekat gypsum',
							],
							[
								'code' => '009',
								'title' => 'Sekat kayu',
							],
							[
								'code' => '010',
								'title' => 'Sekat kaca',
							],
							[
								'code' => '011',
								'title' => 'Meja taman',
							],
						]
					],
				]
			],
			[
				'code' => '02',
				'title' => 'Aset Tak berwujud',
				'groups' => [
					[
						'code' => '001',
						'title' => 'ISO',
						'types' => [
							[
								'code' => '001',
								'title' => 'ISO 9001',
							],
							[
								'code' => '002',
								'title' => 'ISO 14001',
							],
							[
								'code' => '003',
								'title' => 'ISO 17100'
							],
							[
								'code' => '004',
								'title' => 'ISO 27001'
							]
						]
					]
				]
			],
			[
				'code' => '03',
				'title' => 'Bangunan',
				'groups' => [
					[
						'code' => '001',
						'title' => 'Permanen',
						'types' => [
							[
								'code' => '001',
								'title' => 'Gedung',
							]
						]
					],
					[
						'code' => '002',
						'title' => 'Tidak permanen',
						'types' => [
							[
								'code' => '001',
								'title' => 'Sekat gypsum'
							],
							[
								'code' => '002',
								'title' => 'Sekat Kaca'
							],
							[
								'code' => '003',
								'title' => 'Pos satpam'
							],
							[
								'code' => '004',
								'title' => 'Tangga LPK'
							],
							[
								'code' => '005',
								'title' => 'Kanopi LPK'
							],
							[
								'code' => '006',
								'title' => 'Backdrop'
							],
							[
								'code' => '007',
								'title' => 'Ruang kedap suara'
							],
							[
								'code' => '008',
								'title' => 'Teralis'
							],
							[
								'code' => '009',
								'title' => 'Pengaman/rumah Genset'
							],
							[
								'code' => '010',
								'title' => 'Perlengkapan bangunan'
							]
						]
					]
				]
			],
			[
				'code' => '04',
				'title' => 'Pengembangan program',
				'groups' => [
					[
						'code' => '001',
						'title' => 'Software',
						'types' => [
							[
								'code' => '001',
								'title' => 'Lisensi lifetime',
							],
							[
								'code' => '002',
								'title' => 'SIA',
							],
							[
								'code' => '003',
								'title' => 'Lisensi terbatas'
							],
							[
								'code' => '004',
								'title' => 'Web Apps'
							]
						]
					]
				]
			],
			[
				'code' => '05',
				'title' => 'Tanah',
				'groups' => [
					[
						'code' => '001',
						'title' => 'Tanah',
						'types' => [
							[
								'code' => '001',
								'title' => 'Tanah',
							]
						]
					]
				]
			],
			[
				'code' => '06',
				'title' => 'Kendaraan',
				'groups' => [
					[
						'code' => '001',
						'title' => 'Roda 2',
						'types' => [
							[
								'code' => '001',
								'title' => 'Motor',
							],
							[
								'code' => '002',
								'title' => 'Sepeda',
							]
						]
					],
					[
						'code' => '002',
						'title' => 'Roda 4',
						'types' => [
							[
								'code' => '001',
								'title' => 'Mobil Dinas',
							]
						]
					]
				]
			],
		];

		foreach ($classifications as $key => $classification) {
			$c = Category::create(Arr::only($classification, ['code', 'title']));
			foreach ($classification['groups'] as $_key => $group) {
				$g[$_key] = CategoryGroup::create([
					...Arr::only($group, ['code', 'title']),
					'category_id' => $c->id
				]);
				foreach ($group['types'] as $__key => $value) {
					CategoryGroupType::create([
						...Arr::only($value, ['code', 'title']),
						'group_id' => $g[$_key]->id
					]);
				}
			}
		}

		$building = [
			'kd' => 'hotel-moro-seneng',
			'name' => 'Hotel moro seneng',
			'address_primary' => 'Jl Kaliurang Km 15,5 Hargobinangun Pakem Sleman Yogyakarta 55582',
			'address_secondary' => 'RT 03 RW 20 Sembung',
			'address_city' => 'Sleman',
		];

		$building = new CompanyBuilding($building);

		if ($building->save()) {
			for ($i = 0; $i < 50; $i++) {
				$room = $building->rooms()->create([
					'kd' => $i <= 45 ? 'room-' . $i : 'meeting-room-' . $i,
					'name' => $i <= 45 ? 'Kamar no ' . $i : 'Meeting Room ' . $i,
				]);
			}
		}
	}
}
