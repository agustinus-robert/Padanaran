<?php

namespace Modules\HRMS\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Arr;
use Modules\Account\Models\User;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeScheduleCategory;
use Modules\HRMS\Models\EmployeeScheduleLesson;
use Modules\HRMS\Models\EmployeeScheduleShiftDuty;

use Illuminate\Database\Seeder;
use Modules\Core\Models\CompanyRole;

class HRMSDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedulesTeacher = [
            'Kategori Jadwal Umum' => [
                'jam ke-1' => ['in' => '07:00', 'out' => '07:45'],
                'jam ke-2' => ['in' => '07:45', 'out' => '08:30'],
                // Istirahat pagi
                'jam ke-3' => ['in' => '09:00', 'out' => '09:45'],
                'jam ke-4' => ['in' => '09:45', 'out' => '10:30'],
                'jam ke-5' => ['in' => '10:30', 'out' => '11:15'],
                'jam ke-6' => ['in' => '11:15', 'out' => '12:00'],
                // Istirahat siang
                'jam ke-7' => ['in' => '12:30', 'out' => '13:15'],
                'jam ke-8' => ['in' => '13:15', 'out' => '14:00'],
                'jam ke-9' => ['in' => '14:00', 'out' => '16:00'],
            ],
            'Kategori Jadwal Khusus' => [
                'jam ke-1' => ['in' => '07:00', 'out' => '07:30'],
                'jam ke-2' => ['in' => '07:30', 'out' => '08:00'],
                //'istirahat pagi' => ['in' => '08:30', 'out' => '08:45'],
                'jam ke-3' => ['in' => '08:45', 'out' => '09:15'],
                'jam ke-4' => ['in' => '09:15', 'out' => '09:45'],
                'jam ke-5' => ['in' => '09:45', 'out' => '10:15'],
                'jam ke-6' => ['in' => '10:15', 'out' => '10:45'],
                //'istirahat siang' => ['in' => '11:45', 'out' => '12:00'],
                'jam ke-7' => ['in' => '12:00', 'out' => '12:30'],
                'jam ke-8' => ['in' => '12:30', 'out' => '13:00'],
                'jam ke-9' => ['in' => '13:00', 'out' => '14:30'],
            ]
        ];

        $schedulesShiftTeacher = [
            '1' => [
                'shift ke-1' => ['in' => '07:00', 'out' => '11:00'],
                'shift ke-2' => ['in' => '13:00', 'out' => '16:00']
            ],
            '2' => [
                'shift ke-1' => ['in' => '08:00', 'out' => '10:00'],
                'shift ke-2' => ['in' => '12:00', 'out' => '13:00']
            ]
        ];

        $dataGrade = [4, 5];

        foreach ($dataGrade as $gradeId) {
            foreach($schedulesTeacher as $keyCategory => $valueCategory){
                $category = EmployeeScheduleCategory::create([
                    'name' => $keyCategory,
                    'grade_id' => $gradeId
                ]);

                foreach($valueCategory as $lessonName => $times){
                    EmployeeScheduleLesson::create([
                        'category_schedule_id' => $category->id,
                        'name' => $lessonName,
                        'in' => $times['in'],
                        'out' => $times['out'],
                    ]);
                }
            }
        }


        foreach ($dataGrade as $gradeId) {
            foreach($schedulesShiftTeacher as $keyShift => $valueShift){
                foreach($valueShift as $keys => $values){
                    EmployeeScheduleShiftDuty::create([
                        'name' => $keys,
                        'start_at' => $values['in'],
                        'end_at' => $values['out'],
                        'status' => $keyShift,
                        'grade_id' => $gradeId
                    ]);
                }
            }
        }
    }
}
