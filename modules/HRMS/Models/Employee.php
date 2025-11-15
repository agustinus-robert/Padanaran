<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Metable\Metable;
use App\Models\Traits\Userstamps\Userstamps;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Account\Models\User;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\HRMS\Models\Traits\EmployeeTrait;
use Modules\Academic\Models\AcademicSubjectMeet;
use Modules\Academic\Models\AcademicSubjectMeetPlan;
use App\Models\References\Grade;

class Employee extends Model
{
    use Metable, Userstamps, Restorable, Searchable, EmployeeTrait;

    /**
     * The table associated with the model.
     */
    protected $table = 'empls';

    /**
     * Define the meta table
     */
    protected $metaTable = 'empl_meta';

    /**
     * Define the meta key name
     */
    public $metaKeyName = 'empl_id';

    /**
     * Prevent meta from being populated
     */
    public $hideMeta = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'kd',
        'grade_id',
        'joined_at',
        'permanent_at',
        'permanent_kd',
        'permananet_sk',
        'exited_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'deleted_at'    => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'joined_at'     => 'datetime',
        'permanent_at'  => 'datetime',
        'exited_at'     => 'datetime',
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'joined_at',
        'permanent_at',
        'exited_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'user.name',
        'kd'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This belongs to user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->whereHas('user', function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
            ->orWhere('username', 'like', '%' . $term . '%');
        });
    }

    /**
     * This has many positions.
     */
    public function positions()
    {
        return $this->hasMany(EmployeePosition::class, 'empl_id');
    }

    public function scopeOrWhereMetaLike($query, $key, $value)
    {
        return $query->orWhereHas('meta', function ($q) use ($key, $value) {
            $q->where('key', $key)->where('value', 'like', "%{$value}%");
        });
    }

    /**
     * This has one position.
     */
    public function position()
    {
        return $this->hasOne(EmployeePosition::class, 'empl_id')->active()->withDefault();
    }

    /**
     * This has many contracts.
     */
    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class, 'empl_id')->orderByDesc('start_at');
    }

    /**
     * This has many contract.
     */
    public function contract()
    {
        return $this->hasOne(EmployeeContract::class, 'empl_id')->active();
    }

    /**
     * This has many contract within 7 days.
     */
    public function contractWithin7Days()
    {
        return $this->hasOne(EmployeeContract::class, 'empl_id')->activeWithin7Days();
    }

    /**
     * This has many contract within 7 days.
     */
    public function contractEndNextMonth()
    {
        return $this->hasOne(EmployeeContract::class, 'empl_id')->endNextMonth();
    }

    public function classroom(){
        return $this->hasOne(\Modules\Academic\Models\AcademicClassroom::class, 'supervisor_id');
    }

    /**
     * This has many scanlogs.
     */
    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class, 'empl_id');
    }

    /**
     * This has many scanlogs teacher.
     */
    public function schedulesTeachers()
    {
        return $this->hasMany(EmployeeScheduleTeacher::class, 'empl_id');
    }

    /**
     * This has many duty teacher.
     */
    public function schedulesDutyTeacher(){
        return $this->hasMany(EmployeeTeacherDuty::class, 'empl_id');
    }

    /**
     * This has many scanlogs.
     */
    public function schedulesSubmission()
    {
        return $this->hasMany(EmployeeScheduleTeacher::class, 'empl_id');
    }

    /**
     * This employee has many scanlogs.
     */
    public function scanlogs()
    {
        // return $this->hasMany(EmployeeScanLog::class, 'empl_id');
        return EmployeeScanLog::where('empl_id', $this->id)->get();
    }

    /**
     * This teacher has many scanlogs.
     */
    public function teachingscanlogs()
    {
        // return $this->hasMany(TeacherScanLog::class, 'empl_id', 'id');
        return EmployeeTeacherScanLog::where('empl_id', $this->id)->get();
    }

    /**
     * This has many vacationQuotas.
     */
    public function vacationQuotas()
    {
        return $this->hasMany(EmployeeVacationQuota::class, 'empl_id');
    }

    /**
     * This has many vacations through quotas.
     */
    public function vacations()
    {
        return $this->hasManyThrough(EmployeeVacation::class, EmployeeVacationQuota::class, 'empl_id', 'quota_id', 'id', 'id');
    }

    /**
     * This has many leaves.
     */
    public function leaves()
    {
        return $this->hasMany(EmployeeLeave::class, 'empl_id');
    }

    /**
     * This has many outworks.
     */
    public function outworks()
    {
        return $this->hasMany(EmployeeOutwork::class, 'empl_id');
    }

    /**
     * This has many overtimes.
     */
    public function overtimes()
    {
        return $this->hasMany(EmployeeOvertime::class, 'empl_id');
    }

    /**
     * This has many insurances.
     */
    public function insurances()
    {
        return $this->hasMany(EmployeeInsurance::class, 'empl_id');
    }

    /**
     * This has many loans.
     */
    public function loans()
    {
        return $this->hasMany(EmployeeLoan::class, 'empl_id');
    }

    public function meets () {
        return $this->hasMany(AcademicSubjectMeet::class, 'teacher_id');
    }

    /**
     * This hasMany plans.
     */
    public function plans () {
        return $this->hasManyThrough(
            AcademicSubjectMeetPlan::class,
            AcademicSubjectMeet::class,
            'teacher_id',
            'meet_id'
        )->selectRaw('acdmc_subject_meet_plans.*, acdmc_subject_meets.semester_id');
    }

    /**
     * This has one salaryTemplates.
     */
    public function salaryTemplates()
    {
        return $this->hasMany(EmployeeSalaryTemplate::class, 'empl_id');
    }

    /**
     * This has one salaryTemplate.
     */
    public function salaryTemplate()
    {
        return $this->hasOne(EmployeeSalaryTemplate::class, 'empl_id')->active()->withDefault();
    }

    /**
     * This has one salaryTemplate.
     */
    public function lastSalaryTemplate()
    {
        return $this->hasOne(EmployeeSalaryTemplate::class, 'empl_id')->lastYear()->latest();
    }

    /**
     * This get main salary methods.
     */
    public function getMainSalary()
    {
        return $this->salaryTemplate->items->firstWhere('name', 'Gaji Pokok')?->amount ?? 0;
    }

    /**
     * This get overtime salary.
     */
    public function getDailySalary()
    {
        $result = $this->getMainSalary();

        if ($result) {
            $result = $result / 20;
        }

        return round($result);
    }

    /**
     * This get overtime salary.
     */
    public function getOvertimeSalary()
    {
        $result = $this->getMainSalary();
        $working_hour = !is_null($this->getMeta('working_hour')) ? $this->getMeta('working_hour') : 8;

        if ($result) {
            $result = $result / 20 / $working_hour * 135 / 100;
        }

        return round($result);
    }

    /**
     * This get overtime salary via active template.
     */
    public function getOvertimeSalaryViaActiveTemplate()
    {
        $template = CompanySalaryTemplate::whereJsonContains('meta->default', true)->first();
        $overtime = CompanySalarySlipComponent::where('kd', 'lembur')->first();
        $result = 0;

        if ($overtime) {
            $items = $this->salaryTemplates()->latest()->where('cmp_template_id', $template->id)->first()?->items;

            $result = $items->firstWhere('component_id', $overtime->id)?->amount != 0
                ? $items->firstWhere('component_id', $overtime->id)?->amount
                : ($this->getOvertimeSalary() ?? 0);
        }

        return round($result);
    }

    public function getOvertimeSalaryTemplate()
    {
        $template = CompanySalaryTemplate::whereJsonContains('meta->default', true)->first();
        $overtime = CompanySalarySlipComponent::where('kd', 'lembur')->first();
        $results  = [];

        if ($overtime) {
            $data = $this->salaryTemplates()->with(['items' => fn($c) => $c->where('component_id', $overtime->id)])->latest()->where('cmp_template_id', $template->id)->limit(4)->get();
            $results = $data->map(function ($items) {
                return [
                    'template_id' => $items->id,
                    'start' => $items->start_at->format('Y-m-d'),
                    'end' => $items->end_at->format('Y-m-d'),
                    'amount' => $items->items->first()->amount,
                ];
            });
        }
        return $results;
    }

    /**
     * This get meal salary via active template.
     */
    public function getMealSalaryViaActiveTemplate()
    {
        $meal = CompanySalarySlipComponent::where('kd', 'tj-makan')->first();
        $result = 0;

        if ($meal) {
            $items = $this->salaryTemplates()->latest()->where('name', 'like', '%bulanan%')->first()?->items;

            $result = $items ? $items->firstWhere('component_id', $meal->id)?->amount : 17500;
        }

        return round($result);
    }

    /**
     * This get transport salary via active template.
     */
    public function getTransportSalaryViaActiveTemplate()
    {
        $transport = CompanySalarySlipComponent::where('kd', 'tj-transportasi')->first();
        $result = 0;

        if ($transport) {
            $items = $this->salaryTemplates()->latest()->where('name', 'like', '%bulanan%')->first()?->items;

            $result = $items->firstWhere('component_id', $transport->id)?->amount ?: 0;
        }

        return round($result);
    }

    /**
     * This get Overdays salary.
     */
    public function getOverdaysSalary()
    {
        $result = $this->getMainSalary();

        if ($result) {
            $result = $result / 20 / 8 * 135 / 100;
        }

        return round($result);
    }

    /**
     * This has many salaries.
     */
    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class, 'empl_id');
    }

    /**
     * This has many dataRecapitulations.
     */
    public function dataRecapitulations()
    {
        return $this->hasMany(EmployeeDataRecapitulation::class, 'empl_id');
    }

    /**
     * This has many dataRecapitulations.
     */
    public function recapSubmissions()
    {
        return $this->hasMany(EmployeeRecapSubmission::class, 'empl_id');
    }

    /**
     * This has many taxs.
     */
    public function taxs()
    {
        return $this->hasMany(EmployeeTax::class, 'empl_id');
    }

    /**
     * When position of department.
     */
    public function scopeWhenPositionOfDepartment($query, $dep, $pos)
    {
        return $query->when(
            $dep,
            fn($q1) =>
            $q1->whereHas('contract.position.position', fn($q3) => $q3->whereIn('dept_id', (array) $dep))->when(
                $pos,
                fn($q2) =>
                $q2->whereHas('contract.position', fn($q3) => $q3->whereIn('position_id', (array) $pos))
            )
        );
    }

    /**
     * Scope with where has.
     */
    public function scopeWithWhereHas($query, $relation, $function)
    {
        return $query->with([$relation => $function])->whereHas($relation, $function);
    }

    /**
     * Scope active employee.
     */
    public function scopeisActive($query)
    {
        return $query->whereHas('contract')->orWhereHas('contractEndNextMonth');
    }

    /**
     * When position of department.
     */
    public function scopeWhenReligions($query, array $religions)
    {
        return $query->when($religions, fn($empl) => $empl->whereHas('user', fn($q) => $q->whereMetaIn('profile_religion', $religions)));
    }

    /**
     * Scope employee as teacher.
     */
    public function scopeisTeacher($query, $dept_id = null)
    {
        // return $query->whereHas('contract.position', fn($position) => $position->whereHas('position', fn($type) => $type->where('type', PositionTypeEnum::TEACHER)));
        return $query->whereHas('contract.position', function ($query) use ($dept_id) {
            $query->whereHas('position', function ($childQuery) use ($dept_id) {
                $childQuery->where('type', PositionTypeEnum::GURU);

                if ($dept_id) {
                    $childQuery->where('dept_id', $dept_id);
                }
            });
        });
    }

    public function scopeisCasier($query){
        return $query->whereHas('contract.position', function ($query) {
            $query->whereHas('position', function ($childQuery) {
                $childQuery->where('type', PositionTypeEnum::KASIRTOKO);

                return $childQuery;            
            });
        });   
    }

    /**
     * Scope employee as teacher.
     */
    public function scopeisTeacherJakarta($query)
    {
        return $query->whereHas('contract.position', fn($position) => $position->whereHas(
            'position',
            fn($type) => $type->where([
                'type' => PositionTypeEnum::TEACHERjAKARTA,
                'dept_id' => 7
            ])
        ));
    }

    /**
     * Scope employee as teacher.
     */
    public function scopeisBackOffice($query)
    {
        return $query->whereHas('contract.position', fn($position) => $position->whereHas('position', fn($type) => $type->where('type', PositionTypeEnum::BACKOFFICE)));
    }

    public function education(){
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}
