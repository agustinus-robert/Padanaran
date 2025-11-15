<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use Modules\Account\Models\User;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyRole;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyPosition;
use Modules\Docs\Models\Document;
use Modules\Docs\Models\DocumentSignature;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\HRMS\Models\EmployeeRecapSubmission;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\HRMS\Models\EmployeeSchedule;
use Modules\HRMS\Models\EmployeeScheduleSubmission;
use Modules\Support\Models\SupportTicket;
use Modules\Support\Models\SupportTicketComment;

class DepartementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [4, 5];
        $departments = [
            ['kd' => 'kepala_sekolah', 'name' => 'Kepala Sekolah'],
            ['kd' => 'administrasi', 'name' => 'Administrasi'],
            ['kd' => 'unit_usaha', 'name' => 'Unit usaha'],
            ['kd' => 'admin', 'name' => 'Admin Umum'],
            ['kd' => 'pondok', 'name' => 'Pondok'],
            ['kd' => 'admin_pondok', 'name' => 'Admin Pondok'],
            ['kd' => 'keamanan', 'name' => 'Keamanan'],
            ['kd' => 'dapur', 'name' => 'Dapur']
        ];


        $positions = [
            ['kd' => 'pengasuh', 'name' => 'Pengasuh', 'dept_id' => 1, 'level' => 1, 'type' => PositionTypeEnum::PENGASUH],
            ['kd' => 'pengurus', 'name' => 'Pengurus', 'dept_id' => 1, 'level' => 2, 'type' => PositionTypeEnum::PENGURUS],
            // Kepala Sekolah
            ['kd' => 'kepala_sekolah', 'name' => 'Kepala Sekolah', 'dept_id' => 1, 'level' => 1, 'type' => PositionTypeEnum::KEPALASEKOLAH],
            // Administrasi
            ['kd' => 'humas', 'name' => 'Humas', 'dept_id' => 2, 'level' => 2, 'type' => PositionTypeEnum::HUMAS, 'parent_ids' => [1]],
            ['kd' => 'tata_usaha', 'name' => 'Tata Usaha', 'dept_id' => 2, 'level' => 2, 'type' => PositionTypeEnum::TATAUSAHA, 'parent_ids' => [1]],
            ['kd' => 'keuangan', 'name' => 'Keuangan', 'dept' => 2, 'level' => 2, 'type' => PositionTypeEnum::KEUANGAN, 'parent_ids' => [1]],
            ['kd' => 'guru', 'name' => 'Guru', 'dept_id' => 2, 'level' => 4, 'type' => PositionTypeEnum::GURU, 'parent_ids' => [1, 2]],
            ['kd' => 'guru_bk', 'name' => 'Guru BK', 'dept_id' => 2, 'level' => 3, 'type' => PositionTypeEnum::GURUBK, 'parent_ids' => [1, 2]],
            ['kd' => 'murid', 'name' => 'Murid', 'dept_id' => 2, 'level' => 5, 'type' => PositionTypeEnum::MURID, 'parent_ids' => [1, 2, 3, 4, 5]],

            // Unit Usaha
            ['kd' => 'kasir_toko', 'name' => 'Kasir Toko', 'dept_id' => 3, 'level' => 2, 'type' => PositionTypeEnum::KASIRTOKO],
            ['kd' => 'kasir_swalayan', 'name' => 'Kasir Swalayan', 'dept_id' => 3, 'level' => 2, 'type' => PositionTypeEnum::KASIRSWALAYAN],
            ['kd' => 'supplier', 'name' => 'Supplier', 'dept_id' => 3, 'level' => 2, 'type' => PositionTypeEnum::SUPPLIER],

            // Admin
            ['kd' => 'admin', 'name' => 'Admin', 'dept_id' => 4, 'level' => 2, 'type' => PositionTypeEnum::ADMIN],

            // Pondok Pesantren
            ['kd' => 'ustadz', 'name' => 'Ustadz', 'dept_id' => 5, 'level' => 2, 'type' => PositionTypeEnum::USTADZ, 'parent_ids' => [1]],
            ['kd' => 'ustadzah', 'name' => 'Ustadzah', 'dept_id' => 5, 'level' => 2, 'type' => PositionTypeEnum::USTADZAH, 'parent_ids' => [1]],

            // extra
            ['kd' => 'admin_pondok', 'name' => 'Admin Pondok', 'dept_id' => 6, 'level' => 2, 'type' => PositionTypeEnum::ADMINPONDOK],
            ['kd' => 'keamanan', 'name' => 'Keamanan', 'dept_id' => 7, 'level' => 2, 'type' => PositionTypeEnum::KEAMANAN],
            ['kd' => 'koki', 'name' => 'Koki', 'dept_id' => 8, 'level' => 2, 'type' => PositionTypeEnum::KOKI],
        ];

        foreach ($departments as $department) {
            foreach ($grades as $grade) {
                CompanyDepartment::create([
                    'kd' => $department['kd'].'-'.$grade,
                    'name' => $department['name'],
                    'grade_id' => $grade
                ]);
            }
        }

        foreach ($positions as $position) {
            $_p = CompanyPosition::create(Arr::only($position, [
                'cmp_id', 'kd', 'name', 'dept_id', 'level', 'type', 'is_visible', 'permissions'
            ]));

            //if (isset($position['is_visible'])) {
                foreach ($grades as $grade) {
                    $_r = CompanyRole::create([
                        'kd' => $position['kd'].'-'.$grade,
                        'name' => $position['name'],
                        'grade_id' => $grade
                    ]);
                }

                switch ($_r->kd) {
                    case 'kepala_sekolah':
                        $_r->permissions()->attach(
                            Permission::whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'humas':
                            $_r->permissions()->attach(
                            Permission::whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'tata_usaha':
                        $_r->permissions()->attach(
                            Permission::whereNotIn('module', ['Account', 'HRMS', 'Administration', 'Core'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'guru':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Academic', 'Teacher'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'guru_bk':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Counseling'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'murid':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Academic'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    // Unit Usaha
                    case 'kasir_swalayan':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Poz'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;
                    case 'kasir_toko':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Poz'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;
                    case 'supplier':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Poz'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    // Admin
                    case 'admin':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Management', 'Core', 'Administration'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    // Pondok Pesantren
                    case 'pengasuh':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Boarding'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'pengurus':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Boarding'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'ustadz':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Boarding'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;
                    case 'ustadzah':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Boarding'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'admin_pondok':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account', 'Boarding'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'keamanan':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    case 'koki':
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;

                    default:
                        $_r->permissions()->attach(
                            Permission::whereIn('module', ['Account'])
                                ->whereNotIn('key', ['cross-login-users'])->pluck('id')
                        );
                        break;
                }

                $_p->setMeta('default_applied_role', $_r);
           // }

            if (isset($position['parent_ids'])) {
                $_p->parents()->sync($position['parent_ids']);
            }
        }

    }
}
