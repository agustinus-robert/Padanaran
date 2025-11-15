<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\Setting;
use App\Models\Permission;
use App\Models\Role;

class AppDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $settings = [
            'app_short_name'   => 'Padanaran',
            'app_name'         => 'Padanaran',
            'app_long_name'    => 'Portal untuk Padanaran',
            'meta_author'      => 'backend2',
            'meta_keywords'    => 'website, umkm, e-commerce',
            'meta_image'       => '/img/logo/logo-icon-sq-512.png',
            'meta_description' => config('app.url'),
        ];


        foreach ($settings as $key => $value) {
            Setting::create([
                'key'      => $key,
                'value'    => $value,
            ]);
        }


        $permissions = [
            'Account' => [
                'User' => ['read', 'write', 'delete', 'cross-login'],
                'UserLog' => ['read', 'delete'],
            ],
            'Management' => [
                'Admin' => ['read', 'write', 'delete'],
                'Casier' => ['read', 'write', 'delete'],
                'Warehouse' => ['read', 'write', 'delete']
            ],
            'Core' => [
                'CompanyApprovable' => ['read', 'write', 'delete'],
                'CompanyBuilding' => ['read', 'write', 'delete'],
                'CompanyBuildingRoom' => ['read', 'write', 'delete'],
                'CompanyContract' => ['read', 'write', 'delete'],
                'CompanyDepartment' => ['read', 'write', 'delete'],
                'CompanyInsurance' => ['read', 'write', 'delete'],
                'CompanyInsurancePrice' => ['read', 'write', 'delete'],
                'CompanyLeaveCategory' => ['read', 'write', 'delete'],
                'CompanyStudentLeaveCategory' => ['read', 'write', 'delete'],
                'CompanyMoment' => ['read', 'write', 'delete'],
                'CompanyOutworkCategory' => ['read', 'write', 'delete'],
                'CompanyPosition' => ['read', 'write', 'delete'],
                'CompanyRole' => ['read', 'write', 'delete'],
                'CompanySalarySlip' => ['read', 'write', 'delete'],
                'CompanySalarySlipCategory' => ['read', 'write', 'delete'],
                'CompanySalarySlipComponent' => ['read', 'write', 'delete'],
                'CompanySalaryTemplate' => ['read', 'write', 'delete'],
                'CompanyVacationCategory' => ['read', 'write', 'delete'],
                'CompanyPtkp' => ['read', 'write', 'delete'],
                'CompanyPayrollSetting' => ['read', 'write', 'delete'],
                'CompanyAnnouncement' => ['read', 'write', 'delete'],
                'CompanyLoanCategory' => ['read', 'write', 'delete']
            ],
            'HRMS' => [
                'Employee' => ['read', 'write', 'delete'],
                'EmployeeContract' => ['read', 'write', 'delete'],
                'EmployeeDataRecapitulation' => ['read', 'write', 'delete'],
                'EmployeeDeduction' => ['read', 'write', 'delete'],
                'EmployeeInsurance' => ['read', 'write', 'delete'],
                'EmployeeLeave' => ['read', 'write', 'delete'],
                'EmployeeLoan' => ['read', 'write', 'delete'],
                'EmployeeLoanInstallment' => ['read', 'write', 'delete'],
                'EmployeeLoanInstallmentTransaction' => ['read', 'write', 'delete'],
                'EmployeeOutwork' => ['read', 'write', 'delete'],
                'EmployeeOvertime' => ['read', 'write', 'delete'],
                'EmployeePosition' => ['read', 'write', 'delete'],
                'EmployeeRecapSubmission' => ['read', 'write', 'delete'],
                'EmployeeSalary' => ['read', 'write', 'delete'],
                'EmployeeSalaryTemplate' => ['read', 'write', 'delete'],
                'EmployeeSalaryTemplateItem' => ['read', 'write', 'delete'],
                'EmployeeScanLog' => ['read', 'write', 'delete'],
                'EmployeeSchedule' => ['read', 'write', 'delete'],
                'EmployeeScheduleTeacher' => ['read', 'write', 'delete'],
                'EmployeeTeacherScanLog' => ['read', 'write', 'delete'],
                'EmployeeScheduleSubmission' => ['read', 'write', 'delete'],
                'EmployeeScheduleSubmissionTeacher' => ['read', 'write', 'delete'],
                'EmployeeTax' => ['read', 'write', 'delete'],
                'EmployeeVacation' => ['read', 'write', 'delete'],
                'EmployeeVacationQuota' => ['read', 'write', 'delete']
            ],
            'Portal' =>[
                'Portal' => ['read', 'write', 'update']
            ],
            'Academic' => [
                'Academic' => ['read','write','update']
            ],
            'Counseling' => [
                'CounselingCategory' => ['read', 'write', 'update'],
                'CounselingCaseCategory' => ['read', 'write', 'update'],
                'CounselingCaseDescription' => ['read', 'write', 'update'],
                'Counseling' => ['read', 'write', 'update'],
                'CounselingCase' => ['read', 'write', 'update'],
                'CounselingPresence' => ['read', 'write', 'update'],
            ],
            'Teacher' => [
                'Plan' => ['read', 'write', 'update'],
                'StudentSemesterCase' => ['read', 'write', 'update']
            ],
            'Administration' => [
                'BillBatch' => ['read','write','update'],
                'BillReference' => ['read','write','update'],
                'BillStudent' => ['read','write','update'],
                'CurriculaMeet' => ['read','write','update'],
                'CurriculaSubjectCategory' => ['read','write','update'],
                'CurriculaSubject' => ['read','write','update'],
                'DatabaseAcademic' => ['read','write','update'],
                'DatabaseAcademicSemester' => ['read','write','update'],
                'DatabaseCurricula' => ['read','write','update'],
                'EmployeeTeacher' => ['read','write','update'],
                'FacilityAssetCategories' => ['read','write','update'],
                'FacilityAsset' => ['read','write','update'],
                'FacilityBuilding' => ['read', 'write', 'update'],
                'FacilityRoom' => ['read', 'write', 'update'],
                'ScholarAchivement' => ['read', 'write', 'update'],
                'ScholarAppreciation' => ['read', 'write', 'update'],
                'ScholarClassroom' => ['read', 'write', 'update'],
                'ScholarSemester' => ['read', 'write', 'update'],
                'ScholarMajor' => ['read', 'write', 'update'],
                'ScholarOrganization' => ['read', 'write', 'update'],
                'ScholarStudent' => ['read', 'write', 'update'],
                'ScholarStudy' => ['read', 'write', 'update'],
                'ScholarSuperior' => ['read', 'write', 'update']
            ]
        ];

        foreach ($permissions as $module => $models) {
            foreach ($models as $model => $actions) {
                foreach ($actions as $permission) {
                    Permission::create([
                        'module' => $module,
                        'name' => ucfirst(str($permission)->append(' ' . str(str()->snake($model, ' '))->plural())),
                        'model' => $model,
                        'description' => 'Allow user to ' . strtolower(str($permission)->append(' ' . str(str()->snake($model, ' '))->plural())),
                        'key' => str()->slug(str($permission)->append(' ' . str(str()->snake($model))->plural()))
                    ]);
                }
            }
        }

        // $roles = [
        //     [
        //         'kd' => 'root',
        //         'name' => 'Super Administrator',
        //         'role' => Permission::all()->pluck('id')
        //     ],
        //     [
        //         'kd' => 'admin',
        //         'name' => 'Pemilik Usaha',
        //         'role' => Permission::whereIn('key', ['management'])->pluck('id')
        //     ],
        //     [
        //         'kd' => 'guru',
        //         'name' => 'Guru',
        //         'role' => Permission::whereIn('module', ['Academic', 'Teacher'])->pluck('id')
        //     ],
        //     [
        //         'kd' => 'customer',
        //         'name' => 'Pembeli',
        //         'role' => Permission::whereIn('module', ['User'])->pluck('id')
        //     ]
        // ];

        // foreach ($roles as $role) {
        //     $_role = Role::create(Arr::only($role, ['kd', 'name']));
        //     $_role->permissions()->attach($role['role']);
        // }
    }
}
